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

		if(preg_match($allfieldsRegex, $this->statement, $matches) === 1){
			$this->name = $matches[1];
			$this->parseField($matches[2]);
		} else {
			//TODO : error here?
		}
	}

	protected function parseField($allfields){
		$this->fieldDefinitions =  $this->parseLines($allfields);
		print "\n\n{$this->name} :\n";
		foreach($this->fieldDefinitions as $def){ print "\t$def\n"; }
	}

	protected function parseLines($allFields){
		
		$endable = true;
		$length = 0;
		$start = 0;
		$lines = array();
		
		foreach(str_split($allFields) as $char){
			
			if($char === '('){ 
				$endable = false; 
			} elseif ($char === ')') {
				$endable = true;	
			}

			if($endable && ($char === ',')){
				$lines[] = substr($allFields, $start, $length);
				$start = $start+$length+1;
				$length = 0;
				continue;
			}

			$length++;
		}

		return $lines;
	}
}