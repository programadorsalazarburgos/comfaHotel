@extends('layout-web')
@section('content')

<meta name="friendId" content="{{ $friend->id }}">
<div class="container">
	<div class="column is-8 is-offset-2">
		<div class="panel">
			<div class="panel-heading">
				
			</div>	
			
				<div class="panel-block">
					{{ $friend->usuario }}
				</div>	
			<div class="panel-block">
				<chat v-bind:chats="chats" v-bind:userid="{{ Auth::user()->id}}" v-bind:friendid="{{ $friend->id }}"></chat>
			</div>
			
		</div>
	</div>
</div>


@endsection