<?php

namespace Finances\Site;

/**
 * Group class is used to keep track of a group of routes with similar aspects (the same controller, the same f3-app and etc)
 */
class Routes extends \Dsc\Routes\Group{
	
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * Initializes all routes for this group
	 * NOTE: This method should be overriden by every group
	 */
	public function initialize(){

		$this->setDefaults(
				array(
					'namespace' => '\Finances\Site\Controllers',
					'url_prefix' => '/finances'
				)
		);
		
		$this->add( '/dashboard', 'GET', array(
								'controller' => 'Dashboard',
								'action' => 'index'
								));
		/*$this->add( '/about', 'GET', array(
				'controller' => 'Dashboard',
				'action' => 'about'
		));*/
		
		$this->app->route('GET /finances/about', function() {
			$this->app->reroute('/pages/finances-about');
		});
		
		$this->add( '/transactions/json', 'GET|POST', array(
				'controller' => 'Tranactions',
				'action' => 'json'
		));
		$this->addCrudGroup( 'Transactions', 'Transaction' );
		
		
		
		$this->add( '/transactions', 'GET|POST', array(
								'controller' => 'Transactions',
								'action' => 'json'
								));
		
		$this->add( '/transaction/details/@id', 'GET|POST', array(
				'controller' => 'Transaction',
				'action' => 'details'
		));
	}
}