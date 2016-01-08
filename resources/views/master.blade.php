<!DOCTYPE html>
<html ng-app="TreasureApp">
<head>
	<title>TresureHunt-Home</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta content="{{csrf_token()}}" name="csrf-token" />
	<link rel="shortcut icon" href="/assets/images/tcf.ico">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/selectize/selectize.css">
	<link rel="stylesheet" type="text/css" href="/assets/date-picker/datepicker.css">
	<link rel="stylesheet" type="text/css" href="/assets/time-picker/timepicker.css">
	<link rel="stylesheet" type="text/css" href="/assets/custom/style-main.css">
</head>
<body>
	<nav>
		<div class="container">
			<a class="logo" href="#"></a>
			<ul>
				<li><a href="#">Home</a></li>
			</ul>
			<ul class="right">
				<li ng-if="logged_in"><a href="#/edit">Edit</a></li>
				<li ng-if="logged_in"><a href="#/logout">Logout</a></li>
				<li ng-if="!logged_in"><a href="/login">Login</a></li>
			</ul>
		</div>
	</nav>

	<div class='loader-container' ng-show="loading">
	  <div class='loader'>
	    <div class='loader--dot'></div>
	    <div class='loader--dot'></div>
	    <div class='loader--dot'></div>
	    <div class='loader--dot'></div>
	    <div class='loader--dot'></div>
	    <div class='loader--dot'></div>
	    <div class='loader--text'></div>
	  </div>
	</div>
	<div class="container" ui-view ng-hide="loading">

	</div>

	<div class="modal fade" id="serverErrorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-body">
	      <b>A Problem was encounterd while processing your request. Please try after some time</b> 
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
	<script src="/assets/jquery/jquery.js"></script>
	<script src="/assets/angular/angular.min.js"></script>
	<script src="/assets/angular/ui-router.min.js"></script>
	<script src="/assets/angular/angular-cookies.min.js"></script>
	<script src="assets/angular/angular-selectize.js"></script>
	<script src="/routes/routes.js"></script>
	<script src="/controllers/HomeController.js"></script>
	<script src="/controllers/LogoutController.js"></script>
	<script src="/controllers/EventController.js"></script>
	<script src="/controllers/CreateEventController.js"></script>
	<script src="/controllers/ChangePasswordController.js"></script>
	<script src="/controllers/EditProfileController.js"></script>
	<script src="/controllers/TestController.js"></script>
	<script src="/assets/angular/angular-animate.min.js"></script>
	<script src="assets/selectize/selectize.js"></script>
	<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/selectize/selectize.js"></script>
	<script src="/assets/date-picker/datepicker.js"></script>
	<script src="/assets/time-picker/timepicker.js"></script>
	<script src="/assets/custom/script.js"></script>
</body>
</html>
