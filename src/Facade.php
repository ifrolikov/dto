<?php
declare(strict_types=1);

namespace ifrolikov\dto;

class Facade
{
	public function getDependenciesConfig() {
		return require __DIR__.'/config/di.php';
	}
}