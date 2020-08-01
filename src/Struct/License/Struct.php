<?php

namespace App\Struct\License;

class Struct
{
    protected static $mappedFields = [
    ];

    /**
     * @param \stdClass $object
     *
     * @return static
     */
    public static function map($object)
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
                    $newObject->$key = static::$mappedFields[$key]::map($value);
                }
                continue;
            }
            $newObject->$key = $value;
        }

        return $newObject;
    }

    /**
     * @param array $data
     *
     * @return static[]
     */
    public static function mapList($data)
    {
        if (empty($data)) {
            return [];
        }

        return array_map(fn ($item) => static::map($item), $data);
    }
}
