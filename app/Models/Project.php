<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Uuid;

    protected $fillable = [
        'uuid',
        'name',
    ];

    protected $table = 'projects';
}
