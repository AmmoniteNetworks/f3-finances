<?php 
namespace Finances\Site\Controllers;

class Transaction extends \Dsc\Controller 
{	
	use \Dsc\Traits\Controllers\CrudItemCollection;
	use \Dsc\Traits\Controllers\SupportPreview;
	
	protected $list_route = '/finances/transactions/dashboard';
	protected $create_item_route = '/finances/transaction/create';
	protected $get_item_route = '/finances/transaction/read/{id}';
	protected $edit_item_route = '/finances/transaction/edit/{id}';
	
    public function details()
    {   
   	
        $this->app->set('pagetitle', 'Transactions');
        $this->app->set('subtitle', '');
		
        $this->app->set('transaction', $this->getItem());
        
        
        echo  $this->theme->render('Finances/Site/Views::transactions/details.php');
    }
    
    protected function getProject() {
    	return array(array('value' => 'funds.trees', 'text' => 'Trees' ), array('value' => 'funds.parks', 'text' => 'Parks' ) , array('value' => 'funds.suburbs', 'text' => 'Suburbs' ), array('value' => 'funds.education', 'text' => 'Education' ), array('value' => 'funds.costs', 'text' => 'Costs' ));
    }
    
    protected function displayCreate()
    {
    	$item = $this->getItem();
    	
    	$user = $this->getIdentity();
    	
    	

    	
    	$projects = $this->getProject();
    	$this->app->set('projects', $projects );
    	$this->app->set('selected', 'null' );
    
    	
    	
    	$this->app->set('meta.title', 'Create Transaction | Finances');
    
    	$view = \Dsc\System::instance()->get('theme');
    	$view->event = $view->trigger( 'onDisplayFiancesTransactionEdit', array( 'item' => $item, 'tabs' => array(), 'content' => array() ) );
    	echo $view->render('Finances/Site/Views::transactions/create.php');
    }
    
    
    
    protected function displayEdit()
    {
    	$item = $this->getItem();
    
    	$projects = $this->getProject();
    	$this->app->set('projects', $projects );
    	$this->app->set('selected', 'null' );
    
    	
    	$this->app->set('meta.title', 'Edit Transaction | Finances');
    
    	$view = \Dsc\System::instance()->get('theme');
    	$view->event = $view->trigger( 'onDisplayFiancesTransactionEdit', array( 'item' => $item, 'tabs' => array(), 'content' => array() ) );
    
    	echo $view->render('Finances/Site/Views::transactions/edit.php');
    }
    
    protected function displayRead()
    {
    	$item = $this->getItem();
    
    	$model = $this->getModel('categories');
    	$categories = $model->getList();
    	$this->app->set('categories', $categories );
    	$this->app->set('selected', 'null' );
    
    	$all_tags = $this->getModel()->getTags();
    	$this->app->set('all_tags', $all_tags );
    	$this->app->set( 'authors', $this->getListAuthors() );
    	$this->app->set( 'allow_preview', $this->canPreview( true ) );
    
    	$this->app->set('meta.title', 'Edit Post | Blog');
    
    	$view = \Dsc\System::instance()->get('theme');
    	$view->event = $view->trigger( 'onDisplayBlogPostEdit', array( 'item' => $item, 'tabs' => array(), 'content' => array() ) );
    
    	echo $view->render('Blog/Admin/Views::posts/edit.php');
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
