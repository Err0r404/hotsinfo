<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParticipationTalentTable extends Migration {

	public function up()
	{
		Schema::create('participation_talent', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('participation_id')->unsigned();
			$table->integer('talent_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('participation_talent');
	}
}