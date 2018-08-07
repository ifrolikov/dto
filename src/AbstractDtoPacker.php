<?php
declare(strict_types=1);

namespace IFrol\RESTTools;

use IFrol\RESTTools\Interfaces\DtoPackerInterface;

/**
 * Class AbstractDtoPacker
 * @package IFrol\RESTTools\Dto
 */
abstract class AbstractDtoPacker implements DtoPackerInterface
{
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
				return $this->packToArray($dataItem);
			}, $data)
			: $this->packToArray($data);
    	
        return $this->packInternal($result);
    }

    /**
     * @param mixed $value
     * @return mixed array
     * @throws \ReflectionException
     */
    private function packValue($value)
    {
        return is_scalar($value) ? $value : $this->packToArray($value);
    }

    /**
     * @param object $dto
     * @return array
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function packToArray($dto): array
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
        foreach ($getters as $getter) {
            $propertyName = lcfirst(
                preg_replace('~^get~', '', $getter->getName())
            );

            $getterValue = $getter->invoke($dto);
            if (is_array($getterValue)) {
                foreach ($getterValue as $value) {
                    $result[$propertyName][] = $this->packValue($value);
                }
            } else {
                $result[$propertyName] = $this->packValue($getterValue);
            }
        }
        return $result;
    }
}