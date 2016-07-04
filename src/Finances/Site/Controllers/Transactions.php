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
    
    public function json()
    {
    
    	$this->app->set('meta.title', 'Finances');
    	$this->app->set('subtitle', '');
    
    	//$dash = (New \Finances\Models\Dashboard)->setCondition('type','stripe')->getItem();
    	$transactions = (New \Finances\Models\Transactions)->populateState()->setState('list.sort',array('_id' => -1))->getList();
    	//$this->app->set('dash', $dash);
    	$json = array('data'=> array());
    	foreach ($transactions as $trans) {
    		
    		$json['data'][] = array($trans->type, $trans->getAmount(), $trans->getActualAmount(),$trans->getDesc(), '<a target="_blank" href="/finances/transaction/details/'. $trans->id.'">Details</a>', date("m-d-y",$trans->created) );
    	}
    	
    	echo json_encode($json);
    	exit;
     }

}
