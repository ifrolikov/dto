<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use ifrolikov\dto\Interfaces\ArrayPackerInterface;
use ifrolikov\dto\Interfaces\DtoPackerInterface;

/**
 * Class AbstractDtoPacker
 * @package ifrolikov\dto\Dto
 */
abstract class AbstractDtoPacker implements DtoPackerInterface
{
	/**
	 * @var ArrayPackerInterface
	 */
	private $arrayPacker;
	
	public function __construct(ArrayPackerInterface $arrayPacker)
	{
		$this->arrayPacker = $arrayPacker;
	}
	
	/**
     * @param array $data
     * @return string
     */
    abstract protected function packInternal(array $data): string;

    /**
     * @param object|object[] $data
     * @return string
     * @throws \ReflectionException
     */
    public function pack($data): string
    {
    	$result = is_array($data)
			? array_map(function($dataItem) {
				return $this->arrayPacker->pack($dataItem);
			}, $data)
			: $this->arrayPacker->pack($data);
    	
        return $this->packInternal($result);
    }
}