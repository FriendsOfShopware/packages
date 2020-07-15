<?php

namespace App\Struct;

abstract class Struct implements \JsonSerializable
{
    public static $mappedFields = [];

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
                if (is_array($value) && is_object($value[0])) {
                    $data = [];
                    foreach ($value as $item) {
                        $data[] = static::$mappedFields[$key]::map($item);
                    }
                    $newObject->$key = $data;
                } else {
                    if (is_array($value)) {
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
     * @return static[]
     */
    public static function mapList(array $data): array
    {
        if (empty($data)) {
            return [];
        }

        return array_map(fn ($item) => static::map($item), $data);
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
