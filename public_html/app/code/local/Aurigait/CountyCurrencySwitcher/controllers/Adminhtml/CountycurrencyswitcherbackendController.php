<?php
class Aurigait_CountyCurrencySwitcher_Adminhtml_CountycurrencyswitcherbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Backend Page Title"));
	   $this->renderLayout();
    }
}