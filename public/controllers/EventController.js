app.controller('EventController',function($scope,$http,$stateParams,$rootScope,$cookies){
	$scope.temp = {};
	$scope.temp.round_selected = "1";
	$http({
		method:"GET",
		url:"/apiv1/show_event/"+$stateParams.eventSlug
	}).then(function successCallback(response) {
		$rootScope.logged_in = response.data.logged_in;
		$scope.data = response.data.data;
		$scope.endtime = {};
		for(var i in $scope.data.rounds)
		{
			$scope.endtime[i] = $scope.data.rounds[i].date_time + $scope.data.rounds[i].duration*3600000;
		}
	}, function errorCallback(response) {

	})
	$scope.register=function()
	{
		$http({
			method:"POST",
			url:"/apiv1/register/"+$stateParams.eventSlug
		}).then(function(){
			$scope.data.registered = true;
		})
	}
	var now = new Date();
	$cookies.put('logged_in',$rootScope.logged_in,{
		expires: new Date(now.getFullYear(), now.getMonth(), now.getDate()+1)
	});

})