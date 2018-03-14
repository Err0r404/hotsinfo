<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTalentsTable extends Migration {

	public function up()
	{
		Schema::create('talents', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('reference')->unique();
			$table->text('description');
			$table->integer('level');
			$table->timestamps();
			$table->integer('hero_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('talents');
	}
}