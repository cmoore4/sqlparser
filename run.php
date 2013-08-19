<?php
ini_set('display_errors', 'stdout');

include 'sqlfile.php';

$parser = new SQLParser\Sqlfile('database.sql');
$parser->parseStatements();
$parser->parseTables();