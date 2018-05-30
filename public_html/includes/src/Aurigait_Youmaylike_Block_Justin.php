<?php 
class Aurigait_Youmaylike_Block_Justin extends Mage_Core_Block_Template
{
    
    public function getProducts()
    {
        $category_id=$this->getCategoryId();
        $category = Mage::getModel('catalog/category')->load($category_id);
        //$collection = $category->getProductCollection();
        $collection = $category->getProductCollection()->addAttributeToSort('position', 'ASC');
		Mage::getModel('catalog/layer')->prepareProductCollection($collection);
        $collection->getSelect()->limit(6);
        return $collection;
    }
}
?>
