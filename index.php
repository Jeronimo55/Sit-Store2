<?
	header('Content-Type: text/html; charset=utf-8');
	//Настроим PHP: отображение ощибок - выключено
	//ini_set('display_errors', 'off');
	
	require_once("functions.php");						//подключить файл с функциями
	require_once($_SERVER["DOCUMENT_ROOT"].'/admin/classes/CCatalogSections.php');

//1. Секция данных (переменные и массивы)			=================================================================
	$file = $_GET['file'];
	
	if(!$file)										//Если нет файла (не указан, первый заход на сайт)
		$file=index;								//на экране будет показан файл с именем index
	
	if(!file_exists("templates/$file.html"))		//Если файл с указанным именем не существует
		$file="404";								//на экране будет показан файл с именем 404

	if($_POST['action'])
		$action =$_POST['action'];
	else
		$action =$_GET['action'];
	
	$arResult = Array();
	$arResult["MENU_SECTIONS"] = CCatalogSections::GetMenuSections(Array("active"=>"Y"), Array("*"));
	
	//2. Секция логики (работа с данными)
	session_start();

//3. Секция представления (вывод данных на экран)
	require_once("modules/add_user.php"); // тестовый коммент для гит
	require_once("templates/shablon.html");
?>