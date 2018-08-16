<?php
declare(strict_types=1);

namespace ifrolikov\dto\Tests;

use ifrolikov\dto\DtoFakeDataGenerator;
use ifrolikov\dto\PhpDocManager;
use ifrolikov\dto\Tests\Data\CafeDto;
use PhpDocReader\PhpParser\UseStatementParser;
use PHPUnit\Framework\TestCase;

class DtoFakeDataGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        $expectedValue = '[
    \'name\' => $this->faker->word,
    \'bar\' => [
        \'beers\' => [
            [
                \'label\' => $this->faker->word
            ]
        ],
        \'officiants\' => [
            $this->faker->word
        ]
    ]
]
';
        $generator = new DtoFakeDataGenerator(new PhpDocManager(new UseStatementParser()), '$this->faker->');
        $value = $generator->generate(CafeDto::class, true);
        $this->assertEquals($expectedValue, $value);
    }
}