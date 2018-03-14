{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('participation_id', 'Participation_id:') !!}
			{!! Form::text('participation_id') !!}
		</li>
		<li>
			{!! Form::label('talent_id', 'Talent_id:') !!}
			{!! Form::text('talent_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}