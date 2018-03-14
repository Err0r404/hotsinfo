<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGamesTable extends Migration {

	public function up()
	{
		Schema::create('games', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('length');
			$table->datetime('date');
			$table->integer('api_id')->unique();
			$table->timestamps();
			$table->integer('map_id')->unsigned();
			$table->integer('type_id')->unsigned();
			$table->integer('version_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('games');
	}
}