{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('length', 'Length:') !!}
			{!! Form::text('length') !!}
		</li>
		<li>
			{!! Form::label('date', 'Date:') !!}
			{!! Form::text('date') !!}
		</li>
		<li>
			{!! Form::label('api_id', 'Api_id:') !!}
			{!! Form::text('api_id') !!}
		</li>
		<li>
			{!! Form::label('map_id', 'Map_id:') !!}
			{!! Form::text('map_id') !!}
		</li>
		<li>
			{!! Form::label('type_id', 'Type_id:') !!}
			{!! Form::text('type_id') !!}
		</li>
		<li>
			{!! Form::label('version_id', 'Version_id:') !!}
			{!! Form::text('version_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}