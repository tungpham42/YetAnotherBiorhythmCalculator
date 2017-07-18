<?php
class Model
{
	public static function insert($table, $values)
	{
		$fields = array_keys($values);
		$values = array_map("addslashes", $values);
		$q = "INSERT INTO wt_{$table} (".implode(",", $fields).") VALUES ('".implode("','", $values)."')";
		DB::ins()->query($q);
	}

	public static function select($table, $domain)
	{
		$q = "SELECT * FROM wt_{$table} WHERE Domain = '".addslashes($domain)."'";
		return DB::ins()->queryRow($q);
	}

	public static function delete($table, $id)
	{
		$q = "DELETE FROM wt_$table WHERE Domain = '".addslashes($domain)."'";
		DB::ins()->query($q);
	}

	public static function update($table, array $data, $domain)
	{
		$u = "";
		foreach($data as $f=>$v)
		  $u .= "`{$f}` = '".addslashes($v)."',";
		$u = mb_substr($u, 0, -1);
		$q = "UPDATE wt_{$table} SET {$u} WHERE Domain = '".addslashes($domain)."'";
		DB::ins()->query($q);
	}
}