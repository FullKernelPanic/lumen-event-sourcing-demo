<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventStoreTable extends Migration
{
    public function up()
    {
        Schema::connection('event_store')->create('event_store', function (Blueprint $table) {
            $table->char('event_id', 16)->charset('binary');
            $table->char('aggregate_root_id', 16)->charset('binary');
            $table->integer('version', 20);
            $table->string('payload', 16001);
            $table->dateTime('recorded_at', 6);
            $table->index('aggregate_root_id');
            $table->unique(['aggregate_root_id', 'version'], 'uid_reconstitution');

            $table->dropPrimary('version');
            $table->primary('event_id');
            $table->engine = 'MyISAM';
        });
    }

    public function down()
    {
        Schema::connection('event_store')->dropIfExists('event_store');
    }
}
