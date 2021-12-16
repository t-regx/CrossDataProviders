<?php

declare(strict_types=1);

namespace TRegx\DataProvider;

class DataProviders
{
    /** @var array */
    private $dataProviders;

    /** @var callable|null */
    private $mapper;

    /** @var callable */
    private $keyMapper;

    /**
     * @param array<string|int, array<int, mixed>> $dataProviders
     */
    public function __construct(array $dataProviders, $mapper, callable $keyMapper)
    {
        $this->dataProviders = $dataProviders;
        $this->mapper = $mapper;
        $this->keyMapper = $keyMapper;
    }

    /**
     * @return array<string|int, array<int, mixed>>
     */
    public function create(): array
    {
        $result = (new ArrayMatrix())->cross($this->dataProviders);
        $entries = (new KeyMapper($this->keyMapper))->map($result);
        if ($this->mapper !== null) {
            $entries = $this->mapEntries($entries);
        }

        $this->validateDataProviders($entries);
        return $entries;
    }

    public static function builder(): DataProvidersBuilder
    {
        return new DataProvidersBuilder([], null, '\json_encode');
    }

    /**
     * @param array<string|int, array<int, mixed>> ...$dataProviders
     *
     * @return array<string|int, array<int, mixed>>
     */
    public static function cross(array ...$dataProviders): array
    {
        return (new DataProviders($dataProviders, null, '\json_encode'))->create();
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function validateDataProviders(array $entries): void
    {
        foreach ($entries as $value) {
            if (!is_array($value)) {
                $message = sprintf("Argument list is supposed to be an array, '%s' given", gettype($value));
                throw new \InvalidArgumentException($message);
            }
            if (array_values($value) !== $value) {
                throw new \InvalidArgumentException("Arguments composed of an associative array");
            }
        }
    }

    private function mapEntries(array $entries): array
    {
        return \array_map(function ($input) {
            return (array)$input;
        }, \array_map($this->mapper, $entries));
    }

    public static function pairs(...$values): array
    {
        return DataProviderPairs::getMixedPairs($values);
    }

    public static function distinctPairs(...$values): array
    {
        return DataProviderPairs::getDistinctPairs($values);
    }

    public static function each(array $array): array
    {
        return DataProvidersEach::each($array);
    }

    public static function eachNamed(array $array)
    {
        return DataProvidersEach::eachNamed($array);
    }
}
