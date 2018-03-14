<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('games', function(Blueprint $table) {
			$table->foreign('map_id')->references('id')->on('maps')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('games', function(Blueprint $table) {
			$table->foreign('type_id')->references('id')->on('types')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('games', function(Blueprint $table) {
			$table->foreign('version_id')->references('id')->on('versions')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('heroes', function(Blueprint $table) {
			$table->foreign('role_id')->references('id')->on('roles')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('talents', function(Blueprint $table) {
			$table->foreign('hero_id')->references('id')->on('heroes')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->foreign('player_id')->references('id')->on('players')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->foreign('hero_id')->references('id')->on('heroes')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->foreign('game_id')->references('id')->on('games')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participation_talent', function(Blueprint $table) {
			$table->foreign('participation_id')->references('id')->on('participations')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('participation_talent', function(Blueprint $table) {
			$table->foreign('talent_id')->references('id')->on('talents')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('games', function(Blueprint $table) {
			$table->dropForeign('games_map_id_foreign');
		});
		Schema::table('games', function(Blueprint $table) {
			$table->dropForeign('games_type_id_foreign');
		});
		Schema::table('games', function(Blueprint $table) {
			$table->dropForeign('games_version_id_foreign');
		});
		Schema::table('heroes', function(Blueprint $table) {
			$table->dropForeign('heroes_role_id_foreign');
		});
		Schema::table('talents', function(Blueprint $table) {
			$table->dropForeign('talents_hero_id_foreign');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->dropForeign('participations_player_id_foreign');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->dropForeign('participations_hero_id_foreign');
		});
		Schema::table('participations', function(Blueprint $table) {
			$table->dropForeign('participations_game_id_foreign');
		});
		Schema::table('participation_talent', function(Blueprint $table) {
			$table->dropForeign('participation_talent_participation_id_foreign');
		});
		Schema::table('participation_talent', function(Blueprint $table) {
			$table->dropForeign('participation_talent_talent_id_foreign');
		});
	}
}