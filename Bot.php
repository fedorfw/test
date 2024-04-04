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
			// TODO проверка тест
		}

		private function getHelp($chatId)
		{
			// TODO помощь
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
			// TODO базовый метод отправки
		}
	}
