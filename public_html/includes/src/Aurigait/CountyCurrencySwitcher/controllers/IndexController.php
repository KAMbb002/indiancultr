<?php
class Aurigait_CountyCurrencySwitcher_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {

    	$this->loadLayout();   
		$this->getLayout()->getBlock("head")->setTitle($this->__("Currency Switcher Home Page"));
	 	$this->renderLayout(); 
	 }
	 
    public function saveAction(){
	
		$data = $this->getRequest()->getPost();
		if(!empty($data))
		{
			try
			{
				if (@$data['country'] && $data['country']=='IN')
				{
					setcookie('webCode','IN',time()+60*60*24*30,'/','.indiancultr.com');
					//Mage::getModel('core/cookie')->set('webCode', 'IN',time() + (86400 * 7));
					$current_store='india';
				}
				else
				{
					setcookie('webCode','US',time()+60*60*24*30,'/','.indiancultr.com');
					//Mage::getModel('core/cookie')->set('webCode', 'US',time() + (86400 * 7));
					$current_store='global';
				}		    
			   
			}
			catch (Exception $e) {
						$msg = "An internal error occurred";
						Mage::getSingleton('core/session')->addError($msg);
			}
		}
	    $this->_redirectReferer();
	}
    
    
}
