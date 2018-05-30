<?php

class Aurigait_Fitguide_Block_Adminhtml_Fitguide_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("fitguideGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("fitguide/fitguide")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("fitguide")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
				$this->addColumn("image", array(
				"header" => Mage::helper("fitguide")->__("Guide Icon"),
				"align" =>"right",
				"width" => "50px",
				"type" => "image",
				"index" => "image",
				));
                
				$this->addColumn("title", array(
				"header" => Mage::helper("fitguide")->__("Guide Title"),
				"index" => "title",
				));
						$this->addColumn('status', array(
						'header' => Mage::helper('fitguide')->__('Status'),
						'index' => 'status',
						'type' => 'options',
						'options'=>Aurigait_Fitguide_Block_Adminhtml_Fitguide_Grid::getOptionArray3(),				
						));
						
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_fitguide', array(
					 'label'=> Mage::helper('fitguide')->__('Remove Fitguide'),
					 'url'  => $this->getUrl('*/adminhtml_fitguide/massRemove'),
					 'confirm' => Mage::helper('fitguide')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray3()
		{
            $data_array=array(); 
			$data_array[0]='Enabled';
			$data_array[1]='Disabled';
            return($data_array);
		}
		static public function getValueArray3()
		{
            $data_array=array();
			foreach(Aurigait_Fitguide_Block_Adminhtml_Fitguide_Grid::getOptionArray3() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}