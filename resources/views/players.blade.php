{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('battletag', 'Battletag:') !!}
			{!! Form::text('battletag') !!}
		</li>
		<li>
			{!! Form::label('blizzard_id', 'Blizzard_id:') !!}
			{!! Form::text('blizzard_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}