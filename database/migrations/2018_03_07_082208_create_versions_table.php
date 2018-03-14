<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVersionsTable extends Migration {

	public function up()
	{
		Schema::create('versions', function(Blueprint $table) {
			$table->increments('id');
			$table->string('version')->unique();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('versions');
	}
}