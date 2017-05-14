<?php 
class Aurigait_Youmaylike_Block_Alsolike extends Mage_Core_Block_Template
{

    function getProducts($cat_id,$product_id)
    {
       
        $category = new Mage_Catalog_Model_Category();
        $category->load($cat_id);
        $collection = $category->getProductCollection();
        Mage::getModel('catalog/layer')->prepareProductCollection($collection);
        $collection->getSelect()->where('e.entity_id!='.$product_id);
        $collection->getSelect()->order('rand()');
        $numProducts = Mage::getStoreConfig('catalog/frontend/limit');
        $collection->setPage(1, $numProducts);
        $collection->load();
        return $collection;
    }
}



?>
