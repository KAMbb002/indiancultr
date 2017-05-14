<?php
require_once(__DIR__.'/../app/Mage.php');
umask(0);
Mage::app('admin');
		
$from_days=Mage::getStoreConfig('catalog/prosetting/from_days');
$from_date=date('Y-m-d');

$to_days=7;
$to_date=date('Y-m-d',strtotime('-'.$to_days.' days'));

$NewJewelcat_id=16;
$jewelCatId=3;
$readConnection = Mage::getSingleton('core/resource')->getConnection('core_read');
if($NewJewelcat_id)
{
	 $writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
     
	 $delQuery = 'Delete from iccatalog_category_product where category_id ='.$NewJewelcat_id;
     $writeConnection->query($delQuery);

	$category = Mage::getModel('catalog/category')->load($NewJewelcat_id);
	$products = array();
    $category_products='';
	
    
    $sql="select product_id
    from iccatalog_category_product_index c
    where c.category_id=".$jewelCatId." group by product_id";
    $products=$readConnection->fetchAll($sql);
	foreach($products as $product)
	{
           if(!$category_products){
                $category_products=$product['product_id'].'=1&';
            }else{
                $category_products .=$product['product_id'].'=1&';            
            }        
		
	}
	 parse_str($category_products, $products);    
        $category->setPostedProducts($products)->save();
	
}


$NewClothin_id=17;
$clothingCatId=19;
if($NewClothin_id)
{
	 $writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
	 $delQuery = 'Delete from iccatalog_category_product where category_id ='.$NewClothin_id;
     $writeConnection->query($delQuery);

	$category = Mage::getModel('catalog/category')->load($NewClothin_id);
	$products = array();
    $category_products='';
	
	$sql="select product_id
    from iccatalog_category_product_index c
    where c.category_id=".$clothingCatId." group by product_id";
    $products=$readConnection->fetchAll($sql);
    
	foreach($products as $product)
	{
           if(!$category_products){
                $category_products=$product['product_id'].'=1&';
            }else{
                $category_products .=$product['product_id'].'=1&';            
            }        
		
	}
	 parse_str($category_products, $products);    
        $category->setPostedProducts($products)->save();
	
}

$NewAccess_id=18;
$accessCatId=21;
if($NewAccess_id)
{
	 $writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
	 $delQuery = 'Delete from iccatalog_category_product where category_id ='.$NewAccess_id;
     $writeConnection->query($delQuery);

	$category = Mage::getModel('catalog/category')->load($NewAccess_id);
	$products = array();
    $category_products='';
	
	$sql="select product_id
    from iccatalog_category_product_index c
    where c.category_id=".$accessCatId." group by product_id";
    $products=$readConnection->fetchAll($sql);
    
	foreach($products as $product)
	{
           if(!$category_products){
                $category_products=$product['product_id'].'=1&';
            }else{
                $category_products .=$product['product_id'].'=1&';            
            }        
		
	}
	 parse_str($category_products, $products);    
        $category->setPostedProducts($products)->save();
	
}

$process = Mage::getModel('index/indexer')->getProcessByCode('catalog_category_product');
$process->reindexAll();
echo 'new arrival updated';die;

?>
