<?php
ini_set('memory_limit','512M');

include 'sqlfile.php';

$parser = new SQLParser\Sqlfile('database.sql');
$parser->parseStatements();
$parser->parseTables();