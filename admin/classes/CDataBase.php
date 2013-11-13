<?
	class CDataBase
	{
        public static function Connect()
		{
			require_once($_SERVER["DOCUMENT_ROOT"].'/connectvars.php');
			
			return mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
		}

        public static function Disonnect($desciptor)
		{
			if(mysqli_close($desciptor))
                return true;
            else
                return false;
		}

        public static function Query($desciptor, $query)
		{
			if(strlen($query)>0)
			{
				return mysqli_query($desciptor, $query);
			}
		}

        public static function Fetch($data)
		{
			return mysqli_fetch_array($data);
		}
	}
?>