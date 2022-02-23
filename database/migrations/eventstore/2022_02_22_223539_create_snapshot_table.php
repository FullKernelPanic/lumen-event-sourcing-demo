<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSnapshotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('event_store')->create('snapshot', function (Blueprint $table) {
            $table->char('aggregate_root_id', 16)->charset('binary');
            $table->integer('version')->unsigned();
            $table->string('state', 16001);
            $table->dateTime('recorded_at', 6);

            $table->engine = 'MyISAM';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('event_store')->dropIfExists('snapshot');
    }
}
