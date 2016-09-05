angular.module('controller.ImageCopper', ['ImageCropper'])

        .controller('ImageCopperController', ['$scope', function ($scope) {

                $scope.fileChanged = function (e) {
                    var files = e.target.files;

                    var fileReader = new FileReader();
                    fileReader.readAsDataURL(files[0]);

                    fileReader.onload = function (e) {
                        $scope.imgSrc = this.result;
                        $scope.$apply();
                    };

                };
                $scope.clear = function () {
                    $scope.imageCropStep = 1;
                    delete $scope.imgSrc;
                    delete $scope.result;
                    delete $scope.resultBlob;
                };
                $scope.upload_picture = function (imageData) {
                };
            }]);

