<?php

declare(strict_types=1);

namespace TRegx\DataProvider\Test;

use PHPUnit\Framework\TestCase;
use TRegx\DataProvider\DataProvidersEach;
use TRegx\DataProvider\DuplicatedValueException;

/**
 * @covers \TRegx\DataProvider\DuplicatedValueException
 */
class DataProvidersEachTest extends TestCase
{
    /**
     * @test
     */
    public function eachNamedShouldThrowForInvalidType(): void
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("eachNamed() only accepts string, but integer (1) given");

        // when
        DataProvidersEach::eachNamed(['One', 'Two', 1]);
    }

    /**
     * @test
     */
    public function eachShouldThrowForDuplicated(): void
    {
        // then
        $this->expectException(DuplicatedValueException::class);
        $this->expectExceptionMessage("Duplicated entry passed to each(): string ('One')");

        // when
        DataProvidersEach::each(['One', 'Two', 'One']);
    }

    /**
     * @test
     */
    public function eachNamedShouldThrowForDuplicated(): void
    {
        // then
        $this->expectException(DuplicatedValueException::class);
        $this->expectExceptionMessage("Duplicated entry passed to each(): string ('One')");

        // when
        DataProvidersEach::eachNamed(['One', 'Two', 'One']);
    }
}
