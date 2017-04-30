(function() {
	"use strict";

	angular.module('app.accounts', ['ui.router'])

	/* Route
	/*------------------------------------------------*/

	.config(['$stateProvider', function($stateProvider) {
		// Parent state
		$stateProvider.state('default.accounts', {
			url: '/accounts',
			views: {
				"main@default": {
					templateUrl: './views/accounts/accounts.html',
					controller: ViewCtrl,
					controllerAs: 'vm'
				}
			},
			data: {
				title: 'Superstor | Accounts'
			}
		});

	}]);

	// Inject
	ViewCtrl.$inject = ['$scope'];

	// Controller for navigation component
	function ViewCtrl($scope) {
		var vm;

		// Controller on init
		this.$onInit = function() {
			vm = this;

			vm.rows = [
				{ id: '123', name: 'Added name 1' },
				{ id: '321', name: 'Added name 2' }
			];

			vm.add = add;
		};

		// Controller on destroy
		this.$onDestroy = function() {
			// Unbind rootscope listeners
		};


		// Functions

		/**
		 *	Function that adds to a table
		 */
		function add() {
			if (vm.name.indexOf('ä') > -1 || vm.name.indexOf('õ') > -1 || vm.name.indexOf('ü') > -1 || vm.name.indexOf('ö') > -1) {
				vm.name = undefined;
			}
			vm.rows.push({
				id: vm.id,
				name: vm.name
			});
			vm.id = undefined;
			vm.name = undefined;
		}
	}

})();
