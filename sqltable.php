<?php
namespace SQLParser;

class Sqltable {

	protected $temp = false;
	protected $ifNotExists = false;
	protected $likeSource = null;

	protected $name;

	protected $rows = array();

	public function __construct(){

	}


}