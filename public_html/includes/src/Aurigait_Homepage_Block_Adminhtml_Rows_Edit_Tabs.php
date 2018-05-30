<?php
class Aurigait_Homepage_Block_Adminhtml_Rows_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("rows_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("homepage")->__("Row Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("homepage")->__("Row Information"),
				"title" => Mage::helper("homepage")->__("Row Information"),
				"content" => $this->getLayout()->createBlock("homepage/adminhtml_rows_edit_tab_form")->toHtml(),
				));
				$this->addTab("image_section", array(
				"label" => Mage::helper("homepage")->__("Images"),
				"title" => Mage::helper("homepage")->__("Images"),
				"content" => $this->getLayout()->createBlock("homepage/adminhtml_rows_edit_tab_image")->setTemplate('homepage/images.phtml')->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
