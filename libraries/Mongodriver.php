<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mongodriver {
	
	private $CI;
	private $config = array();
	private $hostname;
	private $port;
	private $username;
	private $password;
	private $debug;
	private $manager;
	private $database;
	
	function __construct() {
		
		if ( ! class_exists('MongoDB\Driver\Manager')) {
			show_error("The MongoDB Driver has not been installed or enabled", 500);
		}
		$this->CI =& get_instance();
		$this->CI->load->config('mongodriver_db');
		$this->config = $this->CI->config->item('mongodriver_db');
		$this->connect();
	}
	
	private function connect()
	{
		$this->prepare();
		try
		{
			$dns = "";
			
			if(isset($this->config[$this->activate]['no_auth']) == TRUE && $this->config[$this->activate]['no_auth'] == TRUE) {
				$dns = "mongodb://{$this->hostname}:{$this->port}";
			} else {
				$dns = "mongodb://{$this->username}:{$this->password}@{$this->hostname}:{$this->port}";
			}
			
			$this->manager = new MongoDB\Driver\Manager($dns);
			
		} catch (MongoDB\Driver\Exception\Exception $mx) {
		
			if(isset($this->debug) == TRUE && $this->debug == TRUE) {
				show_error("Unable to connect to MongoDB: {$mx->getMessage()}", 500);
			} else {
				show_error("Unable to connect to MongoDB", 500);
			}
		}
	}
	
	public function query($collection ="", $filter = array() , $option = array()) {
		
		$result = array();
		
		if (empty($collection)) {
			show_error("No Mongo collection selected to query into", 500);
		}
		
		if (!is_array($filter)) {
			show_error("Mongo filter is not an array", 500);
		} 
		
		if (!is_array($option)) {
			show_error("Mongo option is not an array", 500);
		} 
		
		try
		{
		
			$query = new MongoDB\Driver\Query($filter,$option);
			$result = $this->manager->executeQuery("{$this->database}.{$collection}", $query);
			
		} catch (MongoDB\Driver\Exception\Exception $mx) {
			if(isset($this->debug) == TRUE && $this->debug == TRUE) {
				show_error("Insert of document into MongoDB failed: {$mx->getMessage()}", 500);
			} else {
				show_error("Insert of document into MongoDB failed", 500);
			}
		}
		
		return $result;
		
	}
	
	public function insert($collection ="", $document = array()) {
		
		if (empty($collection))
		{
			show_error("No Mongo collection selected to insert into", 500);
		}
		
		if (!is_array($document) || count($document) == 0)
		{
			show_error("Nothing to insert into Mongo collection or document is not an array", 500);
		}
		
		try
		{
		
			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->insert($document);
			
			$this->manager->executeBulkWrite("{$this->database}.{$collection}", $bulk);
		
		
		} catch (MongoDB\Driver\Exception\Exception $mx) {
			if(isset($this->debug) == TRUE && $this->debug == TRUE) {
				show_error("Insert of document into MongoDB failed: {$mx->getMessage()}", 500);
			} else {
				show_error("Insert of document into MongoDB failed", 500);
			}
		}
		
	}
	

	public function delete($collection ="", $document = array()) {
		
		if (empty($collection))
		{
			show_error("No Mongo collection selected to insert into", 500);
		}
		
		if (!is_array($document) || count($document) == 0)
		{
			show_error("Nothing to delete into Mongo collection or document is not an array", 500);
		}
		
		try
		{
			
			$bulk = new MongoDB\Driver\BulkWrite;
			$bulk->delete($document);
			
			$this->manager->executeBulkWrite("{$this->database}.{$collection}", $bulk);
			
			
		} catch (MongoDB\Driver\Exception\Exception $mx) {
			if(isset($this->debug) == TRUE && $this->debug == TRUE) {
				show_error("Delete of document into MongoDB failed: {$mx->getMessage()}", 500);
			} else {
				show_error("Delete of document into MongoDB failed", 500);
			}
		}
		
	}
	
	public function isExist($collection ="", $query = array()) { 
		
		$exist = false;
		
		if (empty($collection))
		{
			show_error("No Mongo collection selected to insert into", 500);
		}
		
		if (!is_array($query) || count($query) == 0)
		{
			show_error("Nothing to filter into Mongo collection or filter is not an array", 500);
		}
		
		try {
			
			$command = new MongoDB\Driver\Command(["count" => $collection, "query" => $query]);
			$result = $this->manager->executeCommand($this->database, $command);
			$resultArray = current($result->toArray());
			
			if ($resultArray->n >= 1 ) {
				$exist = true;
			}
			
		} catch (MongoDB\Driver\Exception\Exception $mx) {
			if(isset($this->debug) == TRUE && $this->debug == TRUE) {
				show_error("isExist into MongoDB failed: {$mx->getMessage()}", 500);
			} else {
				show_error("Insert into MongoDB failed", 500);
			}
		}
		
		return $exist;
	}
	
	private function prepare() {
		
		if(isset($this->config['active']) && !empty($this->config['active']))
		{
			$this->activate = $this->config['active'];
		}else
		{
			show_error("MongoDB configuration is missing.", 500);
		}
		
		if(isset($this->config[$this->activate]) == TRUE)
		{
			if(empty($this->config[$this->activate]['hostname']))
			{
				show_error("Hostname missing from mongodb config group : {$this->activate}", 500);
			}
			else
			{
				$this->hostname = trim($this->config[$this->activate]['hostname']);
			}
			
			if(empty($this->config[$this->activate]['port']))
			{
				show_error("Port number missing from mongodb config group : {$this->activate}", 500);
			}
			else
			{
				$this->port = trim($this->config[$this->activate]['port']);
			}
			
			
			if(empty($this->config[$this->activate]['database']))
			{
				show_error("Database name missing from mongodb config group : {$this->activate}", 500);
			}
			else
			{
				$this->database = trim($this->config[$this->activate]['database']);
			}
			
			if(isset($this->config[$this->activate]['no_auth']) == FALSE
					&& empty($this->config[$this->activate]['username']))
			{
				show_error("Username missing from mongodb config group : {$this->activate}", 500);
			}
			else
			{
				$this->username = trim($this->config[$this->activate]['username']);
			}
			
			if(isset($this->config[$this->activate]['no_auth']) == FALSE
					&& empty($this->config[$this->activate]['password']))
			{
				show_error("Password missing from mongodb config group : {$this->activate}", 500);
			}
			else
			{
				$this->password = trim($this->config[$this->activate]['password']);
			}
			
			if(empty($this->config[$this->activate]['db_debug']))
			{
				$this->debug = FALSE;
			}
			else
			{
				$this->debug = $this->config[$this->activate]['db_debug'];
			}
		}
		else
		{
			show_error("mongodb config group :  <strong>{$this->activate}</strong> does not exist.", 500);
		}
	}
	
	
	
	function __destruct()
	{
		//Nothing todo for now
	}
	
	
	
}
