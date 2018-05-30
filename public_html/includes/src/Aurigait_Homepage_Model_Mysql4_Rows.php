<?php
class Aurigait_Homepage_Model_Mysql4_Rows extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("homepage/rows", "id");
    }
}