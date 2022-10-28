(function(){
    'use strict';
    var app = angular.module('flexiblepos', [ ]);

    app.controller("SearchItemCtrl", [ '$scope', '$http', function($scope, $http) {
        $scope.items = [ ];
        $scope.add_payment = 0;

        $http.get(site_url+'/api/item').success(function(data) {
            $scope.items = data;
        });

        $scope.recitems = [ ];
        $http.get(site_url+'/api/recitem').success(function(data) {
            $scope.recitems = data;
        });

        $scope.saletemp = [ ];
        $scope.newsaletemp = { };
        $http.get(site_url+'/api/saletemp').success(function(data, status, headers, config) {
            $scope.saletemp = data;
        });

        $scope.addSaleTemp = function(item, newsaletemp) {
            $http.post(site_url+'/api/saletemp', { item_id: item.id,attribute_id: item.attribute_id, cost_price: item.cost_price, selling_price: item.selling_price }).
            success(function(data, status, headers, config) {
                $scope.saletemp.push(data);
                    $http.get(site_url+'/api/saletemp').success(function(data) {
                    $scope.saletemp = data;
                    });
            });
        }

        // $scope.customitem = { };
        $scope.addCustomItem = function(customitem) {
            if(typeof (customitem.item_name) == 'undefined') {
                $.notify({ message: "Product can't be added!" }, { type: 'danger' });
            }
            $http.post(site_url+'/item/customcreate', { item_name: customitem.item_name, attribute_id: customitem.attribute_id,category_id: customitem.category_id, cost_price: customitem.cost_price, selling_price: customitem.selling_price, quantity: customitem.quantity, type: 'sale' }).
            success(function(data, status, headers, config) {
                $scope.customitem = { };
                $.notify({ message: 'Product added successfully!' }, { type: 'success' });
                $scope.saletemp.push(data);
                    $http.get(site_url+'/api/saletemp').success(function(data) {
                    $scope.saletemp = data;
                    });
            }).
            error(function(data, status, headers, config) {
                $.notify({ message: "Product can't be added!" }, { type: 'danger' });
            });
            
        }

        $scope.updateSaleTemp = function(newsaletemp) {
            console.log(newsaletemp);
            $http.put(site_url+'/api/saletemp/' + newsaletemp.id, {attribute_id: newsaletemp.attribute_id, quantity: newsaletemp.quantity, total_cost: newsaletemp.item.cost_price * newsaletemp.quantity, selling_price: newsaletemp.selling_price,
                total_selling: newsaletemp.selling_price * newsaletemp.quantity }).
            success(function(data, status, headers, config) {
                
                });
        }
        $scope.removeSaleTemp = function(id) {
            $http.delete(site_url+'/api/saletemp/' + id).
            success(function(data, status, headers, config) {
                $http.get(site_url+'/api/saletemp').success(function(data) {
                        $scope.saletemp = data;
                        });
                });
        }
        $scope.sum = function(list) {
            var total=0;
            angular.forEach(list , function(newsaletemp){
                    total += parseFloat(newsaletemp.selling_price * newsaletemp.quantity);
            });
            return total;
        }
    }]);
})();