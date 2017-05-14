<?php 
class Aurigait_Homepage_Block_Homepage extends Mage_Core_Block_Template
{
    public function getRows()
    {
        $row=Mage::getModel('homepage/rows')->getCollection();
        $row->addFieldToFilter('status','1');
        $row->setOrder('sort_order', 'ASC');
        $store_id = Mage::app()->getStore()->getStoreId();
        $row->getSelect()->where("FIND_IN_SET(".$store_id.",main_table.store_id) or FIND_IN_SET(0,main_table.store_id)");
        return $row;
    }
    public function getRowImages($rowId)
    {
        $sql="select * from homepage_row_images where row_id='".$rowId."' order by sort_order asc";
        $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
        return $data;
    }
}