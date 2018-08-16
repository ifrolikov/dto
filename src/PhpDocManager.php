<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use PhpDocReader\PhpParser\UseStatementParser;

class PhpDocManager
{
	/**
	 * @var UseStatementParser
	 */
	private $useStatementParser;
	/**
	 * @var string[]
	 */
	private $scalarTypes = ['string', 'int', 'bool', 'integer', 'boolean', 'float', 'double'];
	
	/**
	 * PhpDocManager constructor.
	 *
	 * @param UseStatementParser $useStatementParser
	 */
	public function __construct(UseStatementParser $useStatementParser)
	{
		$this->useStatementParser = $useStatementParser;
	}
	
	/**
	 * @param \ReflectionParameter $parameter
	 * @return string
	 * @throws \Exception
	 */
	public function getArrayItemType(\ReflectionParameter $parameter): string
	{
		$calledClass = $parameter->getDeclaringClass()->getName();
		
		$doc = $parameter->getDeclaringFunction()->getDocComment();
		if (!$doc) {
			throw new \Exception('write phpdoc for ' . $calledClass);
		}
		if (!preg_match(
			'~\*[\s]+\@param[\s]+([A-Za-z0-9_\\\]+)\[\][\s]+\$' . $parameter->getName() . '~s',
			$doc,
			$matches
		)
		) {
			throw new \Exception('write param phpdoc for ' . $calledClass . ' ' . $parameter->getName());
		}
		
		$type = $matches[1];
		
		return $this->getRealType($type, $parameter->getDeclaringClass());
	}
	
	/**
	 * @param string $type
	 * @return bool
	 */
	public function isScalar(string $type): bool
	{
		return in_array($type, $this->scalarTypes);
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
}