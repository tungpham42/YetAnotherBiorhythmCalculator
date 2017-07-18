<?php
class DBException extends Exception {}

class DB extends MySQLi
{
	private static $ins;

	private function __construct(Config $config)
	{
		parent::__construct($config->host, $config->username, $config->passwd, $config->dbname);
		if($this->connect_errno)
			throw new DBException("Could not connect to MySQL Server...");
		$this->set_charset("utf8");
	}

	public static function ins()
	{
		if(empty(self::$ins))
			self::$ins = new self(ConfigFactory::load('db'));
		return self::$ins;
	}

	public function query($q)
	{
		if(!$res = parent::query($q))
			throw new DBException(sprintf("Query: %s \n Error: %s", $q, $this->error));
		return $res;
	}

	public function queryAll($q, $id = false)
	{
		$data = array();
		$res = $this->query($q);
		if($res->num_rows == 0)
			return $data;

		while($row = $res->fetch_assoc())
			if ($id)
				$data[$row[$id]] = $row;
			else
				$data[] = $row;
		$res->free_result();
		return $data;
	}

	public function queryScalar($q)
	{
		$res = $this->query($q);
		$row = $res->fetch_row();
		$res->free_result();
		return (int)$row[0];
	}

	public function queryRow($q)
	{
		$data = array();
		$res = $this->query($q);
		if($res->num_rows == 0)
			return $data;
		$row = $res->fetch_assoc();
		$res->free_result();
		return $row;
	}

	public function singleArray($q, $k)
	{
		$data = array();
		$res = $this->query($q);
		if($res->num_rows == 0)
			return $data;

		while($row = $res->fetch_assoc())
			$data[] = $row[$k];

		$res->free_result();
		return $data;
	}

	private function __clone() {}
}