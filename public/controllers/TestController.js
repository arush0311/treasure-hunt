app.controller('TestController',function($scope,$rootScope,$http,$stateParams,$interval){
	$scope.errors= {};
	$scope.answers= {};

	$scope.submitTest = function(){
		$http({
			method: 'POST',
			url: '/apiv1/submit_test/'+$stateParams.eventSlug+"/"+$stateParams.roundNo,
			data: {
				answers:$scope.answers
			}
		})
		.then(function (response){
			if(response.data.errors.length != 0)
			{
				for(var i in response.data.errors)
				{
					$scope.errors.is = true;
					$scope.errors.errorMessage = response.data.errors[i];
					angular.element('#errorModal').modal('show');
				}
			}

			else
			{
				$scope.errors.successMessage = "Your test has been submitted successfully. You will be notified when the results are declared";
				angular.element('#successModal').modal('show');

			}
		},function (response){
			$rootScope.showServerError();
		})
	}





	$http({
		method: 'GET',
		url: '/apiv1/show_test/'+$stateParams.eventSlug+"/"+$stateParams.roundNo
	})
	.then(function (response){
		if(response.data.errors.length != 0)
		{
			for(var i in response.data.errors)
			{
				$scope.errors.is = true;
				$scope.errors.errormessage = response.data.errors[i];
				angular.element('#errorModal').modal('show');
			}
		}
		else
		{
			$scope.errors.is = false;
			$scope.data = response.data.data;
			$scope.timestamp = response.data.timestamp;
			$scope.time_left = $scope.data.date_time + (3600000 * $scope.data.duration)- $scope.timestamp;

			var Tick = function(){
				$scope.time_left = $scope.time_left - 1000;
				if($scope.time_left<=1000)
				{
					$interval.cancel(promise);
					$scope.submitTest();
				}
			}

			var promise = $interval(Tick,1000);

			$scope.$on('$destroy', function(){
			  $interval.cancel(promise);
			});
		}
	},function (response){
		$rootScope.showServerError();
	});


});