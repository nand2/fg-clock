function FgClockCtrl($scope, $http) {
  $http.get('/data.php').success(function(data) {
    $scope.gld_data = data;
    console.log(data);
    setTimeout(yihaaah, 1000);
  });
}