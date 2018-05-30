<?php
class Aurigait_Fitguide_Model_Mysql4_Fitguide extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("fitguide/fitguide", "id");
    }
}