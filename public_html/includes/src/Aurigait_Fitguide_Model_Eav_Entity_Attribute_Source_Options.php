<?php
class Aurigait_Fitguide_Model_Eav_Entity_Attribute_Source_Options extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            
            $options=Mage::getModel('fitguide/fitguide')->getCollection()->load();
            $arr[]=array("label"=>"Please Select a Fit Guide","value"=>'');
            
            foreach($options as $option)
            {
                if($option->getStatus()==0)
                {
                    $arr[]=array("label"=>$option->getTitle(),"value"=>$option->getId());
                }
            }
            $this->_options = $arr;
        }
        return $this->_options;
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        $_options = array();
        foreach ($this->getAllOptions() as $option) {
            $_options[$option["value"]] = $option["label"];
        }
        return $_options;
    }
}
?>