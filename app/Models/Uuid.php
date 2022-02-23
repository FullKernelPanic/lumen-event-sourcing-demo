<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait Uuid
{
    public static function findByUuid(string $uuid, string $fieldName = 'uuid'): Builder
    {
        return self::where($fieldName, DB::raw('UUID_TO_BIN("' . $uuid . '")'));
    }
}
