@extends('mail/mail-master')

@section('content')
	Please click the following to reset yout password<br><br>
	
	<a href="http://localhost:8000/apiv1/forgot_password/{{$email}}/{{$token}}">http://localhost:8000/apiv1/forgot_password/{{$email}}/{{$token}}</a>

	If you did not request for a Password Reset please ignore this mail.</em><br>

	<b>Note:</b>The token is only valid for one day<br><br>

@endsection