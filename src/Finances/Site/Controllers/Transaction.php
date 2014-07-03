<?php 
namespace Finances\Site\Controllers;

class Transaction extends \Dsc\Controller 
{
    public function details()
    {   
   	
        $this->app->set('pagetitle', 'Transactions');
        $this->app->set('subtitle', '');
		
        $this->app->set('transaction', $this->getItem());
        
        
        echo  $this->theme->render('Finances/Site/Views::transactions/details.php');
    }
    
    protected function getItem()
    {
    	$f3 = \Base::instance();
    	$id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    	$model = $this->getModel()
    	->setState('filter.id', $id);
    
    	try {
    		$item = $model->getItem();
    	} catch ( \Exception $e ) {
    		\Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
    		$f3->reroute( $this->list_route );
    		return;
    	}
    
    	return $item;
    }
    
	protected function getModel($name = 'transactions')
    {
        $model = null;
        switch( $name ) {
        	case 'transactions' :
		        $model = new \Finances\Models\Transactions;
        		break;
       		case 'categories' :
       			$model = new \Finances\Models\Categories;
       			break;
        }
        return $model;
    }

}
