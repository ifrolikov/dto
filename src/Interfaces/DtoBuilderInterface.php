<?php
declare(strict_types=1);

namespace ifrolikov\dto\Interfaces;

/**
 * Interface DtoBuilderInterface
 * @package ifrolikov\dto\Dto\Interfaces
 */
interface DtoBuilderInterface
{
    /**
     * @param string $dtoClass
     * @return object
     */
    public function build(string $dtoClass);

    /**
     * @param array $data
     * @return DtoBuilderInterface
     */
    public function setData(array $data): DtoBuilderInterface;
}