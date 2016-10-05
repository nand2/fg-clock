angular.module('fgclock', [])
.filter('reverse', function() {
  return function(items) {
    if(items != undefined) {
      return items.slice().reverse();
    }
  };
});

function FgClockCtrl($scope, $http) {
  $http.get('data.php').success(function(data) {
    console.log(data);
    $scope.gld_data = data.gld_data;
    $scope.predictive_fg_day = data.predictive_data[data.predictive_data.length - 1].date;
    yihaaah(data.gld_data, data.predictive_data);
  });
}
