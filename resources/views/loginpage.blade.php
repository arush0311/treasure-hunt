<!DOCTYPE html>
<html ng-app="TreasureApp">
<head>
	<title>TresureHunt-Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta content="{{csrf_token()}}" name="csrf-token" />
	<link rel="shortcut icon" href="assets/images/tcf.ico">
	<link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/selectize/selectize.css">
	<link rel="stylesheet" type="text/css" href="/assets/custom/style-login.css">
</head>
<body>

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

	<div class="banner" ng-init="sidebar_decider.sidebar = 'login'" ng-hide="loading">
		<div class="banner-logo">

		</div>
		<div class="form-div card-1">
			<div>

			  <!-- Nav tabs -->
				<ul class="nav nav-tabs">
					<li style="cursor:pointer" ng-class="{active: sidebar_decider.sidebar == 'login'}"><a ng-click="sidebar_decider.sidebar = 'login'">Login</a></li>
					<li style="cursor:pointer" ng-class="{active: sidebar_decider.sidebar == 'register'}"><a ng-click="sidebar_decider.sidebar = 'register'">Register</a></li>
				</ul>

				<!-- Tab panes -->
				<div>
					<div id="login" ng-if="sidebar_decider.sidebar == 'login'" ng-controller="loginController">
						<form class="form-group" ng-submit="login()">
							<div class="text-danger">@{{ error.login }}</div><br>
							<label for="email">Email</label>
							<input type="email" class="form-control" id="email" placeholder="Email Id" ng-model="email" required>

							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" placeholder="Password" ng-model="password" required>
							<a ng-click="sidebar_decider.sidebar = 'forgot_password'" style="cursor:pointer">Forgot Password?</a><br><br>

							<input type="submit" class="btn btn-primary" value="Login" >

						</form>
					</div>
					<div id="register"  ng-if="sidebar_decider.sidebar == 'register'" ng-controller="registerController">
						<form class="form-group" ng-submit="register()">
							<div class="row">
							<div class="text-danger" style="text-align:center;">@{{error.register}}</div>
								<div class="col-md-6">
									<label for="r_name">Name</label>
									<input type="text" class="form-control" id="r_name" placeholder="Name" ng-model="name" required>

									<label for="r_email">Email</label>
									<input type="email" class="form-control" id="r_email" placeholder="Email Id" ng-model="email" required>

									<label for="r_password">Password</label>
									<input type="password" class="form-control" id="r_password" placeholder="Password" ng-model="password" required>

									<label for="repeat_password">Repeat Password</label>
									<input type="password" class="form-control" id="repeat_password" placeholder="Repeat Password" ng-model="repeat_password" required>
									<div class="text-danger">@{{error.password}}</div>

									<label for="college_name">College Name</label>
									<input type="text" class="form-control" id="college_name" placeholder="College Name" ng-model="college_name" required>
								</div>
								<div class="col-md-6">
									<label for="city">City</label>
									<input type="text" class="form-control" id="city" placeholder="City" ng-model="city" required>

									<label for="branch">Branch</label>
									<input type="text" class="form-control" id="branch" placeholder="Branch" ng-model="branch" required>

									<label for="interests">Interests</label>
									<selectize config='myConfig' options='options' ng-model="interests"></selectize>
									<div class="text-danger">@{{error.interests}}</div>

									<label for="semester">Semester</label>
									<select class="form-control" id="semester" ng-model="semester"required>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
										<option>6</option>
										<option>7</option>
										<option>8</option>
									</select>
								</div>
							</div>
							<input type="submit" class="btn btn-primary" value="Register" id="submit">
						
						</form>
					</div>
					<div id="forgot_password" ng-if="sidebar_decider.sidebar == 'forgot_password'" ng-controller="forgotPasswordController">
						<form class="form-group" ng-submit="forgotPassword()">
							<div class="text-danger">@{{error.forgot_password}}</div>
							<label for="email">Email</label><br>
							<em>(Will be used for sending the confirmation link)</em>
							<input type="email" class="form-control" id="email" placeholder="Email Id" ng-model="email"required>

							<label for="new_password">New Password</label>
							<input type="password" class="form-control" id="repeat_password" placeholder="Password" ng-model="new_password" required>
							<label for="repeat_new_password">Repeat New Password</label>
							<input type="password" class="form-control" id="repeat_new_password" placeholder="Password" ng-model="repeat_new_password" required>
							<div class="text-danger">@{{error.new_password}}</div>

							<input type="submit" class="btn btn-primary" value="Submit">

						</form>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-body">
	        <b>@{{ modal.message }}</b>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>




	<script src="assets/jquery/jquery.js"></script>
	<script src="assets/angular/angular.min.js"></script>
	<script src="assets/selectize/selectize.js"></script>
	<script src="assets/angular/angular-selectize.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/custom/script.js"></script>
	<script>
		app = angular.module('TreasureApp',['selectize']);
		app.run(function($rootScope){
			$rootScope.modal = {};
			$rootScope.sidebar_decider = {};
			$rootScope.displayModal = function(str){
				$rootScope.modal.message = str;
				angular.element('#Modal').modal('show');
			};
		})



		app.config(function($httpProvider){
			$httpProvider.interceptors.push('loader');
		});

		app.service('loader',function($rootScope){

			this.request = function(config){
				$rootScope.loading = true;
				config.data._token = $("meta[name='csrf-token']").attr('content');
				return config;
			}

			this.response = function(response){
				$rootScope.loading = false;
				return response;
			}
		});

		app.controller('loginController',function($scope,$rootScope,$http)
		{
			$scope.error = {};
			$scope.login = function(){
				$http({
					method: 'POST',
					url: '/apiv1/login/',
					data:{
						email: $scope.email,
						password : $scope.password
					}
				}).then(function(response){
					if(response.data.errors.length == 0)
					{
						window.location = "/";
					}
					else
					{
						for(i in response.data.errors)
						{
							$scope.error.login = response.data.errors[i][0];
							break;
						}
					}
				},function(response){
					$rootScope.displayModal("Some Error Ocurred while processing your request. Please try after some time");
				});
			}
		});

		app.controller('registerController',function($scope,$rootScope,$http){
			$scope.semester = "1";
			$scope.error = {};
			$scope.options = [
					{title:'Computer Science'},
					{title:'Electronics'},
					{title:'History'},
					{title:'Others'}
				];
			$scope.myConfig = {
				placeholder: 'Select atleast two..',
				valueField: 'title',
				labelField: 'title',
				delimiter : "|"
			}
			$scope.register = function register(){
				var message = "";
				if($scope.password != $scope.repeat_password)
				{
					message = $scope.error.password = "'Password' and 'Repeat Password' fields are not same";
				}
				else if($scope.password.length < 8)
				{
					message = $scope.error.password = "Min length of password must be 8";
				}
				else
				{
					message = $scope.error.password = "";
				}

				if(!$scope.interests || $scope.interests.length < 2)
				{	
					message = $scope.error.interests = "Please select atleast two interests";
				}
				else
				{
					message = $scope.error.interests = "";
				}

				if(message)
				{
					return;
				}

				$http({
					method: 'POST',
					url: '/apiv1/register/',
					data:{
						email: $scope.email,
						password: $scope.password,
						repeat_password: $scope.repeat_password,
						name: $scope.name,
						interests:$scope.interests, 
						semester: $scope.semester,
						college_name: $scope.college_name,
						branch : $scope.branch,
						city: $scope.city
					}
				}).then(function successCallback(response){

					if(response.data.errors.length === 0)
					{
						$rootScope.displayModal("Please check your email for the verification link send by the TheCollegeFever");
					}
					else
					{
						for(i in response.data.errors)
						{
							$scope.error.register = response.data.errors[i][0];
							break;
						}
					}
				},function errorCallback(response){
					$rootScope.displayModal("Some Error Ocurred while processing your request. Please try after some time");
				});
			};

		});


		app.controller('forgotPasswordController',function($scope,$rootScope,$http){
			$scope.error = {};
			$scope.forgotPassword = function(){
				var message = "";
				if($scope.new_password !== $scope.repeat_new_password)
				{
					message = $scope.error.new_password = "'New Password' and 'Repeat New Password' fields are not same";
				}
				else if($scope.new_password.length < 8)
				{
					message = $scope.error.new_password = "Min length of password must be 8";
				}
				else
				{
					message = $scope.error.new_password = "";
				}

				if(!message)
				{
					return;
				}

				$http({
					method: 'POST',
					url: '/apiv1/forgot_password/',
					data:{
						email: $scope.email,
						new_password: $scope.new_password,
						repeat_new_password: $scope.repeat_new_password,
					}
				}).then(function successCallback(response){

					if(response.data.errors.length === 0)
					{
						$rootScope.displayModal("Please check your email for the password reset link send by the TheCollegeFever");
					}
					else
					{
						for(i in response.data.errors)
						{
							$scope.error.forgot_password = response.data.errors[i][0];
							break;
						}
					}
				},function errorCallback(response){
					$rootScope.displayModal("Some Error Ocurred while processing your request. Please try after some time");
				});

			}
		});
	</script>
</body>
</html>