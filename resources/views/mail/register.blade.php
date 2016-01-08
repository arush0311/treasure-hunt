@extends('mail/mail-master')

@section('content')
	Congratulations on joining TheTreasureHunt.<br>
	Just one more step, click on the link below and verify your account <br><br>

	<a href="http://localhost:8000/verify/{{$email}}/{{$token}}">http://localhost:8000/verify/{{$email}}/{{$token}}</a><br><br>

	If you did not register please ignore this mail.</em><br>

	<b>Note:</b>This link is only valid for one day<br><br>
@endsection


