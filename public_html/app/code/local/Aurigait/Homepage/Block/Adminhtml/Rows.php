<?php


class Aurigait_Homepage_Block_Adminhtml_Rows extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_rows";
	$this->_blockGroup = "homepage";
	$this->_headerText = Mage::helper("homepage")->__("Rows Manager");
	$this->_addButtonLabel = Mage::helper("homepage")->__("Add New Item");
	parent::__construct();
	
	}

}