@extends('layout-web')
@section('content')


<div class="container">
	<div class="column is-8 is-offset-2">
		<div class="panel">
			<div class="panel-heading">
				lista de friendsee
			</div>	
			@forelse ($friends as $friend)
			
			<a href="{{ route('chat.show', $friend->id) }}" class="btn btn-primary"></a>
				<div class="panel-block">
					{{ $friend->usuario }}
				</div>	
			@empty
			<div class="panel-block">
				saludo
			</div>
			@endforelse
		</div>
	</div>
</div>


@endsection