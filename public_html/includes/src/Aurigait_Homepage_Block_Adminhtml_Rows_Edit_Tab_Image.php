<?php
class Aurigait_Homepage_Block_Adminhtml_Rows_Edit_Tab_Image extends Mage_Adminhtml_Block_Widget_Form
{
	
	public function getImageData()
	{
		$data='';
		if($this->getRequest()->getParam("id")){
			$sql="select * from homepage_row_images where row_id=".$this->getRequest()->getParam("id");
			$reader = Mage::getSingleton("core/resource")->getConnection("core_write");
			$data=$reader->fetchAll($sql);
		}
		return $data;
	}
    
}
?>