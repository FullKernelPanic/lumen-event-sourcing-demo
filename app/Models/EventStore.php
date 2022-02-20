<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventStore extends Model
{
    protected $connection = 'event_store';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'aggregate_root_id',
        'version',
        'payload',
        'recorded_at',
    ];

    protected $table = 'event_store';
}
