app.controller('CreateEventController',function($scope,$rootScope,$location,$http){
	if(!$rootScope.logged_in)
	{
		console.log('frtgyhujik');
		window.location = "/login";
	}
	$scope.event = {};
	$scope.rounds = {};
	$scope.errors = {};
	$scope.errors.rounds = {};
	$scope.event.no_of_rounds = "1";
	$scope.event.category = "Computer Science";
	temp_date = new Date();
	temp_date.setDate(temp_date.getDate() + 1);
	$scope.minDate = temp_date.toDateString();

	$scope.range = function(number){
		var x = [];
		for(var a=1;a<=number;a++)
		{
			x.push(a);
		}
		return x;
	}

	$scope.create = function(){
		$scope.event.rounds = $scope.rounds;
		if(!$scope.event.event_image || !$scope.event.background_image)
		{
			$scope.error = $scope.errors.images = "Select an event image as well as a background image";
			display_error();
			return;
		}
		else
		{
			$scope.errors.images = "";

		}
		for(round_no in $scope.rounds)
		{
			if(round_no > $scope.event.no_of_rounds)
			{
				delete $scope.rounds[round_no];
			}

			if(round_no === $scope.event.no_of_rounds)
			{
				delete $scope.rounds[round_no].cutoff;
			}
		}
		for(round_no in $scope.rounds)
		{
			$scope.errors.rounds[round_no] = {};
			$scope.errors.rounds[round_no].questions = {};

			if(!Number($scope.rounds[round_no].no_of_questions) || Number($scope.rounds[round_no].no_of_questions) < 1)
			{
				$scope.errors.rounds[round_no].no_of_questions = "Dont be silly! There must be atleast 1 question";
				display_error();
				return;
			}
			else
			{
				$scope.errors.rounds[round_no].no_of_questions = "";
			}

			if(!$scope.rounds[round_no].date)
			{
				$scope.errors.rounds[round_no].date = "Please enter date on which the current round will be held";
				display_error();
				return;
			}
			else
			{
				$scope.errors.rounds[round_no].date = "";
			}
			for(question_no in $scope.rounds[round_no].questions)
			{
				if(question_no > Number($scope.rounds[round_no].no_of_questions))
				{
					delete $scope.rounds[round_no].questions[question_no];
				}
			}
			for(question_no in $scope.rounds[round_no].questions)
			{

				if(!$scope.rounds[round_no].questions[question_no].answer)
				{
					$scope.errors.rounds[round_no].questions[question_no] = "Please select the option with the right answer";
					display_error();
					return;
				}
				else
				{
					$scope.errors.rounds[round_no].questions[question_no] = "";
				}
			}
		}

		$http({
			method: "POST",
			url: '/apiv1/create_event/',
			data: $scope.event
		})
		.then(function successCallback(response){
				if(response.data.errors.length != 0)
				{
					$rootScope.showServerError();
				}
				else
				{
					angular.element('#successModal').modal('show');
				}
			},function errorCallback(response){
				$rootScope.showServerError();
			}
		);
		
	}

	function display_success()
	{
		angular.element('#successModal').modal('show');
	}

	function display_error()
	{
		angular.element('#errorModal').modal('show');
	}

	angular.element(document).ready(function(){
		angular.element("#eventimg").change(function(){
		    readURL(this,'#eventimg-target',"#eventimg");
		});

		angular.element("#backgroundimg").change(function(){
		    readURL(this,'#backgroundimg-target',"#backgroundimg");
		});

	});
	

	var readURL = function (input,target,src)
	{
		if(input.files && input.files[0])
		{
			if(input.files[0].size > 2097152)
			{
				$scope.errors.images = "The Image Size should be less than 2 MB";
			}
			else
			{
				var filename = $(src).val();
				var extension = filename.replace(/^.*\./, '');

				// Iff there is no dot anywhere in filename, we would have extension == filename,
				// so we account for this possibility now
				if (extension == filename) 
				{
				    extension = '';
				} 
				else 
				{
				    // if there is an extension, we convert to lower case
				    // (N.B. this conversion will not effect the value of the extension
				    // on the file upload.)
				    extension = extension.toLowerCase();
				}
				if(extension == "jpeg" || extension == "jpg")
				{
					$scope.errors.images = "";
					var temp_url = URL.createObjectURL(input.files[0]);
					$(target).css('background-image',"url('"+temp_url+"')");

					getBase64Image(temp_url,target);
				}
				else
				{
					$scope.errors.images = "Only jpg/jpeg images are allowed";
				}
			}
			$scope.$apply()
		}
	}

	function getBase64Image(url,target) {
		var img = new Image();

		img.setAttribute('crossOrigin', 'anonymous');

		img.onload = function () {
		    var canvas = document.createElement("canvas");
		    canvas.width =this.width;
		    canvas.height =this.height;

		    var ctx = canvas.getContext("2d");
		    ctx.drawImage(this, 0, 0);

		    var dataURL = canvas.toDataURL("image/jpg");

		    var b64 = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");

			if(target ==  '#eventimg-target')
			{
				$scope.event.event_image = b64;
			}
			else if(target ==  '#backgroundimg-target')
			{
				$scope.event.background_image = b64;
			}


		};

		img.src = url;
	}
})