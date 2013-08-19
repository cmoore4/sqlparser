<?php
namespace SQLParser;
include 'sqltable.php';

class Sqlfile {

	public $fileHandle;
	public $fileName;
	public $dialect;
	public $statements = array();
	public $tables = array();

	public function __construct($filename, $dialect = 'mysql'){

		$this->fileName = $filename;
		$this->dialect = $dialect;

		// Open the file
		try{
			$this->fileHandle = new \SplFileObject($filename);
			$this->fileHandle->setFlags(
				\SplFileObject::DROP_NEW_LINE + 
				\SplFileObject::READ_AHEAD + 
				\SplFileObject::SKIP_EMPTY);

		} catch (Exception $e){
			// TODO: sane error handling
		}
	}

	public function parseStatements(){
		$statement = '';

		// This regex will find any line that ends with a semi-colon, the end of a statement
		// TODO: this is naieve.  Will never detect multi-line statements.  Also, will detect
		// any end-line semi-colon, even if it's in a string, ex ';\n'
		$endRegex = '/.*;\s*$/';

		// Finds lines that begin with '--'
		// TODO: add parsing/stripping intra-line comments: SELECT abc, def -- def is a column
		$commentRegex = '/\s*--.*/';

		// Explode file by ';' in a memory-sane way
		while (!$this->fileHandle->eof()){
			
			//read the next line, append it to any open statements
			$line = $this->fileHandle->fgets();

			//see if line is a comment
			if(preg_match($commentRegex, $line) === 1){
				continue;
			}

			$statement .= $line;

			//see if line is end of statement
			if(preg_match($endRegex, $statement) === 1){
				$this->statements[] = $statement;
				$statement = '';
			}
		}

		$this->fileHandle = null;
	}

	public function parseTables(){
		$tableRegex = '/\w*CREATE (TEMPORARY )?TABLE.*/i';
		foreach($this->statements as $statement){
			if(preg_match($tableRegex, $statement) === 1){
				$this->tables[] = new Sqltable($statement);
			}
		}
	}

}