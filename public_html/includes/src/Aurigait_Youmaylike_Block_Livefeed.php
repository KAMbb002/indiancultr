<?php 
class Aurigait_Youmaylike_Block_Livefeed extends Mage_Core_Block_Template
{
    public function getProducts()
    {
        $category_id=$this->getCategoryId();
        $category = Mage::getModel('catalog/category')->load($category_id);
        $collection = $category->getProductCollection();
        Mage::getModel('catalog/layer')->prepareProductCollection($collection);
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
        $collection->getSelect()->limit(6);
        return $collection;
    }
}

?>
