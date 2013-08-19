<?php
namespace SQLParser;

class Sqlcolumn {

	protected $definition = '';

	protected $name;
	protected $type;
	protected $primaryKey = false;
	protected $autoIncrement;
	protected $uniqueKey = false;
	protected $null =true;
	protected $default;
	protected $currentTimestamp;
	protected $unsigned = false;
	protected $zerofill = false;
	protected $characterSet;
	protected $collate;
	protected $binary;

	protected $comment;

	static $types = array(
		'BIT',
		'BOOLEAN',
		'TINYINT',
		'SMALLINT',
		'MEDIUMINT',
		'INT',
		'INTEGER',
		'BIGINT',
		'REAL',
		'DOUBLE',
		'FLOAT',
		'DECIMAL',
		'NUMERIC',
		'DATE',
		'TIME',
		'TIMESTAMP',
		'DATETIME',
		'YEAR',
		'CHAR',
		'VARCHAR',
		'BINARY',
		'VARBINARY',
		'TINYBLOB',
		'BLOB',
		'MEDIUMBLOB',
		'LONGBLOB',
		'TINYTEXT',
		'TEXT',
		'MEDIUMTEXT',
		'LONGTEXT',
		'ENUM',
		'SET'
	);

	/**
	 * For storing the values of enum and sets
	 * @var array
	 */
	protected $values = array();

	public function __construct($definition){
		$this->definition = trim($definition);
		$this->parse();
	}

	protected function parse(){
		$this->name = $this->getName();
		$this->type = $this->getType();
		print "{$this->name} -- {$this->type}\n";
	}

	//TODO: Will it always be a space, or do we need to account for any whitespace?
	//test versus split('/\s/')
	protected function getName(){
		return trim(strtok($this->definition, ' '));
	}

	protected function getType(){
		foreach(self::$types as $type){
			if(stripos($this->definition, ' ' . $type . ' ') !== false){
				return $type;
			}
			if(stripos($this->definition, ' ' . $type . '(') !== false){
				return $type;
			}
			if(stripos($this->definition, ' ' . $type . ',') !== false){
				return $type;
			}
		}
	}
}