<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    use Uuid;
    
    protected $connection = 'event_store';

    public $timestamps = false;

    protected $fillable = [
        'aggregate_root_id',
        'version',
        'state',
        'recorded_at',
    ];

    protected $table = 'snapshot';
}
