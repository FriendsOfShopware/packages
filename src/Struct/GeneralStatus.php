<?php

namespace App\Struct;

/**
 * The store has lot of fields just with id, name, description.
 * Instead of having for any one a single struct. Just use that there
 */
class GeneralStatus extends Struct
{
    public int $id;

    public string $name;

    public string $description;
}
