<?php
class Aurigait_Homepage_Block_Adminhtml_Slider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $id = $this->getRequest()->getParam('id');
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("homepage_form", array("legend"=>Mage::helper("homepage")->__("Item information")));


        $fieldset->addField("title", "text", array(
        "label" => Mage::helper("homepage")->__("Title"),
        "name" => "title",
        ));

        $fieldset->addField('image', 'image', array(
        'label' => Mage::helper('homepage')->__('Image'),
        'name' => 'image',
        'note' => '(*.jpg, *.png, *.gif)',
        ));
        $fieldset->addField("url", "text", array(
        "label" => Mage::helper("homepage")->__("Image Url"),
        "name" => "url",
        ));
        if($id){
            $fieldset->addField("is_append_base_url", "checkbox", array(
            "label" => Mage::helper("homepage")->__("Is Append Base Url?"),
            "name" => "is_append_base_url",
            'value' => 1,
            'checked' => ($this->getIsAppendBaseUrl($id) == 1) ? 'true' : '',
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'disabled' => false,
            'readonly' => false,
            ));
        }else{
            $fieldset->addField("is_append_base_url", "checkbox", array(
            "label" => Mage::helper("homepage")->__("Is Append Base Url?"),
            "name" => "is_append_base_url",
            'value' => 1,
            'checked' => 'true',
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'disabled' => false,
            'readonly' => false,
            ));
        }
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

        $fieldset->addField("sort_order", "text", array(
        "label" => Mage::helper("homepage")->__("Position"),
        "name" => "sort_order",
        ));

         $fieldset->addField('status', 'select', array(
        'label'     => Mage::helper('homepage')->__('Status'),
        'values'   => Aurigait_Homepage_Block_Adminhtml_Slider_Grid::getValueArray5(),
        'name' => 'status',
        ));

        if (Mage::getSingleton("adminhtml/session")->getSliderData())
        {
                $form->setValues(Mage::getSingleton("adminhtml/session")->getSliderData());
                Mage::getSingleton("adminhtml/session")->setSliderData(null);
        } 
        elseif(Mage::registry("slider_data")) {
            $form->setValues(Mage::registry("slider_data")->getData());
        }
        return parent::_prepareForm();
    }
    private function getIsAppendBaseUrl($id){
        $model = Mage::getModel('homepage/slider')->load($id);
        return $model->getIsAppendBaseUrl();
    }
}
