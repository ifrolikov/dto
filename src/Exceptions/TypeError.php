<?php
declare(strict_types=1);

namespace ifrolikov\dto\Exceptions;

class TypeError extends \TypeError
{
    /**
     * @var string
     */
    private $property;
    /**
     * @var mixed
     */
    private $valueTypeOrClass;
    /**
     * @var string
     */
    private $expectedType;
    /**
     * @var string
     */
    private $declareClass;
    
    public function __construct(
        string $property,
        string $expectedTypeOrClass,
        string $valueTypeOrClass,
        string $declareClass
    )
    {
        $this->property = $property;
        $this->valueTypeOrClass = $valueTypeOrClass;
        $this->expectedType = $expectedTypeOrClass;
        $this->declareClass = $declareClass;
        
        $message = 'Argument ' . $property . ' expect ' . $this->expectedType .
            ' value in ' . $declareClass . ' but given ' . $valueTypeOrClass;
        parent::__construct($message, 500, null);
    }
    
    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }
    
    /**
     * @return mixed
     */
    public function getValueTypeOrClass()
    {
        return $this->valueTypeOrClass;
    }
    
    /**
     * @return string
     */
    public function getExpectedType(): string
    {
        return $this->expectedType;
    }
    
    /**
     * @return string
     */
    public function getDeclareClass(): string
    {
        return $this->declareClass;
    }
}
