<?php

/*
 | -------------------------------------------------------------------------
 | DATABASE CONNECTIVITY SETTINGS
 | -------------------------------------------------------------------------
 | This file will contain the settings needed to access your Mongo database.
 |
 |
 | ------------------------------------------------------------------------
 | EXPLANATION OF VARIABLES
 | ------------------------------------------------------------------------
 |
 |	['hostname'] The hostname of your database server.
 |	['username'] The username used to connect to the database
 |	['password'] The password used to connect to the database
 |	['database'] The name of the database you want to connect to
 |	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
 |
 | The $config['mongodriver_db']['active'] variable lets you choose which connection group to
 | make active.  By default there is only one group (the 'default' group).
 |
 */

$config['mongodriver_db']['active'] = 'default';

$config['mongodriver_db']['default']['no_auth'] = FALSE;
$config['mongodriver_db']['default']['hostname'] = 'localhost';
$config['mongodriver_db']['default']['port'] = '27017';
$config['mongodriver_db']['default']['username'] = '';
$config['mongodriver_db']['default']['password'] = '';
$config['mongodriver_db']['default']['database'] = '';
$config['mongodriver_db']['default']['db_debug'] = TRUE;
