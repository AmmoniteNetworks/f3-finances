<?php 
namespace Finances\Models;
use Money\Money as Money;
/**
 * UNFINISHED Class ultimately intended to simplify Variant management 
 * so it doesn't all have to go through the Products model
 * 
 * @author Rafael Diaz-Tushman
 *
 */
class Transactions extends \Dsc\Mongo\Collection 
{
	
	
	
    public $title = null;          // e.g. Alternative Title for the product when this evariant has been selected
    public $created = null;
    public $dateofcharge = null;
    public $amount = null; //in cents
    public $actualamount = null; //amount after fees in cents
    public $description = null;
    public $type = null; //in cents
    public $__dashboard = 'stripe';
    public $__currency = 'USD';
    protected $__collection_name = 'finances.transactions';
    protected $__type = 'finances.transactions';
    
 
    
    public function deposit() {
    	$dash = $this->getDash();
    	$dash->deposit($this->actualamount);
    	$this->save();
    	
    }
    
    public function widthdrawl() {
    	$dash = $this->getDash();
    	$dash->widthdrawl($this->actualamount);
    	$this->save();
    }
    
    public function getDash() {
    	return (New \Finances\Models\Dashboard)->setCondition('type',$this->__dashboard)->getItem();
  
    }
    public function getDesc() {
    	return $this->description;
    }
    
    public function makeDesc($stripeEvent) {
    	//You have a stripe object lets find about more about this charge and convert 
    	
    	return 'Description PLaceholder';
    }
    //TODO these might not be needed once we clean up values in mongo this could just be a simple return
    public function getAmount() {
    	
    	return Money::USD((int) $this->amount)->getAmount();
    	
    }
    public function getActualamount() {
    	return Money::USD((int) $this->actualamount)->getAmount();
    	
    }
    
    
    public function chargeFee( $amount, $percentage = 0.029, $flat = 30) {
    	$amount = Money::USD($amount);
    	$flatfee = Money::USD($flat);
    	$fee = $amount->multiply($percentage);
    	$newAmount = $amount->subtract($fee);
    	$newAmount =  $newAmount->subtract($flatfee);
    	return $newAmount->getAmount();
    } 

}