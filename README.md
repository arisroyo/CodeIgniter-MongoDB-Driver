#CodeIgniter MongoDB Driver Library
========

This library inspired by CIMongo - MongoDB Library to help perform simple MongoDB based queries using MongoDB Driver in CodeIgniter.

This library work with single document process
* Query
* Insert
* Update
* Delete
* isExist

##Methods
* `query` Return MongoDB Array Of Object, accept collection name, filter and option.
* `insert` Return nothing, accept collection name and document to insert.
* `update` Return nothing, accept collection name and document to update and query.
* `delete` Return nothing, accept collection name and document query for deletion.
* `isExist` Return true/false, accept collection name and query (filter and option)

```
Query examples
<?php
$this->load->library('Mongodriver');
$filter = [];
$option = ['limit' => 10];
$results = $this->mongodriver->query("tweets",$filter,$option);
foreach ($results as $row) {
	echo ($row->_id) . "</br>\n";
}
?>
```

```
Insert examples
<?php
$this->load->library('Mongodriver');
$tweets = array('tweetid'=>1,'text'=>"This sample Tweet",'date'=>"July 15, 2017")
$this->mongodriver->insert("tweets",$tweets);
?>
```

```
Update examples
<?php
$this->load->library('Mongodriver');
$query = array('tweetid'=>1,'date'=>"July 15, 2017");
$tweets = array('tweetid'=>1,'text'=>"Latest Tweet Update MongoDB Driver Method",'date'=>"Aug 20, 2017")
$this->mongodriver->update("tweets",$tweets);
?>
```

```
isExist examples
<?php
$this->load->library('Mongodriver');
$query = ["id" =>1];
if (!$this->mongodriver->isExist("tweets",$query)) {
 echo "Tweet has been found."
}
?>
```

## Authors

* **Aristedes Royo** - *Initial work* 

## License

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
