<?php
	
class Aurigait_Fitguide_Block_Adminhtml_Fitguide_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "fitguide";
				$this->_controller = "adminhtml_fitguide";
				$this->_updateButton("save", "label", Mage::helper("fitguide")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("fitguide")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("fitguide")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("fitguide_data") && Mage::registry("fitguide_data")->getId() ){

				    return Mage::helper("fitguide")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("fitguide_data")->getId()));

				} 
				else{

				     return Mage::helper("fitguide")->__("Add Item");

				}
		}
}