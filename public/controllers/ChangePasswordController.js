app.controller('ChangePasswordController',function($scope,$rootScope,$http){
	$scope.error = {};
	$scope.success = {};
	$scope.changePassword = function(){

		if($scope.new_password !== $scope.repeat_new_password)
		{
			message = $scope.error.password = "'New Password' and 'Repeat New Password' fields are not same";
		}
		else if($scope.new_password.length < 8)
		{
			message = $scope.error.password = "Min length of password must be 8";
		}
		else
		{
			message = $scope.error.password = "";
		}
		if(typeof message !== 'undefined' && message)
		{
			return;
		}



		$http({
			method: 'POST',
			url: '/apiv1/change_password',
			data:{
				password: $scope.password,
				new_password: $scope.new_password,
				repeat_new_password: $scope.repeat_new_password
			}
		}).then(function (response){
			if(response.data.errors.length !== 0)
			{
				for(i in response.data.errors)
				{
					$scope.error.password = response.data.errors[i][0];
					break;
				}
			}
			else
			{
				$scope.error.password = "";

				$scope.success.password = "Your password has been changed successfully";
			}
		},function (response){
			$rootScope.showServerError();
		})
	};
});