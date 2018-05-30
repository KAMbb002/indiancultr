<?php


class Aurigait_Fitguide_Block_Adminhtml_Fitguide extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_fitguide";
	$this->_blockGroup = "fitguide";
	$this->_headerText = Mage::helper("fitguide")->__("Fitguide Manager");
	$this->_addButtonLabel = Mage::helper("fitguide")->__("Add New Item");
	parent::__construct();
	
	}

}