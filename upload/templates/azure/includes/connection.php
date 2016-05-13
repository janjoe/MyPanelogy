<?php
	/*============ Error Reporting ===============*/
	ini_set("display_errors", 'off');
	ini_set("error_reporting",E_ALL);
	/*============================================*/	

class myclass
{
	function myclass()
  	{	
		$user = "root";
		$pass = "12345678";
		$server = "localhost";
		$dbase = "survey_office_2";
	 	  	
	   	$conn = mysql_connect($server,$user,$pass);
	   	if(!$conn)
		{
			$this->error("Connection attempt failed");
        }
        if(!mysql_select_db($dbase,$conn))
		{
			$this->error("Dbase Select failed");
        }
        $this->CONN = $conn;
        return true;
	}
	
	function close()
  	{   
		$conn = $this->CONN ;
        $close = mysql_close($conn);
        if(!$close)
		{
            $this->error("Connection close failed");
        }
        return true;
    }
	function error($text)
    {
        $no = mysql_errno();
        $msg = mysql_error();
        exit;
    }
  function sql_query($sql="")
  {    
        if(empty($sql))
		{
			return false;
		}
        if(empty($this->CONN))
		{
			return false;
		}
        $conn = $this->CONN;
        $results = mysql_query($sql,$conn) or die("Query Failed..<hr>" . mysql_error());
        if(!$results)
        {   
			$message = "Bad Query !";
            $this->error($message);
            return false;
        }

        if(!(eregi("^select",$sql) || eregi("^show",$sql)))
		{
            return true;
		}
        else
		{
            $count = 0;
            $data = array();
            while($row = mysql_fetch_array($results))
			{
                $data[$count] = $row;
                $count++;
            }
            mysql_free_result($results);
            return $data;
         }
    }
		
	function set_flash($msg)
	{
			$_SESSION['message'] = $msg;
	}
	
	function get_flash()
	{
			$msg = $_SESSION['message'];
			unset($_SESSION['message']);
			return $msg;
	}		
}
?>
