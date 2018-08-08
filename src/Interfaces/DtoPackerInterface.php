<?php
declare(strict_types=1);

namespace ifrolikov\dto\Interfaces;

/**
 * Interface DtoPackerInterface
 * @package ifrolikov\dto\Dto\Interfaces
 */
interface DtoPackerInterface
{
    /**
     * @param object $dto
     * @return string
     */
    public function pack($dto): string;
}