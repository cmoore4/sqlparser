<?php

namespace SQLParser;

class Sqlfile {

	public $fileHandle;
	public $fileName;
	public $statements = array();

	public function __construct($filename){

		$this->fileName = $filename;

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
		$endRegex = '/.*;\s*$/';
		// Finds lines that begin with '--'
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
	}

}