<?php
class Aurigait_CountyCurrencySwitcher_Model_Eav_Entity_Attribute_Source_Options extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            
            $data=Mage::getResourceModel('directory/country_collection')
        	->loadData()
        	->toOptionArray(false);
            $arr[]=array("label"=>"Please Select Countries not to Ship","value"=>'');
            foreach($data as $option)
            {
                $arr[]=array("label"=>$option["label"],"value"=>$option["value"]);
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
        $data=Mage::getResourceModel('directory/country_collection')
        ->loadData()
        ->toOptionArray(false);
        foreach ($data as $option) {
            $_options[$option["value"]] = $option["label"];
        }
        return $_options;
    }
}
?>