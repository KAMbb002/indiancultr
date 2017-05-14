<?php
class Aurigait_Fitguide_Block_Adminhtml_Fitguide_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("fitguide_form", array("legend"=>Mage::helper("fitguide")->__("Item information")));

								
						$fieldset->addField('image', 'image', array(
						'label' => Mage::helper('fitguide')->__('Icon Image'),
						'name' => 'image',
						'note' => '(*.jpg, *.png, *.gif)',
						));
						$fieldset->addField("title", "text", array(
						"label" => Mage::helper("fitguide")->__("Guide Title"),
						"name" => "title",
						));
					
						$fieldset->addField("content", "textarea", array(
						"label" => Mage::helper("fitguide")->__("Guide Content"),
						"name" => "content",
						));
									
						 $fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('fitguide')->__('Status'),
						'values'   => Aurigait_Fitguide_Block_Adminhtml_Fitguide_Grid::getValueArray3(),
						'name' => 'status',
						));

				if (Mage::getSingleton("adminhtml/session")->getFitguideData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getFitguideData());
					Mage::getSingleton("adminhtml/session")->setFitguideData(null);
				} 
				elseif(Mage::registry("fitguide_data")) {
				    $form->setValues(Mage::registry("fitguide_data")->getData());
				}
				return parent::_prepareForm();
		}
}
