<?php
namespace SQLParser;

class Sqltable {

	protected $statement;

	protected $temp = false;
	protected $ifNotExists = false;
	protected $likeSource = null;

	protected $name;

	protected $rows = array();
	protected $fieldDefinitions;

	public function __construct($statement){
		$this->statement = $statement;
		$this->parseFields();
	}

	protected function parseFields(){
		//TODO: Does not account for 'LIKE tbl_name' style tables
		$allfieldsRegex = '/CREATE .+? (\w*)\s?\((.+)\).*/i';
		//print 'Parsing statement: ' . $this->statement . "\n";

		if(preg_match($allfieldsRegex, $this->statement, $matches) === 1){
			$this->name = $matches[1];
			$this->parseFields($matches[2]);
		} else {
			//TODO : error here?
		}
	}

	protected function parseField($allfields){
		//$this->fieldDefinitions = explode(',', $allfields);
	}
}