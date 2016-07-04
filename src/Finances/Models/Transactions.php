<?php 
namespace Finances\Models;
use Money\Money as Money;

/**
 * Transactions Class
 * 
 * @author Chris French
 *
 */

class Transactions extends \Dsc\Mongo\Collection 
{
		
    public $title = null;          // e.g. Alternative Title for the product when this evariant has been selected
    public $created = null;
    public $amount = null; //in cents
    public $actualamount = null; //amount after fees in cents
    public $description = null;
    public $type = null; //in cents
    public $user = array();
    public $__dashboard = 'stripe';
    public $__currency = 'USD';
    protected $__collection_name = 'finances.transactions';
    protected $__type = 'finances.transactions';
    
    
    protected function fetchConditions()
    {
    	parent::fetchConditions();
    
    	if($this->getState('filter.amountpositive')){
    		$this->setCondition('amount', array('$gt' => 0 ));
    		
    	}
    	
    	
    	
    	
    	return $this;
    }
    
 
    
    public function deposit() {
    //	$dash = $this->getDash();
    //	$dash->deposit($this->actualamount);
    	$this->save();
    	
    }
    
    public function widthdrawl() {
    //	$dash = $this->getDash();
    //	$dash->widthdrawl($this->actualamount);
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

    	switch ($this->type) {
    		case "charge":
    		case "deposit":
    			return Money::USD((int) $this->actualamount)->getAmount();
    			break;
    		case "expense":
    			return 'N/A';
    			break;
    		
    		default:
    			return 'N/A';
    	}
    	
    	
    }
    
   
    
   	protected function beforeValidate()
   	{
   		return parent::beforeValidate();
   	}
   	
   	protected function beforeSave()
   	{
   		return parent::beforeSave();
   	}
   	
   	protected function beforeCreate()
   	{	
   		if(empty($this->user))  {
   			//Lets try to find a user with this customer id
   			$user = (new \Users\Models\Users)->setCondition('stripe.customer.id', $this->customer)->getItem();
   			if(!empty($user->id)) {
   				$this->set('user', array('id' => $user->id, 'name' => $user->fullName()));
   			}
   		}
   		
   		if($this->type == 'expense') {
   			
   			$available = \Finances\Models\Funds::getAvailableFunds($this->project);
   			
   			if($this->amount >= $available['amount']) {
   				
   				die('NOT ENOUGH FUNDS');
   				
   			} 
   			
   			
   			
   		}
   		
   		

   		return parent::beforeCreate();
   	}
   	
   	protected function beforeUpdate()
   	{
   		return parent::beforeUpdate();
   	}
   	
   	protected function beforeDelete()
   	{
   		return parent::beforeDelete();
   	}
   	
   	protected function afterSave()
   	{
   		return parent::afterSave();
   	}
   	
   	protected function afterCreate()
   	{	
   		if($this->paid == 'true' && $this->type == 'deposit' || $this->paid == 'true' && $this->type == 'Donation Deposit') {
   			$this->allocateFunds();
   		}
   		
   		
   		/*
   		 * This is goind to loop through this transaction and spend the money sending emails and such
   		 */
   		
   		if($this->type == 'expense') {
   			//trying to create an expense lets check for funds to see if we have enough
   			$funds = (new \Finances\Models\Funds)->setCondition('type', $this->project)->setCondition('amount', array('$gt'=> 0))->getList();
   			$total = (int)$this->amount;
   			
   			//loops through funds spending them
   			foreach ($funds as $fund) {
   				$val = $fund->amount;
   				
   				
   				if((int)$total >=  (int)$val) {
   					

   					$fund->spend($this, $val);
   					
   					
   					
   					$total =  $total - $val;
   					
   					
   					
		   					if($total)  {
		   						continue;
		   					} else {
		   						 
		   						break;
		   					}
   			
   				} else {
   					//the value of this donation is higher than the remaining cost
   					
   					
   					$fund->spend($this, $total);
   					
   			
   			
   					break;
   				}
   				 
   			}
   			
   		}
   		
   		return parent::afterCreate();
   	}
   	
   	protected function afterUpdate()
   	{
   		return parent::afterUpdate();
   	}
   	
   	protected function afterDelete()
   	{
   		return parent::afterDelete();
   	}
   	
   	
   	public function allocateFunds( ) {
   		
   		$amount = Money::USD($this->actualamount);
   		
   		
   		if(!empty($this->allocation)) {
   			list($trees, $parks, $deserts, $education, $costs ) = $amount->allocate(array($this->{'allocation.trees'},$this->{'allocation.parks'},$this->{'allocation.deserts'},$this->{'allocation.education'},$this->{'allocation.costs'}));
   		} else {
   			list($trees, $parks, $deserts, $education, $costs ) = $amount->allocate(array(20,20,20,20,20));
   		}
   		
   		
   		
   		
   		
   		//put funds in each project 
   		//TODO abstract this
   		//TREES
   		$allocation = array();
   		$allocation['funds.trees'] = (new \Berms\Models\Trees\Funds)->add($trees->getAmount(), $this);
   		$allocation['funds.parks'] = (new \Berms\Models\Parks\Funds)->add($parks->getAmount(), $this);
   		$allocation['funds.deserts'] = (new \Berms\Models\Deserts\Funds)->add($suburbs->getAmount(), $this);
   		$allocation['funds.education'] = (new \Berms\Models\Education\Funds)->add($education->getAmount(), $this);
   		$allocation['funds.costs'] = (new \Berms\Models\Costs\Funds)->add($costs->getAmount(), $this);
   		
   		$this->set('allocation',$allocation)->set('allocated',time())->save();
   		
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