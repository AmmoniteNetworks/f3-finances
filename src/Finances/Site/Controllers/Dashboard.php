<?php 
namespace Finances\Site\Controllers;

class Dashboard extends \Dsc\Controller 
{
    public function index()
    {   
   	
        $this->app->set('pagetitle', 'Home');
        $this->app->set('subtitle', '');
		
        $dash = (New \Finances\Models\Dashboard)->setCondition('type','stripe')->getItem();
        
        $this->app->set('dash', $dash);
        
        //$transactions = (New \Finances\Models\Transactions)->setParam('limit',10)->getItems();
        $paginated = (New \Finances\Models\Transactions)->populateState()->setState('list.sort',array('_id' => -1))->paginate();
        
        $this->app->set('transactions',$paginated);
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render('Finances/Site/Views::dashboard/index.php');
    }
    
    public function about()
    {
    
    	$this->app->set('pagetitle', 'About');
    	$this->app->set('subtitle', '');
    
    	$view = \Dsc\System::instance()->get( 'theme' );
    	echo $view->render('Finances/Site/Views::about/index.php');
    }

}
