<?php
class Aurigait_Homepage_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getDealsSection()
	{
		$collection=Mage::getModel('deals/deals')->getCollection();
		$collection->addFieldToFilter('status','0');
		$store_id = Mage::app()->getStore()->getStoreId();
		$collection->getSelect()->where("FIND_IN_SET(".$store_id.",main_table.store_id) or FIND_IN_SET(0,main_table.store_id)");
		//$collection->getSelect()->where("main_table.store_id like '%,".$store_id.",%' or main_table.store_id like '%,".$store_id."' or main_table.store_id like '".$store_id.",%' or main_table.store_id like '".$store_id."'");
		$collection->getSelect()->order('sort_order','asc');
		return $collection;
	}
	public function getInSider()
	{
		$collection=Mage::getModel('exclusivelyinsider/exclusivelyinsider')->getCollection();
		$collection->addFieldToFilter('status','0');
		$store_id = Mage::app()->getStore()->getStoreId();
		$collection->getSelect()->where("FIND_IN_SET(".$store_id.",main_table.store_id) or FIND_IN_SET(0,main_table.store_id)");
		//$collection->getSelect()->where("main_table.store_id like '%,".$store_id.",%' or main_table.store_id like '%,".$store_id."' or main_table.store_id like '".$store_id.",%' or main_table.store_id like '".$store_id."'");
		return $collection;
	}
	public function getRowImages($rowId)
    {
        $sql="select * from homepage_row_images where row_id='".$rowId."' order by sort_order asc";
        $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
        return $data;
    }
	public function getRowData($rowId)
	{
		if($rowId)
		{
			$sql="select * from homepage_rows where id=".$rowId." and status=1";
			$data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchRow($sql);
	        return $data;
		}
		else
		{
			return false;
		}
	}
	public function getFloors()
	{
		$store_id = Mage::app()->getStore()->getStoreId();
		$sql="select flr.name,flr.class_name,row.* from floor_widget flr
		left join homepage_rows row on find_in_set(row.id,flr.floorrows)
		where find_in_set(".$store_id.",flr.store_id) or FIND_IN_SET(0,flr.store_id)
		 order by flr.id asc";
		$data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
	    return $data;
	}
	public function getFloorData($ids)
	{
		$sql="select row.title,row.categoryImage,row.viewAllLink,image.*
		from homepage_rows row
		left join homepage_row_images image on image.row_id=row.id
		where row.id in ($ids)";
		$data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
	    return $data;
	}
}
	 