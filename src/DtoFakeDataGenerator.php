<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use ifrolikov\dto\Interfaces\DtoFakeDataGeneratorInterface;

/**
 * Class DtoFakeDataGenerator
 *
 * @package ifrolikov\dto
 */
class DtoFakeDataGenerator implements DtoFakeDataGeneratorInterface
{
    /**
     * @var string
     */
    private $fakerTemplate;
    /**
     * @var PhpDocManager
     */
    private $phpDocManager;
    
    /**
     * DtoFakeDataGenerator constructor.
     *
     * @param  PhpDocManager $phpDocManager
     * @param string $fakerTemplate
     */
    public function __construct(PhpDocManager $phpDocManager, $fakerTemplate = '$faker->')
    {
        $this->fakerTemplate = $fakerTemplate;
        $this->phpDocManager = $phpDocManager;
    }
    
    /**
     * @param string $dtoClass
     * @return string
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function generate(string $dtoClass, bool $beautify = true): string
    {
        $reflectionClass = new \ReflectionClass($dtoClass);
        $constructor = $reflectionClass->getConstructor();
        
        $attributes = [];
        
        foreach ($constructor->getParameters() as $parameter) {
            if ($parameter->isArray()) {
                $attributes[$parameter->getName()] = $this->generateByPhpDoc($parameter);
            } elseif ($class = $parameter->getClass()) {
                $attributes[$parameter->getName()] = $this->generateByClass($class);
            } elseif ($type = $parameter->getType()) {
                $attributes[$parameter->getName()] = $this->generateByType($parameter, $type->getName());
            }
        }
        
        $result = [];
        foreach ($attributes as $attribute => $value) {
            $result[] = "'$attribute' => $value";
        }
        
        $result = "[ " . implode(', ', $result) . " ]";
        if ($beautify) {
            $result = $this->beautify($result);
        }
        
        return $result;
    }
    
    /**
     * @param \ReflectionClass $class
     * @return string
     * @throws \ReflectionException
     */
    private function generateByClass(\ReflectionClass $class): string
    {
        return $this->generate($class->getName(), false);
    }
    
    /**
     * @param \ReflectionParameter $parameter
     * @param string $type
     * @return string
     * @throws \Exception
     */
    private function generateByType(\ReflectionParameter $parameter, string $type): string
    {
        switch ($type) {
            case 'integer':
            case 'int':
                return $this->fakerTemplate . 'randomNumber()';
                break;
            case 'float':
            case 'double':
                return $this->fakerTemplate . 'randomFloat()';
            case 'string':
                return $this->fakerTemplate . 'word';
            case 'bool':
            case 'boolean':
                return $this->fakerTemplate . 'boolean';
                break;
            default:
                throw new \Exception(
                    'Unexpeced scalar type of property ' . $parameter->getName() .
                    ' ' . $type . ' in ' . $parameter->getDeclaringClass()->getName()
                );
        }
    }
    
    /**
     * @param \ReflectionParameter $parameter
     * @return string
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function generateByPhpDoc(\ReflectionParameter $parameter): string
    {
        $itemType = $this->phpDocManager->getArrayItemType($parameter);
        $isScalar = $this->phpDocManager->isScalar($itemType);
        
        $result = $isScalar ? $this->generateByType($parameter, $itemType) : $this->generate($itemType, false);
        
        return "[ {$result} ]";
    }
    
    /**
     * @param string $code
     * @return string
     */
    private function beautify(string $code): string
    {
        $code = str_replace(PHP_EOL, ' ', $code);
        $code = str_replace("[", "[" . PHP_EOL, $code);
        $code = str_replace(",", "," . PHP_EOL, $code);
        $code = str_replace("]", PHP_EOL . "]", $code);
        $rows = explode(PHP_EOL, $code);
        $ident = "";
        $resultRows = [];
        foreach ($rows as $row) {
            $row = trim($row);
            
            if ($row == "]" || $row == "],") {
                $ident = substr($ident, 4);
            }
            
            $resultRows[] = $ident . $row . PHP_EOL;
            
            if ($row == "[" || preg_match("~\[[\s]*$~si", $row)) {
                $ident .= "    ";
            }
        }
        
        return implode("", $resultRows);
    }
}
