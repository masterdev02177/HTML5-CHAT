<?php

require_once(__DIR__.'/../Config.php');
$con1 = mysqli_connect(hostname_con1, username_con1, password_con1, database_con1) or trigger_error(mysqli_error(DB::$con1),E_USER_ERROR);
mysqli_set_charset($con1, 'utf8');
DB::$con1 = $con1;

class DB {

	public static $id = 'id';
	public static $debug = false;
	public static $lastSQL = '';



	function __construct() {
	}

	public static $con1;


	public static function real_escape_string($value) {
		return mysqli_real_escape_string(DB::$con1, $value);
	}


	public static function clearData($value) {
		return mysqli_real_escape_string(DB::$con1, $value);
	}


	public static function selectAsAssociativeArray($table, $array, $where = '', $debug=true) {
		$sql = sprintf(
				"SELECT %s from $table $where",
				implode(',',$array)
		);
		if ($debug) echo $sql;
		$res = mysqli_query(DB::$con1,$sql) or die (mysqli_error());
		$ArrayResult = array();
		while ($row = mysqli_fetch_assoc($res)) {
			$ArrayResult[] = $row;
		}
		return( $ArrayResult );
	}

	public static function sqlPagination($sql, $page, $resultParPage) {
		$rows = array();
		$res = mysqli_query(DB::$con1,$sql);

		$total = mysqli_fetch_object(mysqli_query(DB::$con1, "SELECT FOUND_ROWS() as total"))->total;

		while ($row = mysqli_fetch_object($res)) {
			$rows[] = $row;
		}
		$resultat = new stdClass();
		$resultat->total = $total;
		$resultat->currentPage = $page;
		$resultat->totalPages = ceil($total / $resultParPage);
		$resultat->rows = $rows;
		return( $resultat );
	}

	public static function deleteFromTable($table, $where='', $debug='') {
		$sql = "delete from $table $where";
		if ($debug) echo $sql;

		mysqli_query(DB::$con1, $sql);
		return mysqli_insert_id(DB::$con1);
	}

	public static function selectAllBySQL($sql) {
		$ArrayResult = array();
		$res = mysqli_query(DB::$con1, $sql) or die ($sql);
		while ($row = mysqli_fetch_object($res)) {
			$ArrayResult[] = $row;
		}
		return $ArrayResult;
	}

	public static function selectAllBySQLAssociativeArray($sql) {
		$ArrayResult = array();
		$res = mysqli_query(DB::$con1, $sql) or die ($sql);
		while ($row = mysqli_fetch_row($res)) {
			$ArrayResult[] = $row;
		}
		return $ArrayResult;
	}

    public static function selectAllBySQLAssociativeArray2($sql) {
        $ArrayResult = array();
        $res = mysqli_query(DB::$con1, $sql) or die ($sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $ArrayResult[] = $row;
        }
        return $ArrayResult;
    }

    public static function selectOneBySQLAssociativeArray($sql) {
        $res = mysqli_query(DB::$con1, $sql) or die ($sql);
        $row = mysqli_fetch_assoc($res);
        return $row;
    }

	public static function getOne($table, $where='',  $debug=false) {
		$sql="select * from $table $where LIMIT 0,1";
		if ($debug) echo $sql;
		$res = mysqli_query(DB::$con1, $sql);
		$obj = mysqli_fetch_object($res);
		return $obj;
	}

	public static function getOneById($id, $table, $debug=false) {
		$sql = sprintf("select * from $table where %s=$id", DB::$id);
		if ($debug) echo $sql;
		$res = mysqli_query(DB::$con1, $sql);
		$obj = mysqli_fetch_object($res);
		return $obj;
	}


	public static function get($id, $table, $moreWhereConditions='', $debug=false) {
		$sql = "select * from $table WHERE ".DB::$id."='$id' $moreWhereConditions";
		if ($debug) echo $sql;


		$res = mysqli_query(DB::$con1, $sql);
		$obj = mysqli_fetch_object($res);
		return $obj;
	}


