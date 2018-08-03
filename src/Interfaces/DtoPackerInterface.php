<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Interfaces;

/**
 * Interface DtoPackerInterface
 * @package IFrol\RESTTools\Dto\Interfaces
 */
interface DtoPackerInterface
{
    /**
     * @param object $dto
     * @return string
     */
    public function pack($dto): string;
}