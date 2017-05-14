<?php
class Aurigait_Homepage_Block_Adminhtml_Slider_Renderer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        
        $data=$row->getData();
        $html="<img src='".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'homepage/slider/'.$data['image']."' width='120' />";
        return $html;
    }
}
?>