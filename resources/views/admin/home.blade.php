@extends('admin/admin-master')

@section('content')

	<nav class="navbar navbar-inverse">
		<div class="container">
		<!-- Collect the nav links, forms, and other content for toggling -->
			<ul class="nav navbar-nav">
				<li class="active"><a href="/admin/home">Home</a></li>
				<li><a href="/admin/students">Students</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<form method="post" action="/admin/logout">
					{{csrf_field()}}
					<li><input type="submit" class="btn btn-danger" name="logout" value="Logout" style="margin-top:5px"></li>
				</form>
			</ul>
		</div><!-- /.container-fluid -->
	</nav>

	@if(count($events) == 0)
		<p class="text-muted" align="center">There are no new events to verify</p>
	@endif

	@foreach($events as $event)
	<div class="container">
		<h2>Some events To be Verified</h2><br>
		<div class="event">
			<div class="row">
				<div class="col-md-2">
					<img src="{{$event->event_image}}" class="event-pic"><br><br>
					<p align="center"><b>Event Picture</b></p>
				</div>

				<div class="col-md-2">
					<img src="{{$event->background_image}}" class="event-pic"><br><br>
					<p align="center"><b>Background Picture</b></p>
				</div>
				<div class="event-div col-md-8">
					<b>{{$event->name}}</b><br>
					<b>{{$event->category}}</b><br>
					<b>Rounds: </b>{{$event->no_of_rounds}}<br>
					<b>Prizes: </b>{{$event->prizes}}<br>
					<b>Description: </b>{{$event->description}}<br>
					<b>Creator name: </b>{{$event->student->name}}<br>
					<b>Creator email: </b>{{$event->student->email}}<br>

				</div>
			</div><br><hr>
			<div class="row">
				@foreach($event->rounds as $round)
				<div class="event-div col-md-3">
					<b>Round No. {{$round->no}}</b><br>
					<b>No of Questions:</b> {{$round->no_of_questions}}<br>
					<b>At:</b>{{ $round->date_time }}<br>
					<b>Duration:</b> {{$round->duration}} hours<br>
					@if($round->cutoff)
						<b>Cutoff: </b> {{$round->cutoff}}<br>
					@endif
				</div>
				@endforeach
				<br>
				<form class="form pull-right col-md-3" action="" method="post">
					{{csrf_field()}}
					<input type="hidden" value="{{$event->slug}}" name="event_slug">
					<input type="submit" value="accept" name="verify" class="btn btn-success">
					<input type="submit" value="reject" name="verify" class="btn btn-danger">
			</div>
		</div>
		@endforeach
	</div>
	


@endsection