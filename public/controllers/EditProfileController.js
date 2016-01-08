app.controller('EditProfileController',function($scope,$rootScope,$http){
	$scope.error = {};
	$scope.success = {};
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
	$scope.semester = "1";

	$http({
		method:'GET',
		url : "/apiv1/get_profile"
	}).then(function(response){
		if(response.data.errors.length !== 0)
		{
			$rootScope.showServerError();
		}
		else
		{
			var temp = response.data.data;
			$scope.name = temp.name;
			$scope.college_name = temp.college_name;
			$scope.city = temp.city;
			$scope.branch = temp.branch;
			$scope.semester = temp.semester;
			$scope.interests = temp.interests
		}
	},function(response){
		$rootScope.showServerError();
	})

	$scope.edit = function(){

		if(!$scope.interests || $scope.interests.length < 2)
		{	
			message = $scope.error.interests = "Please select atleast two interests";
		}
		else
		{
			message = $scope.error.interests = "";
		}

		if(typeof message !== 'undefined' && message)
		{
			return;
		}

		$http({
			method : "POST",
			url : '/apiv1/edit_profile/',
			data:{
				college_name : $scope.college_name,
				city: $scope.city,
				branch: $scope.branch,
				semester:$scope.semester,
				interests:$scope.interests
			}
		})
		.then(function(response){
			if(response.data.errors.length !== 0)
			{
				for(i in response.data.errors)
				{
					$scope.error.all = response.data.errors[i][0];
					break;
				}
			}
			else
			{
				$scope.error.all = "";

				$scope.success.all = "The Profile information has been updated";
			}
		},function(response){
			$rootScope.showServerError();
		})
	}

});