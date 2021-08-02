<?php

declare(strict_types=1);

namespace TRegx\DataProvider\Test\Builder;

use PHPUnit\Framework\TestCase;
use TRegx\DataProvider\DataProviders;

/**
 * @covers \TRegx\DataProvider\DataProviders
 */
class DataProvidersTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAllowArbitraryDataAsLongAsItsMappedToValid(): void
    {
        // when
        $result = DataProviders::builder()
            ->addJoinedSection(['one' => 1], ['two' => 2])
            ->addJoinedSection(['letter-1' => 'A'], ['letter-2' => 'B'])
            ->entryMapper(function ($entry) {
                return array_values(array_flip($entry));
            })
            ->entryKeyMapper(function (array $keys) {
                return join(' + ', $keys);
            })
            ->build();

        // then
        $expected = [
            '0 + 0' => ['one', 'letter-1'],
            '0 + 1' => ['one', 'letter-2'],

            '1 + 0' => ['two', 'letter-1'],
            '1 + 1' => ['two', 'letter-2'],
        ];
        $this->assertEquals($expected, $result);
    }
}
