<?php 
namespace Finances\Models;
use Money\Money;
/**

 * 
 *
 */
class Dashboard extends \Dsc\Mongo\Collection 
{
    public $title = null;          // e.g. Alternative Title for the product when this evariant has been selected
    public $name = null;   
    public $fundsavailable = null; //in cents
    public $dateofcharge = null;
    public $type = null; //in cents

    protected $__collection_name = 'finances.dashboard';
    protected $__type = 'finances.dashboard';
    
    
    public function deposit($amount) {
    	$originalfunds = Money::USD((int)$this->fundsavailable);
    	$addedFunds = Money::USD((int)$amount);
    	$fundsavailable = $originalfunds->add($addedFunds);
    	
    	$this->fundsavailable = $fundsavailable->getAmount();
    	$this->save();
    }
    public function widthdrawl($amount) {
    	$originalfunds = Money::USD((int)$this->fundsavailable);
    	$addedFunds = Money::USD((int)$amount);
    	$fundsavailable = $originalfunds->subtract($addedFunds); 
    	$this->fundsavailable = $fundsavailable->getAmount();
    	$this->save();
    }
    
    public function getAvailableFunds() {
    	return '$'.number_format(( $this->fundsavailable/100),2);
    }
    
    
    
    public function calculateFundsAvailable() {
    	
    }

}