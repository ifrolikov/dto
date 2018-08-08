<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use ifrolikov\dto\Interfaces\DtoBuilderFactoryInterface;
use ifrolikov\dto\Interfaces\DtoBuilderInterface;
use PhpDocReader\PhpParser\UseStatementParser;

/**
 * Class DtoBuilder
 * @package ifrolikov\dto\Dto
 */
class DtoBuilder implements DtoBuilderInterface
{
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var DtoBuilderFactoryInterface
     */
    private $builderFactory;
	/**
	 * @var UseStatementParser
	 */
	private $useStatementParser;
	
	/**
	 * DtoBuilder constructor.
	 *
	 * @param DtoBuilderFactoryInterface $builderFactory
	 * @param UseStatementParser         $useStatementParser
	 */
    public function __construct(DtoBuilderFactoryInterface $builderFactory, UseStatementParser $useStatementParser)
    {
        $this->builderFactory = $builderFactory;
		$this->useStatementParser = $useStatementParser;
	}

    /**
     * @param string $dtoClass
     * @return object
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function build(string $dtoClass)
    {
        $reflection = new \ReflectionClass($dtoClass);
        $constructor = $reflection->getConstructor();
        $properties = $constructor->getParameters();
        $createArgs = [];
        foreach ($properties as $property) {
            if ($property->isArray()) {
                $createArgs[$property->name] = $this->buildArray($constructor, $property, $this->data[$property->name] ?? []);
            } else {
                $createArgs[$property->name] = $this->buildOne($property, $this->data[$property->name] ?? null);
            }
        }
        return $reflection->newInstanceArgs($createArgs);
    }

    /**
     * @param array $data
     * @return DtoBuilderInterface
     */
    public function setData(array $data): DtoBuilderInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param \ReflectionParameter $property
     * @param null|mixed $data
     * @return null|object
     */
    private function buildOne(\ReflectionParameter $property, $data)
    {
        if ($class = $property->getClass()) {
            $result = $this->buildClass($class->getName(), $data);
        } else {
            $result = $data;
        }

        if (is_null($result) && $property->isDefaultValueAvailable() && $default = $property->getDefaultValue()) {
            $result = $default;
        }
        return $result;
    }

    /**
     * @param \ReflectionMethod $constructor
     * @param \ReflectionParameter $property
     * @param array $data
     * @return array
     * @throws \Exception
     */
    private function buildArray(\ReflectionMethod $constructor, \ReflectionParameter $property, array $data)
    {
        if (empty($data)) {
            return ($property->isDefaultValueAvailable()) && ($default = $property->getDefaultValue())
                ? $default
                : [];
        }

        $calledClass = $constructor->getDeclaringClass()->getName();

        $doc = $constructor->getDocComment();
        if (!$doc) {
            throw new \Exception('write phpdoc for ' . $calledClass);
        }
        if (!preg_match(
            '~\*[\s]+\@param[\s]+([A-Za-z0-9_\\\]+)\[\][\s]+\$' . $property->getName() . '~s',
            $doc,
            $matches)
        ) {
            throw new \Exception('write param phpdoc for ' . $calledClass . ' ' . $property->getName());
        }

        $type = $matches[1];
        
        $realType = $this->getRealType($type, $property->getDeclaringClass());
        
        $result = [];
        foreach ($data as $dataItem) {
            if (in_array($realType, ['string', 'int', 'bool'])) {
                $result[] = $dataItem;
            } else {
                $result[] = $this->buildClass($realType, $dataItem);
            }
        }
        return $result;
    }

    /**
     * @param string $class
     * @param array|null $data
     * @return object|null
     */
    private function buildClass(string $class, ?array $data)
    {
        $builder = $this->builderFactory->create();
        return is_null($data) ? null : $builder
            ->setData($data)
            ->build($class);
    }
	
	private function getRealType(string $type, \ReflectionClass $reflectionClass)
	{
		if (substr($type, 0, 1) === "\\") {
			return $type;
		}
		
		$lowerType = strtolower($type);
		$statements = $this->useStatementParser->parseUseStatements($reflectionClass);
		if (isset($statements[$lowerType])) {
			$result = $statements[$lowerType];
		} else {
			$result = $reflectionClass->getNamespaceName().'\\'.$type;
			if (!class_exists($result)) {
				$result = $type;
			}
		}
		
		return $result;
	}
}