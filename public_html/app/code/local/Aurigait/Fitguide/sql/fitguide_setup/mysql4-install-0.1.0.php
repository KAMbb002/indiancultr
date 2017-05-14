<?php
$installer = $this;
$installer->startSetup();

/*$installer->addAttribute("catalog_product", "fitguide",  array(
   "type"     => "int",
   "backend"  => "",
   "frontend" => "",
   "label"    => "Fit Guide",
   "input"    => "select",
   "class"    => "",
   "source"   => "fitguide/eav_entity_attribute_source_options",
   "global"   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
   "visible"  => true,
   "required" => false,
   "is_user_defined"  => true,
   "default" => "0",
   "searchable" => false,
   "filterable" => false,
   "comparable" => false,
   "used_for_sort_by" => true,
   "visible_on_front"  => false,
   "unique"     => false,
   "system" => true,
   "note"       => ""

       ));*/


$sql=<<<SQLTEXT
CREATE TABLE IF NOT EXISTS `auriga_fitguide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` int(1),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 