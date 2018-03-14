{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('team', 'Team:') !!}
			{!! Form::text('team') !!}
		</li>
		<li>
			{!! Form::label('win', 'Win:') !!}
			{!! Form::text('win') !!}
		</li>
		<li>
			{!! Form::label('hero_level', 'Hero_level:') !!}
			{!! Form::text('hero_level') !!}
		</li>
		<li>
			{!! Form::label('silenced', 'Silenced:') !!}
			{!! Form::text('silenced') !!}
		</li>
		<li>
			{!! Form::label('player_id', 'Player_id:') !!}
			{!! Form::text('player_id') !!}
		</li>
		<li>
			{!! Form::label('hero_id', 'Hero_id:') !!}
			{!! Form::text('hero_id') !!}
		</li>
		<li>
			{!! Form::label('game_id', 'Game_id:') !!}
			{!! Form::text('game_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}