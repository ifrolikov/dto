<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use ifrolikov\dto\Interfaces\ArrayPackerInterface;

/**
 * Class ArrayPacker
 *
 * @package ifrolikov\dto\Dto
 */
class ArrayPacker implements ArrayPackerInterface
{
	
	/**
	 * @param object $dto
	 * @return array
	 * @throws \ReflectionException
	 * @throws \Exception
	 */
	public function pack($dto): array
	{
		if ($dto instanceof \stdClass) {
			$result = json_decode(json_encode($dto), true, JSON_UNESCAPED_UNICODE);
		} else
		{
			$reflection = new \ReflectionClass($dto);
			/** @var \ReflectionMethod[] $getters */
			$getters = array_filter(
				$reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
				function (\ReflectionMethod $method) {
					return preg_match('~^get[A-Z]+~', $method->getName());
				}
			);
			
			$result = [];
			foreach ($getters as $getter)
			{
				$propertyName = lcfirst(
					preg_replace('~^get~', '', $getter->getName())
				);
				
				$getterValue = $getter->invoke($dto);
				if (is_array($getterValue))
				{
					$result[$propertyName] = [];
					foreach ($getterValue as $value)
					{
						$result[$propertyName][] = $this->packValue($value);
					}
				}
				else
				{
					$result[$propertyName] = $this->packValue($getterValue);
				}
			}
		}
		return $result;
	}
	
	/**
	 * @param mixed $value
	 * @return mixed array
	 * @throws \ReflectionException
	 */
	private function packValue($value)
	{
		return is_scalar($value) ? $value : $this->pack($value);
	}
}