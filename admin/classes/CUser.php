<?
	class CUser
	{
		public function GenerateRegistrationForm()
		{
			$HobbyOptions = Array(
				"computers"=>"компьютеры",
				"sports"=>"спорт",
				"games"=>"игры",
				"animals"=>"животные",
				"cars"=>"автомобили",
				"clubs"=>"клубы",
				"music"=>"музыка",
			);

			$form = "";
			$form .= '<table border="0" cellspacing="5" cellpadding="5" style="float:left;overflow: hidden;margin-right: 35px;">';
			$form .= '<form name="forma1" action="index.php" method="POST">'; 
			$form .= '<caption>Форма регистрации</caption>';
			$form .= '<tbody><tr><td align="right" valign="top">Имя</td>';
			$form .= '<td><input type="text" name="name" size="25" value="'.$_REQUEST["name"].'"></td></tr>';
			$form .= '<tr><td align="right" valign="top">e-mail</td>';
			$form .= '<td><input type="text" name="e-mail" size="25" value="'.$_REQUEST["e-mail"].'"></td></tr>';
			$form .= '<tr><td align="right" valign="top">Пароль</td>';
			$form .= '<td><input type="password" name="password" size="25" value="'.$_REQUEST["password"].'"></td></tr>';
			$form .= '<tr><td align="right" valign="top">Повтор пароля</td>';
			$form .= '<td><input type="password" name="password2" size="25" value="'.$_REQUEST["password2"].'"></td></tr>';
			$form .= '<tr><td align="right" valign="top">Пол</td>';
			$form .= '<td>
						<input type="radio" name="sex" value="man" '.($_REQUEST["sex"]=='man' ? 'checked="checked"' : '').'>мужской
						<input type="radio" name="sex" value="woman" '.($_REQUEST["sex"]=='woman' ? 'checked="checked"' : '').'> женский
					</td>
					</tr>';
			$form .= '<tr><td align="right" valign="top">Увлечения</td>';
			$form .= '<td><select name="hobby" size="7">';
						
			foreach($HobbyOptions as $val=>$option)
			{
				$form .= '<option'.($_REQUEST["hobby"] == $val ? ' selected="selected"' : '').' value="'.$val.'">'.$option.'</option>';
			}
			
			$form .= '</select></td></tr>';
			$form .= '<tr><td align="right" valign="top">Ваши пожелания</td>';
			$form .= '<td><textarea name="comments" cols="30" rows="3" wrap="physical">'.$_REQUEST["comments"].'</textarea></td></tr>';
			$form .= '<tr><td align="right" colspan="2"><input type="submit" name="submit" value="Отправить"><input type="reset" name="reset" value="Очистить"></td></tr>';
			$form .= '<input type="hidden" name="action" value="register">';
			$form .= '</tbody>';
			$form .= '</form>';
			$form .= '</table>';
			
			//GET и POST запросы
			if(isset($_REQUEST["action"]))
			{
				$form .= '<table border="0" cellspacing="5" cellpadding="5">';
				$form .= '<tbody>';
				$form .= '<tr>';
				$form .= '<td>';
				$form .= '<strong>GET запрос с текущим набором данных из формы</strong>';
				$form .= '</td>';
				$form .= '</tr>';
				$form .= '<td>';
				$form .= self::GenerateGetRequest();
				$form .= '</td>';
				$form .= '</tr>';
				$form .= '<tr>';
				$form .= '<td>';
				$form .= '<strong>POST запрос с текущим набором данных из формы</strong>';
				$form .= '</td>';
				$form .= '</tr>';
				$form .= '<tr>';
				$form .= '<tr>';
				$form .= '<td>';
				$form .= self::GeneratePostRequest();
				$form .= '</td>';
				$form .= '</tr>';
				$form .= '</tbody>';
				$form .= '</table>';
			}
			
			return $form;
		}
		
		private function GenerateGetRequest()
		{
			$result = "";
			
			/*
				Нельзя собрать 2 запроса GET и POST на одной странице на основе данных из
				массива $_SERVER, так как в нем появляются различные элементы в зависимости
				от метода запроса. По этому	приходится некоторые части запроса собирать 
				вручную, хотя если стояла бы задача генерировать текст какого-то одного 
				запроса, эта необходимость отпадает - все приходит в $_SERVER в готовом виде
				
				PHP_EOL - предопределенная константа, корссплатформенно переносит строку
			*/
			
			// Собираем Referer запроса
			$query = '/?';
			foreach($_REQUEST as $key=>$val)
				if(strlen($val)>0)
					$query .= $key.'='.urlencode($val).'&';
					
			$query = substr($query, 0, -1); // отсекаем последний &
			
			//Для примера пишу GET статично, хотя в $_SERVER есть элемент "REQUEST_METHOD"
			$result .= 'GET http://'.$_SERVER["HTTP_HOST"].$query.' '.$_SERVER["SERVER_PROTOCOL"].PHP_EOL;
			$result .= 'Host: '.$_SERVER["HTTP_HOST"].PHP_EOL;
			$result .= 'Connection: '.$_SERVER["HTTP_CONNECTION"].PHP_EOL;
			$result .= 'Accept: '.$_SERVER["HTTP_ACCEPT"].PHP_EOL;
			$result .= 'User-Agent: '.$_SERVER["HTTP_USER_AGENT"].PHP_EOL;
			$result .= 'Referer: http://'.$_SERVER["HTTP_HOST"].$query.PHP_EOL;
			$result .= 'Accept-Encoding: '.$_SERVER["HTTP_ACCEPT_ENCODING"].PHP_EOL;
			$result .= 'Accept-Language: '.$_SERVER["HTTP_ACCEPT_LANGUAGE"].PHP_EOL;
			$result .= 'Cookie: '.$_SERVER["HTTP_COOKIE"].PHP_EOL;
			
			return '<textarea cols="85" rows="10" wrap="off" readonly>'.$result.'</textarea>';
		}
		
		private function GeneratePostRequest()
		{
			$result = "";
			
			// Собираем Referer запроса
			$query = '/?';
			foreach($_REQUEST as $key=>$val)
				if(strlen($val)>0)
					$query .= $key.'='.urlencode($val).'&';
			
			$query = substr($query, 0, -1); // отсекаем последний &
			
			//Для примера пишу POST статично, хотя в $_SERVER есть элемент "REQUEST_METHOD"
			$result .= 'POST '.$_SERVER["HTTP_ORIGIN"].$_SERVER["REQUEST_URI"].' '.$_SERVER["SERVER_PROTOCOL"].PHP_EOL;
			$result .= 'Host: '.$_SERVER["HTTP_HOST"].PHP_EOL;
			$result .= 'Connection: '.$_SERVER["HTTP_CONNECTION"].PHP_EOL;
			$result .= 'Content-Length: '.strlen(substr($query, 2)).PHP_EOL;
			$result .= 'Accept: '.$_SERVER["HTTP_ACCEPT"].PHP_EOL;
			$result .= 'Origin: '.$_SERVER["HTTP_ORIGIN"].PHP_EOL;
			$result .= 'User-Agent: '.$_SERVER["HTTP_USER_AGENT"].PHP_EOL;
			$result .= 'Content-Type: '.$_SERVER["CONTENT_TYPE"].PHP_EOL;
			$result .= 'Referer: http://'.$_SERVER["HTTP_HOST"].$query.PHP_EOL;
			$result .= 'Accept-Encoding: '.$_SERVER["HTTP_ACCEPT_ENCODING"].PHP_EOL;
			$result .= 'Accept-Language: '.$_SERVER["HTTP_ACCEPT_LANGUAGE"].PHP_EOL;
			$result .= 'Cookie: '.$_SERVER["HTTP_COOKIE"].PHP_EOL;
			$result .= PHP_EOL;
			$result .= substr($query, 2);// отсекаем '/?'

			return '<textarea cols="85" rows="10" wrap="off" readonly>'.$result.'</textarea>';
		}
	}
?>