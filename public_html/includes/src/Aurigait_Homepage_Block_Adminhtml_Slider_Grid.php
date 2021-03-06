<?php

class Aurigait_Homepage_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("sliderGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("homepage/slider")->getCollection();
				
				
				$this->setCollection($collection);
				 parent::_prepareCollection();
				 foreach($collection as $link){
						
				       if($link->getStoreId()){
				           $link->setStoreId(explode(',',$link->getStoreId()));
				       }
				       else{
				           $link->setStoreId(array('0'));
				        }
				}
				 return $this;
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("homepage")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("title", array(
				"header" => Mage::helper("homepage")->__("Title"),
				"index" => "title",
				));
				$this->addColumn("image", array(
				"header" => Mage::helper("homepage")->__("Image"),
				"align" =>"right",
				"width" => "120px",
				"type" => "image",
				"index" => "image",
				"renderer" => 'homepage/adminhtml_slider_renderer'
				));
				$this->addColumn("url", array(
				"header" => Mage::helper("homepage")->__("Image Url"),
				"index" => "url",
				));
				if (!Mage::app()->isSingleStoreMode()) {
				$this->addColumn('store_id', array(
				    'header'        => Mage::helper('homepage')->__('Store View'),
				    'index'         => 'store_id',
				    'type'          => 'store',
				    'store_all'     => true,
				    'store_view'    => true,
				    'sortable'      => true,
				    'filter_condition_callback' => array($this,
					'_filterStoreCondition'),
				));
			        }
				
				$this->addColumn("sort_order", array(
				"header" => Mage::helper("homepage")->__("Position"),
				"index" => "sort_order",
				));
				$this->addColumn('status', array(
				'header' => Mage::helper('homepage')->__('Status'),
				'index' => 'status',
				'type' => 'options',
				'options'=>Aurigait_Homepage_Block_Adminhtml_Slider_Grid::getOptionArray5(),				
				));
				$this->addColumn('action',
								array(
								    'header'    => Mage::helper('homepage')->__('Action'),
								    'width'     => '50px',
								    'type'      => 'action',
								    'getter'     => 'getId',
								    'actions'   => array(
									array(
									    'caption' => Mage::helper('homepage')->__('Edit'),
									    'id' => "editlink",
									    'url'     => array(
										'base'=>'*/*/edit',
										'params'=>array('store'=>$this->getRequest()->getParam('store'))
									    ),
									    'field'   => 'id'
									)
								    ),
								    'filter'    => false,
								    'sortable'  => false,
								    'index'     => 'stores',
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
			$this->getMassactionBlock()->addItem('remove_slider', array(
					 'label'=> Mage::helper('homepage')->__('Remove Slider'),
					 'url'  => $this->getUrl('*/adminhtml_slider/massRemove'),
					 'confirm' => Mage::helper('homepage')->__('Are you sure?')
				));
			$this->getMassactionBlock()->addItem('disable_slider', array(
					 'label'=> Mage::helper('homepage')->__('Change Status'),
					 'url'  => $this->getUrl('*/adminhtml_slider/massStatus'),
					  'additional' => array(
								'visibility' => array(
								     'name' => 'massStatus',
								     'type' => 'select',
								     'class' => 'required-entry',
								     'label' => Mage::helper('homepage')->__('Status'),
								     'values' => Aurigait_Homepage_Block_Adminhtml_Slider_Grid::getOptionArray5()
								 )
							 )
				));
			return $this;
		}
			
		static public function getOptionArray5()
		{
            $data_array=array(); 
			$data_array[0]='Enabled';
			$data_array[1]='Disabled';
            return($data_array);
		}
		static public function getValueArray5()
		{
            $data_array=array();
			foreach(Aurigait_Homepage_Block_Adminhtml_Slider_Grid::getOptionArray5() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		protected function _filterStoreCondition($collection, $column){
				if (!$value = $column->getFilter()->getValue()) {
				    return;
				}
				
			$this->getCollection()->addFieldToFilter('store_id',array(
										  array('like'=>'%,'.$value),
										  array('like'=>'%,'.$value.',%'),
										  array('like'=>$value.',%')
										  )
								 );
			
			
	        }
		

}