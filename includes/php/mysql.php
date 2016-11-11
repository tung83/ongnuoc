<?php

// Cap nhat 29/7/2006
class CDB_MySql
{
	var $dbName             = "";
	var $con         		= 0;
	var $dbcon            = 0;

	var $result           = 0;
	var $row             = array();
	var $maximum_time = 0;
	var $timestamp;

	function CDB_Mysql($dbName=''){
		 $this->dbName = $dbName;
	}
	function connect($dbHotName,$dbUserName,$dbPassword,$dbName=''){
		/*$this->con=mysqli_connect($dbHotName,$dbUserName,$dbPassword,$dbName) or die("<br>Not connect host  :".mysql_error());*/	
		 $this->con = mysql_connect($dbHotName,$dbUserName,$dbPassword)  or  die("<br>Not connect host  :".mysql_error());
  		 if($this->dbName !='')
		 {
		 	$this->dbName=$dbName;
		 	$this->dbcon = mysql_select_db($this->dbName,$this->con)  or  die("<br>Not select  Database :". $this->dbName);
		 }	
		 return $this->con;
	}
	function selectdb($database=''){		
		 if($database!='')$this->dbName=$database;
		 $this->dbcon = mysql_select_db($this->dbName,$this->con)  or  die("<br>Not select  Database :". $this->dbName);
		 return $this->dbcon;
	}
	function query($query_string){		
		 $this->sql = $query_string			;
		 $this->result = mysql_query($query_string,$this->con);// or die("Khong thuc hien duoc query :". $query_string);
		 return $this->result;
	}
	function fetch_object(){
			 $row = mysql_fetch_object($this->result);
			 return $row;
	}
	function num_rows(){
			 return ((int)mysql_num_rows($this->result));
	}
	function LayBanGhiDau($sql){			
		$this->sql = $sql;
		$result = mysql_query($this->sql,$this->con);						
		$row = mysql_fetch_array($result);
		mysql_free_result($result);
		return $row ;		
	}
	function errno(){		
		if($this->con)	return mysql_errno($this->con);
		return -1; // connec khong ton tai
	}
	function MaLoi(){		
		if($this->con)	return mysql_errno($this->con);
		return -1; // connec khong ton tai
	}
	
	function ChiTietLoi(){		
		if($this->con)	return mysql_error($this->con);
		return 'No connection'; // connec khong ton tai
	}
	function batLoi($echo = false){		
		if( ! mysql_errno()) return false;
		$str = ' <br>Con : '.$this->con.' <br>this->sql : '.$this->sql. '<br> Loi : '.mysql_errno().'<br> Chi tiet :'.mysql_error();
		if(!$echo)return $str;
		echo $str;
	}
	function fetch_tables_name(){
		if ($this->dbName!=""){
			$tables = mysql_list_tables($this->dbName);
			$count = 0;
			while (list($table_name) = mysql_fetch_array($tables)) 
                	{
                   		$this->row[$count] = $table_name;
                   		$count++;
                	}
		}
		return $this->row;
	}

	
	function backup_tables($table_name="",$start=0){
		$dump = '';
		$this->timestamp=time();
		if ($table_name!=""){
		    if (!$start){
			$field_row=array();
			$tmp = mysql_query('SHOW FIELDS FROM '.$table_name);
			while ($field = mysql_fetch_assoc($tmp)){
				if (!$field['Null']){
					$null = ' NOT NULL ';
				} else {$null='';}
				if ($field['Default']){
					$null .= ' DEFAULT "'.$field['Default'].'"';
				}
				if ($field['Extra']){
					$field['Extra']=' '.$field['Extra'];
				}

				$field_row[] = '  ' . $field['Field'] . ' ' . $field['Type'] . $null . $field['Extra'];
			}
			$tmp = mysql_query('SHOW KEYS FROM '.$table_name);

			while ($key = mysql_fetch_assoc($tmp)) 
            		{
		                if ($key['Key_name'] == 'PRIMARY') 
                		{
		                    $primary_key = $key['Column_name'];
                		}
		                else 
                		{
		                    $unique[ $key['Key_name'] ][] = $key['Column_name'];
                		}
            		}
			if (isset($primary_key)) 
		        {
                		$field_row[] = '  PRIMARY KEY (' . $primary_key . ')';
	                }

	                if (isset($unique))
            		{
		                foreach ($unique as $name => $keys) 
                  		{
		                    $field_row[] = '  UNIQUE ' . $name . ' (' . implode(',',$keys) . ')';
                		}
            		}

	  	        $dump .= "\n\n--\n";
	                $dump .= "-- Table structure for table '$table_name'\n";
	                $dump .= "--\n\n";
        	        $dump .= "CREATE OR REPLACE TABLE $table_name (\n";
           	        $dump .= implode(",\n",$field_row);
	                $dump .= "\n);\n\n";
	                $dump .= "\n\n--\n";
		    }
			$done = 0;

			$tmp = mysql_query('SELECT * FROM '.$table_name.' LIMIT '.$start.',-1');

        		while ($row = mysql_fetch_row($tmp)) 
        		{
            			if ($this->timeout()) 
            			{
             			return array($dump,$done);
            			}

            			$done++;

            			foreach ($row as $id => $value) 
            			{
                			$value = str_replace('"','\\"',$value);
                			$row[$id] = '"'.$value.'"';
				}

        			$dump .= 'INSERT INTO ' . $table_name . ' VALUES (' . implode(',',$row) . ");\n";
        		}
		}
		return $dump;
	}

	function check_exist($fromtable,$checkname,$checkvalue){
			 $sql_query = "SELECT user_id FROM $fromtable WHERE $checkname='". $checkvalue ."'";
			 $this->query($sql_query);
			 if ( $this->num_rows()>0 ) return 1;

			 return 0;
	}

	function close(){
			 return mysql_close($this->con);
	}

	function timeout(){
		if (!$this->maximum_time)
        	{
        		return false;
	        }
        	elseif ((time() - $this->timestamp) > ($this->maximum_time - 5)) 
	        {
        		return true;
	        }
        	else 
	        {
        		return false;
	        }
	}
}

?>