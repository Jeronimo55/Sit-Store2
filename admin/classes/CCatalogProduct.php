<?
	require_once($_SERVER["DOCUMENT_ROOT"].'/admin/classes/CDataBase.php');

    /**
     * Class CCatalogProduct
     * Класс предназначен для работы с товарами магазина
     */
    class CCatalogProduct
	{
        /**
         * Метод обрашается к БД и возвращает список ТОПа товаров в магазине.
         * Выборка ограничивается параметром метода $limit, количество возвращемых
         * элементов равна значение данного параметра
         *
         * ТОП товаров опледеляется в поле БД `catalog_top` таблицы `products`
         *
         * Возвращает массив элеменов со всеми полями ТОП товара в случае удачной
         * выборки или false в случае неудачи
         *
         * @param int $limit
         * @return array|bool
         */
        public static function GetCatalogTop($limit=12)
		{
			$query = "";
			$query .= "SELECT * FROM `products` WHERE `catalog_top` = 'Y' ORDER BY RAND() LIMIT ".intval($limit);
			
			$db = CDataBase::Connect();
			$data = CDataBase::Query($db, $query);
			CDataBase::Disonnect($db);
			
			if(mysqli_num_rows($data)>0)
			{
				$arResult = Array();
				
				while($row = CDataBase::Fetch($data))
				{
					$arResult[] = $row;
				}
				return $arResult;
			}
			else
				return false;
		}

        /**
         * Метод осуществляет выборку товаров из таблицы `products` согласно фильтру
         * $arFields. Список возвращаемых полей задается во втором параметре
         * метода - массиве $SelectFields
         *
         * Доступные параметры для фильтрации:
         *
         * "id" - ID товара в БД
         * "name" - Название товара
         * "description" - Описание товара (в формате HTML)
         * "quantity" - Количество на складе
         * "image" - Путь к картинке товара относительно корня сайта
         * "price" - Цена товара
         * "category_id" - ID категории товара. Первичный ключ таблицы `categories`
         * "catalog_top" - Флаг ТОПа товара на сайте (значение Y или N)
         * "active" - Флаг активности товара на сайте (значение Y или N)
         *
         * Возвращает массив отфильтрованных элементов в случае удачной выборки
         * или false в случае неудачи
         *
         * @param $arFilter
         * @param $SelectFields
         * @return array|bool
         */
        public static function GetList($arFilter, $SelectFields)
		{
			$query = "";
			if(is_array($SelectFields) && count($SelectFields)==1 && $SelectFields[0] == "*")
			{
				$query .= "SELECT ";
				$query .= "* ";
			}
			elseif(is_array($SelectFields) && count($SelectFields)>0)
			{
				$query .= "SELECT ";
				
				foreach($SelectFields as $select)
				{
					$query .= "`".$select."`";
					if($select !== end($SelectFields))
						$query .= ", ";
				}
			}
			else
			{
				return false;
			}
			
			$query .= "FROM `products` ";
			
			if(is_array($arFilter) && count($arFilter)>0)
			{
				$query .= "WHERE ";
				
				foreach($arFilter as $key => $val)
				{
					$query .= "`".$key."` = '".$val."'";
					if($val !== end($arFilter))
						$query .= " and ";
				}
			}
			
			$db = CDataBase::Connect();
			$data = CDataBase::Query($db, $query);
			CDataBase::Disonnect($db);
			
			if($data)
			{
				$arResult = Array();
				
				while($row = CDataBase::Fetch($data))
				{
					$arResult[] = $row;
				}
				return $arResult;
			}
			else
				return false;
		}

        /**
         * Метод осуществляет поиск товара по его ID в БД.
         *
         * Возвращает массив всех полей найденого товара.
         *
         * @param $id
         * @return array|bool
         */
        public static function GetByID($id)
		{
            $arResult = self::GetList(Array("id"=>$id), Array("*"));
            return $arResult;
		}

        /**
         * Метод осуществляет обновление товара в БД. Значения полей для
         * обновления задаются во втором параметре метода - массиве $arFields
         *
         * Доступные параметры для обновления:
         *
         * "name" - Название товара
         * "description" - Описание товара (в формате HTML)
         * "quantity" - Количество на складе
         * "image" - Путь к картинке товара относительно корня сайта
         * "price" - Цена товара
         * "category_id" - ID категории товара. Первичный ключ таблицы `categories`
         * "catalog_top" - Флаг ТОПа товара на сайте (значение Y или N)
         * "active" - Флаг активности товара на сайте (значение Y или N)
         *
         * Возвращает true в случае удачного обновления
         * или false в случае неудачи
         *
         * Пример использования:
         *
         *     $arFields = Array(
         *          "name" => "Блинница Ariete Baby 181",
         *          "quantity" => "10",
         *          "price" => "60.00",
         *          "category_id" => "1",
         *          "catalog_top" => "Y",
         *      );
         *
         *     CCatalogProduct::Update(1, $arFields);
         *
         *
         * @param       $id
         * @param array $arFields
         * @return bool
         */
        public static function Update($id, array $arFields)
        {
            $bResult = false;
            if(count($arFields)>0)
            {
                $query = "";
                $query .= "UPDATE `sit-store`.`products` SET ";
                foreach($arFields as $fieldCode=>$val)
                {
                    if($arFields[$fieldCode] == end($arFields))
                        $query .= "`$fieldCode` =  '$val' ";
                    else
                        $query .= "`$fieldCode` =  '$val', ";
                }
                $query .= "WHERE `products`.`id` = $id;";

                $db = CDataBase::Connect();
                $data = CDataBase::Query($db, $query);
                CDataBase::Disonnect($db);

                if($data)
                    $bResult = true;
            }

            return $bResult;
        }

        /**
         * Метод осуществляет добавление товара в БД. Значения полей для
         * добавления задаются в параметре метода - массиве $arFields
         *
         * Параметры для добавления товара:
         *
         * "name" - Название товара
         * "description" - Описание товара (в формате HTML)
         * "quantity" - Количество на складе
         * "image" - Путь к картинке товара относительно корня сайта
         * "price" - Цена товара
         * "category_id" - ID категории товара. Первичный ключ таблицы `categories`
         * "catalog_top" - Флаг ТОПа товара на сайте (значение Y или N)
         * "active" - Флаг активности товара на сайте (значение Y или N)
         *
         * Возвращает true в случае удачного добавления
         * или false в случае неудачи
         *
         * Пример использования:
         *
         *     $arFields = Array(
         *          "name" => "Блинница Ariete Baby 181",
         *          "description" => "<p>Мощность 901 Вт.</p>",
         *          "quantity" => "10",
         *          "image" => "data/92-162-thickbox.jpg",
         *          "price" => "60.00",
         *          "category_id" => "1",
         *          "catalog_top" => "Y",
         *          "active" => "Y",
         *      );
         *
         *     CCatalogProduct::Add($arFields);
         *
         * @param array $arFields
         * @return bool
         */
        public static function Add(array $arFields)
        {
            $bResult = false;

            if(count($arFields)>0)
            {
                $query = "";
                $query .= "INSERT INTO `sit-store`.`products` (`id`, ";
                foreach($arFields as $fieldCode=>$val)
                {
                    if($fieldCode == "id") continue;
                    if($arFields[$fieldCode] == end($arFields))
                        $query .= "`$fieldCode` ";
                    else
                        $query .= "`$fieldCode`, ";
                }

                $query .= ") VALUES (NULL, ";

                foreach($arFields as $fieldCode=>$val)
                {
                    if($arFields[$fieldCode] == end($arFields))
                        $query .= "`$val` ";
                    else
                        $query .= "`$val`, ";
                }

                $db = CDataBase::Connect();
                $data = CDataBase::Query($db, $query);
                CDataBase::Disonnect($db);

                if($data)
                    $bResult = true;
            }

            return $bResult;
        }

        /**
         * Метод осуществляет удаление товара из БД. Единственный параметр метода -
         * $id - ID товара в БД
         *
         * Возвращает true в случае удачного добавления
         * или false в случае неудачи
         *
         * Пример использования:
         *     $ID = 10;
         *     CCatalogProduct::Delete($ID);
         *
         * @param $id
         * @return bool
         */
        public static function Delete($id)
        {
            $bResult = false;
            if(intval($id)>0)
            {
                $query = "";
                $query .= "DELETE FROM `sit-store`.`products` WHERE `products`.`id` = $id;";

                $db = CDataBase::Connect();
                $data = CDataBase::Query($db, $query);
                CDataBase::Disonnect($db);

                if($data)
                    $bResult = true;
            }

            return $bResult;
        }
	}
?>