<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use ifrolikov\dto\Exceptions\TypeError;
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
     * @var string[]
     */
    private $scalarTypes = ['string', 'int', 'bool', 'integer', 'boolean', 'float'];
    
    /**
     * DtoBuilder constructor.
     *
     * @param DtoBuilderFactoryInterface $builderFactory
     * @param UseStatementParser $useStatementParser
     */
    public function __construct(DtoBuilderFactoryInterface $builderFactory, UseStatementParser $useStatementParser)
    {
        $this->builderFactory = $builderFactory;
        $this->useStatementParser = $useStatementParser;
    }
    
    /**
     * @param string $dtoClass
     * @return mixed Entry.
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
                
                if (isset($this->data[$property->name]) && $this->data[$property->name] && !is_array($this->data[$property->name])) {
                    $this->validateProperty(
                        $property,
                        $this->data[$property->name],
                        null,
                        'array',
                        null,
                        gettype($this->data[$property->name]),
                        false,
                        false
                    );
                }
                
                $createArgs[$property->name] = $this->buildArray(
                    $constructor, $property, $this->data[$property->name] ?? []
                );
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
     * @throws TypeError
     */
    private function buildOne(\ReflectionParameter $property, $data)
    {
        if ($class = $property->getClass()) {
            $result = $this->buildClass($property, $class->getName(), $data);
        } else {
            $result = $data;
        }
        
        if (is_null($result) && $property->isDefaultValueAvailable() && $default = $property->getDefaultValue()) {
            $result = $default;
        }
        
        $this->validateProperty(
            $property,
            $result,
            $class ? $class->getName() : null,
            $class ? null : $property->getType()->getName(),
            is_scalar($result) ? null : get_class($result),
            !is_scalar($result) ? null : gettype($result),
            false,
            false
        );
        
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
            $matches
        )
        ) {
            throw new \Exception('write param phpdoc for ' . $calledClass . ' ' . $property->getName());
        }
        
        $type = $matches[1];
        
        $realType = $this->getRealType($type, $property->getDeclaringClass());
        $isScalar = in_array($realType, $this->scalarTypes);
        
        $result = [];
        foreach ($data as $dataItem) {
            if ($isScalar) {
                $result[] = $dataItem;
            } else {
                $result[] = $this->buildClass($property, $realType, $dataItem);
            }
        }
        
        if ($isScalar) {
            foreach ($result as $resultItemKey => $resultItem) {
                $this->validateProperty(
                    $property,
                    $resultItem,
                    null,
                    $realType,
                    is_scalar($resultItem) ? null : get_class($resultItem),
                    !is_scalar($resultItem) ? null : gettype($resultItem),
                    true,
                    true
                );
            }
        }
        
        return $result;
    }
    
    /**
     * @param \ReflectionParameter $property
     * @param string $class
     * @param array|null $data
     * @return object|null
     */
    private function buildClass(\ReflectionParameter $property, string $class, ?array $data)
    {
        if ($class == \stdClass::class) {
            return json_decode(json_encode($data), false);
        } else {
            try {
                $builder = $this->builderFactory->create();
                return is_null($data) ? null : $builder
                    ->setData($data)
                    ->build($class);
            } catch (TypeError $error) {
                throw new TypeError(
                    $property->getName() . '.' . $error->getProperty(),
                    $class,
                    $error->getValueTypeOrClass(),
                    $error->getDeclareClass()
                );
            }
        }
    }
    
    /**
     * @param string $type
     * @param \ReflectionClass $reflectionClass
     * @return string
     */
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
            $result = $reflectionClass->getNamespaceName() . '\\' . $type;
            if (!class_exists($result)) {
                $result = $type;
            }
        }
        
        return $result;
    }
    
    /**
     * @param \ReflectionParameter $property
     * @param mixed $value
     * @param string|null $class
     * @param string|null $type
     * @param string|null $valueClass
     * @param string|null $valueType
     * @param bool $isMultiple
     * @param bool $isMultipleValue
     */
    private function validateProperty(
        \ReflectionParameter $property,
        $value,
        string $class = null,
        string $type = null,
        string $valueClass = null,
        string $valueType = null,
        bool $isMultiple = false,
        bool $isMultipleValue = false
    ): void
    {
        $classOrType = ($class ?: $type) . ($isMultiple ? '[]' : '');
        $valueClassOrtype = ($valueClass ?: $valueType) . ($isMultipleValue ? '[]' : '');
        
        if (!$property->allowsNull() && is_null($value)) {
            throw new TypeError($property->getName(), $classOrType, $valueClassOrtype, $property->getDeclaringClass()->getName());
        }
        
        if ($class) {
            if ($valueClass !== $class && !is_subclass_of($valueClass, $class)) {
                throw new TypeError($property->getName(), $classOrType, $valueClassOrtype, $property->getDeclaringClass()->getName());
            }
        } elseif (!is_null($value)) {
            $valueType = $this->getFullScalarType($valueType);
            $type = $this->getFullScalarType($type);
            if ($valueType !== $type) {
                throw new TypeError($property->getName(), $classOrType, $valueClassOrtype, $property->getDeclaringClass()->getName());
            }
        }
    }
    
    /**
     * @param string $type
     * @return string
     */
    private function getFullScalarType(string $type): string
    {
        $type = strtolower($type);
        switch ($type) {
            case 'int':
                return 'integer';
                break;
            case 'bool':
                return 'boolean';
                break;
            default:
                return $type;
        }
    }
}