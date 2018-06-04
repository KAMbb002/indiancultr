<?php 	
class Tilkor_ProductOptions_Model_Observer 
{	
	public function afterAddToCart($observer) {
		
		$data = Mage::app()->getRequest()->getParams();
		
		$bust_chest = $data[bust_chest] ? $data[bust_chest] : 'N/A';		
		$waist = $data[waist] ? $data[waist] : 'N/A';		
		$sleeve_length = $data[sleeve_length] ? $data[sleeve_length] : 'N/A';	
		$shoulders = $data[shoulders] ? $data[shoulders] : 'N/A';	
		$armhole = $data[armhole] ? $data[armhole] : 'N/A';		
		$neck = $data[neck] ? $data[neck] : 'N/A';
		$length = $data[length] ? $data[length] : 'N/A';	
		$hip = $data[hip] ? $data[hip] : 'N/A';	
		$length_shoulder_to_nee = $data[length_shoulder_to_nee] ? $data[length_shoulder_to_nee] : 'N/A';	
		$length_waist_to_ankle = $data[length_waist_to_ankle] ? $data[length_waist_to_ankle] : 'N/A';		
		
		$itemsCollection = Mage::getSingleton('checkout/cart')->getQuote()->getItemsCollection();		
		$firstItem = $itemsCollection->getFirstItem();
		
		$quote_id = $firstItem->getQuoteId();		
		$product_id = Mage::getSingleton('checkout/session')->getLastAddedProductId(true);
		
//Mage::log('<br>Your Data product_id :'.$product_id, Zend_Log::DEBUG, 'custom.log');
			
		
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$writeConnection = $resource->getConnection('core_write');
		
		if($product_id != ''){			
			$select = "SELECT item_id FROM icsales_flat_quote_item WHERE quote_id = $quote_id AND product_id = $product_id";
//Mage::log('<br>Your Data select query :'.$select, Zend_Log::DEBUG, 'custom.log');
			$item_id = $readConnection->fetchOne($select);
//Mage::log('<br>Your Data item_id :'.$item_id, Zend_Log::DEBUG, 'custom.log');
			if($item_id != ''){

				if(trim($data[bust_chest]) || trim($data[waist]) || trim($data[sleeve_length]) || trim($data[shoulders])){
					$query = "UPDATE icsales_flat_quote_item SET custom_measurement = '<br>Bust/Chest: $bust_chest, <br>Waist: $waist, <br>Sleeve Length: $sleeve_length, <br>Shoulders: $shoulders, <br>Armhole: $armhole, Neck: $neck, <br>Length: $length <br>Hip: $hip, <br>Length (Shoulder to Knee): $length_shoulder_to_nee, <br>Length (Waist to Ankle): $length_waist_to_ankle' WHERE item_id = $item_id AND quote_id = $quote_id";
					$res = $writeConnection->query($query);
				}
				

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
		
		foreach($order->getAllItems() as $item1) {
			$quoteItemId = $item1->getQuoteItemId();
			$select = "SELECT custom_measurement FROM icsales_flat_quote_item WHERE item_id = $quoteItemId";
			
			$custom_measurement = $readConnection->fetchOne($select);
			
			$query1 = "UPDATE icsales_flat_order_item SET custom_measurement = '$custom_measurement' WHERE quote_item_id = $quoteItemId";
			$res1 = $writeConnection->query($query1);			
		}
		
	}
	
}