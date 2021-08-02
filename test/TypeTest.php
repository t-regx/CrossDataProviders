<?php

declare(strict_types=1);

namespace TRegx\DataProvider\Test;

use PHPUnit\Framework\TestCase;
use TRegx\DataProvider\Type;

/**
 * @covers \TRegx\DataProvider\Type
 */
class TypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider typesMap
     *
     * @param mixed $value
     * @param string $expectedString
     */
    public function shouldGetMessageWithType($value, string $expectedString): void
    {
        // when
        $string = Type::asString($value);

        // then
        $this->assertEquals($expectedString, $string);
    }

    /**
     * @return array<string, array{0: mixed, 1: string}>
     */
    public function typesMap(): array
    {
        return [
            'null' => [null, 'null'],
            'true' => [true, 'boolean (true)'],
            'false' => [false, 'boolean (false)'],
            'int' => [2, 'integer (2)'],
            'double' => [2.23, 'double (2.23)'],
            'string' => ["She's sexy", "string ('She\'s sexy')"],
            'array' => [[1, new \stdClass(), 3], 'array (3)'],
            'resource' => [self::getResource(), 'resource'],
            'stdClass' => [new \stdClass(), 'stdClass'],
            'class' => [new \stdClass(), 'stdClass'],
            'function' => [
                function () {
                },
                'Closure',
            ],
        ];
    }

    /**
     * @return resource
     */
    private static function getResource()
    {
        return opendir(__DIR__);
    }
}
