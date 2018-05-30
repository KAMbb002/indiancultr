<?php
	
class Aurigait_Homepage_Block_Adminhtml_Rows_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "homepage";
				$this->_controller = "adminhtml_rows";
				$this->_updateButton("save", "label", Mage::helper("homepage")->__("Save Row"));
				$this->_updateButton("delete", "label", Mage::helper("homepage")->__("Delete Row"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("homepage")->__("Save And Continue Edit"),
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
				if( Mage::registry("rows_data") && Mage::registry("rows_data")->getId() ){

				    return Mage::helper("homepage")->__("Edit Row '%s'", $this->htmlEscape(Mage::registry("rows_data")->getId()));

				} 
				else{

				     return Mage::helper("homepage")->__("Add Row");

				}
		}
}