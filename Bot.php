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
			// TODO получение стран
		}

		private function getUser($chatId)
		{
			// TODO получение пользователя
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
