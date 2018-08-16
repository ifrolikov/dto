<?php
declare(strict_types=1);

namespace ifrolikov\dto\Interfaces;

/**
 * Interface DtoFakeDataGeneratorInterface
 *
 * @package ifrolikov\dto
 */
interface DtoFakeDataGeneratorInterface
{
	/**
	 * @param string $dtoClass
	 * @return string
	 */
	public function generate(string $dtoClass): string;
}