<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayersTable extends Migration {

	public function up()
	{
		Schema::create('players', function(Blueprint $table) {
			$table->increments('id');
			$table->string('battletag');
			$table->integer('blizzard_id')->unique();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('players');
	}
}