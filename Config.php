<?php

	class Config {
		private $telegramToken = 'tg-token';
		private $leadsToken = 'lead-token';


		public function getTelegramToken() {
			return $this->telegramToken;
		}

		public function getLeadsToken() {
			return $this->leadsToken;
		}
	}
