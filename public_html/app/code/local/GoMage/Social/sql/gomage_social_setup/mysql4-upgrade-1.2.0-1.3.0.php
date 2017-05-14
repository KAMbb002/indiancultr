<?php
$installer = $this;
$installer->startSetup();

/*
$installer->addAttribute("customer", "source",  array(
    "type"     => "int",
    "backend"  => "",
    "label"    => "Registration Source",
    "input"    => "select",
    "source"   => "gomage_social/eav_entity_attribute_source_sourceoption",
    "visible"  => true,
    "required" => false,
    "default" => "Direct",
    "frontend" => "",
    "unique"     => false,
    "note"       => ""

	));

        $attribute   = Mage::getSingleton("eav/config")->getAttribute("customer", "source");

        
$used_in_forms=array();

$used_in_forms[]="adminhtml_customer";
$used_in_forms[]="customer_account_edit";
        $attribute->setData("used_in_forms", $used_in_forms)
		->setData("is_used_for_customer_segment", true)
		->setData("is_system", 0)
		->setData("is_user_defined", 1)
		->setData("is_visible", 1)
		->setData("sort_order", 800)
		;
        $attribute->save();
	
	
	*/
$installer->endSetup();
	 