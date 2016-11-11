<?php
class search_engine
{
    /*function search_engine($mysql)
    {
        # set database connection
        $this->host = $mysql[0];
        $this->username = $mysql[1];
        $this->password = $mysql[2];
        $this->database = $mysql[3];
        $this->link = mysql_connect($this->host,$this->username,$this->password) or die(mysql_error());
        $this->db_selected = mysql_select_db($this->database,$this->link) or die(mysql_error());
        $this->found = array();
    }*/
	function search_engine($table,$key,$field,$keyword)
	{
		# set table
        $this->table = $table;
		# set keywords
        //$this->keyword = explode(" ", $keyword);
		$this->keyword=$keyword;
		# set primary key
        $this->key = $key;
		# set fieldnames to search
        $this->field =$field;			
	}
    /*function set_table($table)
    {
        # set table
        $this->table = $table;
    }
    function set_keyword($keyword)
    {
        # set keywords
        $this->keyword = explode(" ", $keyword);
    }
    function set_primarykey($key)
    {
        # set primary key
        $this->key = $key;
    }
    function set_fields($field)
    {
        # set fieldnames to search
        $this->field =$field;
    }*/
    function set_dump()
    {
        # var dump objects
        echo '<pre>';
        var_dump($this->found);
        echo '</pre>';
    }
    function set_total()
    {
        # total results found
        return sizeof($this->found);
    }
    function set_result()
    {
        # find occurence of inputted keywords
        $key = $this->key;
        for ($n=0; $n<sizeof($this->field); $n++)
        {
            for($i =0; $i<sizeof($this->keyword); $i++)
            {
                $pattern = trim($this->keyword[$i]);
                $sql = "SELECT * FROM ".$this->table." WHERE `".$this->field[$n]."` LIKE '%".$pattern."%'";
                $result = mysql_query($sql);
				$this->found[]=$sql;
                /*while ($row = mysql_fetch_object($result) AND !empty($pattern))
                {
                    //$this->found[] = $row->$key;
					$this->tmp.=','.$sql;
                }*/
            }
        }
        $this->found = array_unique($this->found);
        return $this->found;
    }
}

?> 