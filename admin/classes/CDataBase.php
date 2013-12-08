<?
    /**
     * Class CDataBase
     * Класс предназначен для работы с базой данных
     */
    class CDataBase
	{
        /**
         * Метод осуществляет попытку подключения к БД сайта.
         * Параметры для подключения берутся из файл connectvars.php,
         * расположенного в корне сайта.
         *
         * В файле необходимо определить 4 константы,
         * которые используются в функции подключения:
         *
         * DB_HOST - хост базы даных,
         * DB_USER - имя бользователя базы данных,
         * DB_PASSWORD - пароль к базе данных,
         * DB_NAME - имя базы данных,
         *
         * Возвращает объект с данными о результате подключения к БД (дескриптор)
         *
         * @return mysqli
         */
        public static function Connect()
		{
			require_once($_SERVER["DOCUMENT_ROOT"].'/connectvars.php');
			
			return mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 
		}

        /**
         * Метод осуществляет отключение от БД по указанному
         * в переметре $desciptor дескриптора подключения к БД
         *
         * Возвращает true в случае удачного отключения
         * или false в случае неудачи
         *
         * @param $desciptor
         * @return bool
         */
        public static function Disonnect($desciptor)
		{
			if(mysqli_close($desciptor))
                return true;
            else
                return false;
		}

        /**
         * Метод выполняет запрос к БД. Принимает 2 параметра:
         *
         * $descriptor - дескриптор подключения к БД
         * $query - строка запроса
         *
         * Возвразет объект mysqli_query_result в случае удачного выполнения
         * или false в случае неудачи
         *
         * @param $desciptor
         * @param $query
         * @return bool|mysqli_result
         */
        public static function Query($desciptor, $query)
		{
			if(strlen($query)>0)
			{
				return mysqli_query($desciptor, $query);
			}
            else
                return false;
		}

        /**
         * Извлекает из оперативной памяти асоциативный массив полей
         * строки результата выполнения запроса к БД
         *
         * Принимает параметром объект mysqli_query_result,
         * который есть результатом выполнения метода CDataBase::Query
         *
         * Возвращает асоциативный массив полей строки из БД
         *
         * @param $data
         * @return array|null
         */
        public static function Fetch($data)
		{
			return mysqli_fetch_array($data);
		}
	}
?>