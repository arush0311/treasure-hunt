@extends('admin/admin-master')


@section('content')
<div class="container" style="">
	<div class="row">
		<div class="col-md-4  col-md-offset-4 panel loginbox">
			<h3>Login</h3>
			<form method="post" class="form" action="">
				{{ csrf_field() }}

				<label for="username">Username</label>
				<input type="text" id="username" name="username" class="form-control" placeholder="Username"><br>

				<label for="password">Password</label>
				<input type="password" id="password" name="password" class="form-control" placeholder="Password"><br>

				<input type="submit" class="btn btn-success" value="Login!" name="login"><br><br>
			</form>
		</div>	
	</div>

</div>
@endsection