	public static function getAll($table, $where="", $order="", $debug=false) {
		$sql = "select * from $table $where $order ";
		$ArrayResult = array();
		$res = mysqli_query(DB::$con1, $sql) or die ($sql);
		while ($row = mysqli_fetch_object($res)) {
			$ArrayResult[] = $row;
		}
		if ($debug)  {
			echo $sql;
		}
		return ($ArrayResult);
	}

	public static function sqlExecute($sql) {
		mysqli_query(DB::$con1, $sql);
	}

	public static function getPage($table, $where='', $order='', $page=0, $limit=10, $debug=false) {
		$sql = "select count(id) as total from $table";
		$res = mysqli_query(DB::$con1, $sql);
		$total = mysqli_fetch_object($res)->total;
		$rows = array();
		$ArrayResult = new stdClass();
		$ArrayResult->total = $rows;
		$ArrayResult->page = $page;
		$ArrayResult->totalPages = ceil($total/$limit);

		$sql="select * from $table $where $order LIMIT $page*$limit, $limit";
		if ($debug) echo $sql;

		$res = mysqli_query(DB::$con1, $sql) or die ($sql);
		while ($row = mysqli_fetch_object($res)) {
			$rows[] = $row;
		}
		$ArrayResult->rows = $rows;
		return ($ArrayResult);
	}


	public static function delete($table, $id, $moreWhereConditions='', $debug = false) {
		$sql = "delete from $table where ".DB::$id."=$id $moreWhereConditions";
		if ($debug) echo $sql;
		$res = mysqli_query(DB::$con1, $sql);
		return mysqli_insert_id(DB::$con1);
	}
	public static function insertOLD($table, $array, $debug = false) {
		$values = array_values($array);
		$values = array_map(array(DB::$con1, 'real_escape_string'), $values);

		$sql = sprintf(
				"INSERT INTO $table (%s) VALUES ('%s')",
				implode(',', array_keys($array)),
				implode("','", $values)
		);
		mysqli_query(DB::$con1, ($sql));
		if ($debug) echo $sql;
		return mysqli_insert_id(DB::$con1);
	}

    public static function addObliqueQuote(&$value,$key) {
        $value = "`$value`";
    }

    public static function insert($table, $array, $debug = false) {
        $values = array_values($array);
        $values = array_map(array(DB::$con1, 'real_escape_string'), $values);

        $keys = array_keys($array);

        array_walk($keys, array('self', 'addObliqueQuote'));

        $sql = sprintf(
            "INSERT INTO $table (%s) VALUES ('%s')",
            implode(',', $keys),
            implode("','", $values)
        );
        mysqli_query(DB::$con1, ($sql));
        if ($debug) echo $sql;
        return mysqli_insert_id(DB::$con1);
    }

	public static function replace($table, $array, $debug = false) {
		$values = array_values($array);
		$values = array_map(array(DB::$con1, 'real_escape_string'), $values);

		$sql = sprintf(
				"replace INTO $table (%s) VALUES ('%s')",
				implode(',',array_keys($array)),
				implode("','",$values)
		);
		if ($debug) echo $sql;
		mysqli_query(DB::$con1, ($sql));
		return mysqli_insert_id(DB::$con1);
	}

	public static function fetchOne($table) {
		$sql = "select * from $table LIMIT 0,1";
		$res = mysqli_query(DB::$con1, $sql);
		return mysqli_fetch_object($res);
	}

	public static function execSQL($sql)  {
		mysqli_query(DB::$con1, $sql);
	}

	public static function select($table, $array, $where='', $debug=false) {
		$sql = sprintf(
				"SELECT %s from $table $where",
				implode(',',$array)
		);
		if ($debug) echo $sql;
		$res = mysqli_query(DB::$con1, $sql) or die (mysqli_error(DB::$con1));
		$ArrayResult = array();
		while ($row = mysqli_fetch_object($res)) {
			$ArrayResult[] = $row;
		}
		return( $ArrayResult );
	}

