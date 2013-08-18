<?php

include 'sqlfile.php';

$parser = new SQLParser\Sqlfile('database.sql');
$parser->parseStatements();
print_r($parser->statements);