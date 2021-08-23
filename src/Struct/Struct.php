<?php

namespace App\Struct;

abstract class Struct implements \JsonSerializable
{
    final public function __construct()
    {
    }

    /**
     * @var string[]
     */
    public static array $mappedFields = [];

    /**
     * @return static
     */
    public static function map(\stdClass $object): self
    {
        $newObject = new static();

        foreach (get_object_vars($object) as $key => $value) {
            if (empty($value)) {
                $newObject->$key = $value;
                continue;
            }
            if (isset(static::$mappedFields[$key])) {
                if (\is_array($value) && \is_object($value[0])) {
                    $data = [];
                    foreach ($value as $item) {
                        $data[] = static::$mappedFields[$key]::map($item);
                    }
                    $newObject->$key = $data;
                } else {
                    if (\is_array($value)) {
                        $newObject->$key = $value;
                        continue;
                    }

                    $newObject->$key = static::$mappedFields[$key]::map($value);
                }
                continue;
            }
            $newObject->$key = $value;
        }

        return $newObject;
    }

    /**
     * @param array<\stdClass> $data
     *
     * @return static[]
     */
    public static function mapList(array $data): array
    {
        if (empty($data)) {
            return [];
        }

        return array_map(static fn ($item) => static::map($item), $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function make(array $data): static
    {
        $newObject = new static();

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $newObject->$key = $value;
                continue;
            }

            if (isset(static::$mappedFields[$key])) {
                if (\is_array($value) && isset($value[0])) {
                    $data = [];

                    foreach ($value as $item) {
                        $data[] = static::$mappedFields[$key]::make($item);
                    }

                    $newObject->$key = $data;
                } else {
                    $newObject->$key = static::$mappedFields[$key]::make($value);
                }

                continue;
            }

            $newObject->$key = $value;
        }

        return $newObject;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
