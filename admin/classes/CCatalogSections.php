<?
    require_once($_SERVER["DOCUMENT_ROOT"].'/admin/classes/CDataBase.php');
    class CCatalogSections
    {
        static public function GetMenuSections($arFilter, $SelectFields)
        {
            $query = "";
            if(is_array($SelectFields) && count($SelectFields) == 1 && $SelectFields[0] == "*")
            {
                $query .= "SELECT ";
                $query .= "* ";
            }
            elseif(is_array($SelectFields) && count($SelectFields) > 0)
            {
                $query .= "SELECT ";

                foreach($SelectFields as $key => $select)
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

            $query .= "FROM `categories` ";

            if(is_array($arFilter) && count($arFilter) > 0)
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

            if(mysqli_num_rows($data) > 0)
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

        static public function GetByID($id)
        {
            $query = "";
            $query .= "SELECT * FROM `categories` WHERE `id` = ".intval($id);

            $db = CDataBase::Connect();
            $data = CDataBase::Query($db, $query);
            CDataBase::Disonnect($db);

            if($data)
            {
                while($row = CDataBase::Fetch($data))
                    $result = $row;
                return $result;
            }
            else
                return false;
        }
    }

?>