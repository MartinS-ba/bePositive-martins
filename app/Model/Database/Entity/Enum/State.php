<?php

namespace App\Model\Database\Entity\Enum;

enum State: int {
	case CANCELED = 0;
	case SUCCESS = 1;
	case WAITING = 2;

	public function getDescription(): string {
		return match($this) {
			self::CANCELED => 'Zrušeno',
			self::SUCCESS => 'Zaplaceno',
			self::WAITING => 'Očekáváná platba'
		};
	}
}
