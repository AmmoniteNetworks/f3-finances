<?php 
namespace Finances\Site\Controllers;

class Transactions extends \Dsc\Controller 
{
    public function index()
    {   
   	
        $this->app->set('pagetitle', 'Transactions');
        $this->app->set('subtitle', '');
		
        
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render('Finances/Site/Views::transactions/index.php');
    }

}
