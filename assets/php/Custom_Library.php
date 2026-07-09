<?php
	
	$response = "";
	
	$mode = $_REQUEST["mode"];
	$param = $_REQUEST["param"];
	$param_value = $_REQUEST["param_value"];
	
	//replace symbol yang tidak bisa passing
	$param_value = str_replace("(symbol:plus)","+",$param_value);
	
	$array_param = explode("|",$param);
	$array_param_value = explode("|",$param_value);

	
	for($i = 0; $i < count($array_param); $i++) {
		${$array_param[$i]} = $array_param_value[$i];
	}
	
	function get_new_id($prefix, $table, $column_name) {
		require("connection/dbconnect1.php");
		
		$query = "SELECT max($column_name) AS max_id FROM $table WHERE $column_name LIKE '$prefix-".date("Y")."-%' ";
		$result = mysqli_query($con,$query);
		while($row = mysqli_fetch_array($result)) {
			$max_id = $row["max_id"];
			if($max_id == '') {
				$new_id = $prefix . "-" . date("Y") ."-00001";
			}
			else {
				$array_max_id = explode('-', $max_id);
				$new_id = $array_max_id[2] + 1;
				$awalan_nol = "";
				
				for ($i=0; $i < 5 - strlen($new_id); $i++) {
					$awalan_nol = $awalan_nol . "0";
				}
				$new_id = $prefix . "-" . date("Y") . "-" .$awalan_nol . $new_id;
			}
		}
		return $new_id;
	}
	
	function get_new_id_stocks($prefix, $table, $column_name) { //rully
		require("connection/dbconnect1.php");
		
		$nowdate = date("Y-m-d");
		$query = "SELECT max($column_name) AS max_id FROM $table WHERE $column_name LIKE '$prefix-".$nowdate."-%' ";
		$result = mysqli_query($con,$query);
		while($row = mysqli_fetch_array($result)) {
			$max_id = $row["max_id"];
			
			if($max_id == '') {
				$new_id = $prefix . "-" . $nowdate ."-00001";
			}
			else {
				$array_max_id = explode('-', $max_id);
				$new_id = $array_max_id[2] + 1;
				$awalan_nol = "";
				
				for ($i=0; $i < 5 - strlen($new_id); $i++) {
					$awalan_nol = $awalan_nol . "0";
				}
				$new_id = $prefix . "-" . $nowdate . "-" .$awalan_nol . $new_id;
			}
		}
		return $new_id;
	}
	
	function get_response($mode, $affected_rows) {
		if ($affected_rows < 1) {
			$reply = $mode . "_failed";
		}
		else {
			$reply = $mode . "_success";
		}
		return $reply;
	}
	
	function GQ($searched_column, $table, $condition) { //Get FROM Query
		require("connection/dbconnect1.php");
		
		$query = "SELECT $searched_column AS result FROM $table WHERE $condition ";
		$result = mysqli_query($con,$query);
		while($row = mysqli_fetch_array($result)) {
			return $row['result'];
		}
	}
?>