	public static function selectOne($table, $array, $where='', $debug=false) {
		$sql = sprintf(
				"SELECT %s from $table $where LIMIT 0,1",
				implode(',',$array)
		);
		if ($debug) echo $sql;
		$res = mysqli_query(DB::$con1, $sql) or die (mysqli_error(DB::$con1));
		$ArrayResult = array();
		$row = mysqli_fetch_object($res);
		return $row;
	}

    public static function updateWhere($table, $moreWhereConditions, $array, $debug=false) {
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                $value = mysqli_real_escape_string(DB::$con1, $value);
                $value = "'$value'";
                $updates[] = "$key = $value";
            }
        }
        $implodeArray = implode(', ', $updates);


        $sql = ("UPDATE $table SET $implodeArray $moreWhereConditions");
        if ($debug) echo $sql;
        //exit($sql);
        DB::execSQL($sql);
        return mysqli_affected_rows(DB::$con1);
    }


	public static function update($table, $id, $array, $moreWhereConditions='', $debug=false) {
		if (count($array) > 0) {
			foreach ($array as $key => $value) {
				$value = mysqli_real_escape_string(DB::$con1, $value);
				$value = "'$value'";
				$updates[] = "$key = $value";
			}
		}
		$implodeArray = implode(', ', $updates);
		$sql = ("UPDATE $table SET $implodeArray WHERE ".DB::$id."=$id $moreWhereConditions ");
		mysqli_query(DB::$con1, $sql);
		if ($debug) echo $sql;
		return mysqli_affected_rows(DB::$con1);
	}

	public static function update2($table, $array, $moreWhereConditions='', $debug=false) {
		if (count($array) > 0) {
			foreach ($array as $key => $value) {
				$value = mysqli_real_escape_string(DB::$con1, $value);
				$value = "$value";
				$updates[] = "`$key` = '$value'";
			}
		}
		$implodeArray = implode(', ', $updates);
		$sql = ("UPDATE $table SET $implodeArray $moreWhereConditions ");
		mysqli_query(DB::$con1, $sql);
		if ($debug) echo $sql;
		return mysqli_affected_rows(DB::$con1);
	}

	public static function executeSQL($sql) {
		mysqli_query(DB::$con1, $sql);
		return $sql;
	}

	public static function getRows($table) {
		$sql="select * from $table";
		$res = mysqli_query(DB::$con1, $sql);
		while ($row = mysqli_fetch_object($res)) {
			$ArrayResult[] = $row;
		}
		return( $ArrayResult);
	}

	public static function fetchObject($sql) {
		$res = mysqli_query(DB::$con1, $sql) or die ($sql);
		$obj = mysqli_fetch_object($res);
		return $obj;
	}
	public static function fetchArrayObjects($sql) {
		$ArrayResult = array();
		$res = mysqli_query(DB::$con1, $sql) or die ($sql);
		while ($row = mysqli_fetch_object($res)) {
			$ArrayResult[] = $row;
		}
		return $ArrayResult;
	}

	public static function selectOneBySQL($sql) 	{
		$res = mysqli_query(DB::$con1, $sql);
		$obj = mysqli_fetch_object($res);
		return $obj;
	}

	public static function getBySQL($sql) 	{
		$ArrayResult = array();
		$res = mysqli_query(DB::$con1, $sql);
		while ($row = mysqli_fetch_object($res)) {
			$ArrayResult[] = $row;
		}
		return ($ArrayResult);
	}

	public static function selectOneFieldAsArray($table, $field, $where='') {
		$ArrayResult = array();
		$sql = "SELECT $field from $table $where";
		$objects = self::selectAllBySQLAssociativeArray($sql);
		foreach($objects as $object =>$value ) {
			$ArrayResult[] = $value[0];
		}

		return $ArrayResult;
	}

}