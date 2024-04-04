<?php

	require_once 'Config.php';

	class Bot
	{
		private $config;

		public function __construct()
		{
			$this->config = new Config();
		}

		public function handleRequest()
		{
			// получаем хук и декодируем его в массив
			$update = json_decode(file_get_contents('php://input'), true);

			if (isset($update['message'])) {
				$message = $update['message'];
				if (isset($message['text'])) {
					$command = $message['text'];
					switch ($command) {
						case '/help':
							$this->getHelp($message['chat']['id']);
							break;
						case '/test':
							$this->getTest($message['chat']['id']);
							break;
						case '/getCountries':
							$this->getCountries($message['chat']['id']);
							break;
						case '/getUser':
							$this->getUser($message['chat']['id']);
							break;
						default:
							$this->sendMessage($message['chat']['id'], 'неизвестная команда /help для списка доступных команд');
					}
				}
			}
		}

		private function getTest($chatId)
		{
			// проверка тест
			$this->sendMessage($chatId, 'тест успешно пройден');
		}

		private function getHelp($chatId)
		{
			// помощь - показать список команд
			$this->sendMessage($chatId, "
			Команды бота:\n
			/test - тест проверка\n
			/getCountries - Получение списка стран\n
			/getUser - Получение данных текущего пользователя
			 ");
		}

		private function getCountries($chatId)
		{
			// получение стран
			$url = "http://api.leads.su/webmaster/geo/getCountries?token={$this->config->getLeadsToken()}";

			$response = file_get_contents($url);

			if ($response === false) {
				$this->sendMessage($chatId, 'Не удалось получить данные о странах.');
				return;
			}

			$data = json_decode($response, true);

			if ($data === null || !isset($data['data'])) {
				$this->sendMessage($chatId, 'Ошибка анализа данных о странах.');
				return;
			}

			$countries = $data['data'];

			// сортировка по имени в обратном порядке
			usort($countries, function ($a, $b) {
				return strcmp($b['name'], $a['name']);
			});

			// получение первых 10 стран
			$topCountries = array_slice($countries, 0, 10);

			$message = "Топ-10 стран (в обратном алфавитном порядке):\n";
			foreach ($topCountries as $country) {
				$message .= $country['name'] . "\n";
			}

			// Отправка сообщения
			$this->sendMessage($chatId, $message);
		}


		private function getUser($chatId)
		{
			// получаем данные о пользователе
			$url = "http://api.leads.su/webmaster/account?token={$this->config->getLeadsToken()}";
			$response = file_get_contents($url);

			if ($response === false) {
				$this->sendMessage($chatId, 'Не удалось получить данные пользователя.');
				return;
			}

			$data = json_decode($response, true);

			if ($data === null || !isset($data['data'])) {
				$this->sendMessage($chatId, 'Ошибка анализа пользовательских данных');
				return;
			}

			// Получение ID - и имени пользователя из данных
			$id = $data['data']['id'];
			$currentUserId = $data['data']['current_user_id'];
			$userName = $data['data']['name'];

			$message = "ID: $id\nUser ID: $currentUserId\nUser Name: $userName";

			// Отправка сообщения
			$this->sendMessage($chatId, $message);
		}


		private function sendMessage($chatId, $text)
		{
			// базовый метод отправки
			$url = "https://api.telegram.org/bot{$this->config->getTelegramToken()}/sendMessage";
			$params = [
				'chat_id' => $chatId,
				'text' => $text
			];
			file_get_contents($url . '?' . http_build_query($params));
		}
	}
