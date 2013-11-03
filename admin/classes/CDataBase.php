<?
	class CDataBase
	{
        static public function Connect()
		{
			require_once($_SERVER["DOCUMENT_ROOT"].'/connectvars.php');
			
			return mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
		}

        static public function Disonnect($desciptor)
		{
			mysqli_close($desciptor); 
		}

        static public function Query($desciptor, $query)
		{
			if(strlen($query)>0)
			{
				return mysqli_query($desciptor, $query);
			}
		}

        static public function Fetch($data)
		{
			return mysqli_fetch_array($data);
		}
	}
?>