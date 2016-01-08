@extends('admin/admin-master')

@section('content')

	<nav class="navbar navbar-inverse">
		<div class="container">
		<!-- Collect the nav links, forms, and other content for toggling -->
			<ul class="nav navbar-nav">
				<li><a href="/admin/home">Home</a></li>
				<li class="active"><a href="/admin/students">Students</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<form method="post" action="/admin/logout">
					{{csrf_field()}}
					<li><input type="submit" class="btn btn-danger" name="logout" value="Logout" style="margin-top:5px"></li>
				</form>
			</ul>
		</div><!-- /.container-fluid -->
	</nav>
	<div class="container">
	@foreach($students as $student)
	<div class="student panel">
		<div class="row">
			<div class = "col-md-6">
				<h2>{{$student->name}}</h2>
				<table class="table">
					<tr><td>Name</td><td>{{$student->name}}</td></tr>
					<tr><td>Email</td><td>{{$student->email}}</td></tr>
					<tr><td>City</td><td>{{$student->city}}</td></tr>
					<tr><td>College Name</td><td>{{$student->college_name}}</td></tr>
					<tr><td>Branch</td><td>{{$student->branch}}</td></tr>
					<tr><td>Semester</td><td>{{$student->semester}}</td></tr>
					<tr><td>Interests</td><td> <?php echo implode(unserialize($student->interests),' , ');?> </td></tr>
				</table>
			</div>
			<div class="col-md-6">
				<h3>Events Created</h3><b>
				@if(count($student->events) > 0)
				<ul>
					@foreach($student->events as $event)
						<li><a href="/#/event/{{$event->slug}}">{{$event->name}}</a></li> 
					@endforeach
				</ul>
				@else
					<p class="text-muted" align="center">
						No events created.
					</p>
				@endif
				</b>
			</div>
		</div><hr>
		<div class="row">
			<div class="col-md-12">
				<form class="form pull-right" method="post" action="" onsubmit="prompt('Are you sure you want to delete this student?');">
					{{csrf_field()}}
					<input type="hidden" value="{{$student->id}}" name="student_id">
					<button type="submit" class="btn btn-danger" name="delete">Delete</button>
				</form>
			</div>
		</div>
	</div>
	@endforeach
	</div>
@endsection