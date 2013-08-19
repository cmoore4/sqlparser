<?php
namespace SQLParser;
include 'sqlcolumn.php';

class Sqltable {

	protected $statement;

	protected $temp = false;
	protected $ifNotExists = false;
	protected $likeSource = null;

	protected $name;

	protected $columns = array();
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
		foreach($this->fieldDefinitions as $def){
			$reserved_words = array('foreign', 'key', 'primary', 'constraint', 'fulltext', 'spacial', 'check');
			$name = strtok($def, ' ');

			if(in_array(strtolower($name), $reserved_words)){
				//TODO: new Sqlconstraint($def)
				return null;
			} else {
				$columns[] = new Sqlcolumn($def);
			}
		}
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

			if(($endable && ($char === ',')) || $start+$length+1 == strlen($allFields)){
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