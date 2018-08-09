<?php
declare(strict_types=1);

namespace ifrolikov\dto\Interfaces;

/**
 * Interface ArrayPackerInterface
 * @package ifrolikov\dto\Dto\Interfaces
 */
interface ArrayPackerInterface
{
	/**
	 * @param object $dto
	 * @return array
	 */
	public function pack($dto): array;
}