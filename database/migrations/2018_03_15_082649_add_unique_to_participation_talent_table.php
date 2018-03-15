<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueToParticipationTalentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participation_talent', function (Blueprint $table) {
            $table->unique(['participation_id', 'talent_id']);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participation_talent', function (Blueprint $table) {
            $table->dropUnique(['participation_id', 'talent_id']);
        });
    }
}
