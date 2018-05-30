<?php
class Aurigait_Homepage_Block_Adminhtml_Rows_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("homepage_form", array("legend"=>Mage::helper("homepage")->__("Item information")));

				
						$fieldset->addField("title", "text", array(
						"label" => Mage::helper("homepage")->__("Title"),
						"name" => "title",
						));
					
						$fieldset->addField("categoryName", "text", array(
						"label" => Mage::helper("homepage")->__("Heading"),
						"name" => "categoryName",
						));

						$fieldset->addField('categoryImage', 'image', array(
						'label' => Mage::helper('homepage')->__('Browse'),
						"class" => "required-entry",
						'name' => 'categoryImage',
						'note' => '(*.jpg, *.png, *.gif)',
						));						
						
						$fieldset->addField("viewAllLink", "text", array(
						"label" => Mage::helper("homepage")->__("View All Link"),
						"name" => "viewAllLink",
						));
						
						$fieldset->addField("sort_order", "text", array(
						"label" => Mage::helper("homepage")->__("Position"),
						"name" => "sort_order",
						));
						if (!Mage::app()->isSingleStoreMode()) {
						   $fieldset->addField('store_id', 'multiselect', array(
						    'name' => 'stores[]',
						    'label' => Mage::helper('homepage')->__('Store View'),
						    'title' => Mage::helper('homepage')->__('Store View'),
						    'required' => true,
						    'values' => Mage::getSingleton('adminhtml/system_store')
								 ->getStoreValuesForForm(false, true)
						    ));
						 }
						else {
						    $fieldset->addField('store_id', 'hidden', array(
							'name' => 'stores[]',
							'value' => Mage::app()->getStore(true)->getId()
						    ));
						}			
						 $fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('homepage')->__('Status'),
						'values'   => Aurigait_Homepage_Block_Adminhtml_Rows_Grid::getValueArray7(),
						'name' => 'status',
						));

				if (Mage::getSingleton("adminhtml/session")->getRowsData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getRowsData());
					Mage::getSingleton("adminhtml/session")->setRowsData(null);
				} 
				elseif(Mage::registry("rows_data")) {
				    $form->setValues(Mage::registry("rows_data")->getData());
				}
				return parent::_prepareForm();
		}
}
