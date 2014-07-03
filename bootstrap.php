<?php 

class FinancesBootstrap extends \Dsc\Bootstrap{
	protected $dir = __DIR__;
	protected $namespace = 'Finances';

	protected function runAdmin(){

		parent::runAdmin();
	}
	protected function runSite(){
	 \Dsc\System::instance()->get('theme')->registerViewPath( __dir__ . '/Site/Views/', 'Finances/Site/Views' );

		parent::runSite();
	}
}
$app = new FinancesBootstrap();