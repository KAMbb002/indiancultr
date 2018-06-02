<?php 	
class Tilkor_ProductOptions_Model_Observer 
{	
	public function afterAddToCart($observer) {
		
		$data = Mage::app()->getRequest()->getParams();
		$bust_chest = $data[bust_chest];		
		$waist = $data[waist];		
		$sleeve_length = $data[sleeve_length];		
		$shoulders = $data[shoulders];		
		$armhole = $data[armhole];		
		$neck = $data[neck];		
		$length = $data[length];		
		
		$itemsCollection = Mage::getSingleton('checkout/cart')->getQuote()->getItemsCollection();		
		$firstItem = $itemsCollection->getFirstItem();
		
		$quote_id = $firstItem->getQuoteId();		
		$product_id = Mage::getSingleton('checkout/session')->getLastAddedProductId(true);
			
		
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$writeConnection = $resource->getConnection('core_write');
		$tableName = $resource->getTableName('icsales_flat_quote_item');
		
		if($product_id != ''){			
			$select = "SELECT item_id FROM icsales_flat_quote_item WHERE quote_id = $quote_id AND product_id = $product_id";
			$item_id = $readConnection->fetchOne($select);
			if($item_id != ''){
				$query = "UPDATE $tableName SET custom_measurement = 'Bust/Chest: $bust_chest, Waist: $waist, Sleeve Length: $sleeve_length, Shoulders: $shoulders, Armhole: $armhole, Neck: $neck, Length: $length' WHERE item_id = $item_id AND quote_id = $quote_id";
				$res = $writeConnection->query($query);
			}
		}
	}
	
	public function afterOrderSuccess($observer){	
		
		$order_id = $observer->getEvent()->getOrder()->getId();		
		$order = Mage::getModel("sales/order")->load($order_id);		

		// Custom code to set product option field value in icsales_flat_order_item table
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$writeConnection = $resource->getConnection('core_write');
		$tableName1 = $resource->getTableName('icsales_flat_order_item');
		
		foreach($order->getAllItems() as $item1) {
			$quoteItemId = $item1->getQuoteItemId();
			$select = "SELECT custom_measurement FROM icsales_flat_quote_item WHERE item_id = $quoteItemId";
			
			$custom_measurement = $readConnection->fetchOne($select);
			
			$query1 = "UPDATE $tableName1 SET custom_measurement = '$custom_measurement' WHERE quote_item_id = $quoteItemId";
			$res1 = $writeConnection->query($query1);			
		}
		
	}
	
}