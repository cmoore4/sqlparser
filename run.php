<?php
include 'sqlfile.php';

$parser = new SQLParser\Sqlfile('database.sql');
$parser->parseStatements();
$parser->parseTables();