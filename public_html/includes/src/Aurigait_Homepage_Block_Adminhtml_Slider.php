<?php


class Aurigait_Homepage_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_slider";
	$this->_blockGroup = "homepage";
	$this->_headerText = Mage::helper("homepage")->__("Slider Manager");
	$this->_addButtonLabel = Mage::helper("homepage")->__("Add New Item");
	parent::__construct();
	
	}

}