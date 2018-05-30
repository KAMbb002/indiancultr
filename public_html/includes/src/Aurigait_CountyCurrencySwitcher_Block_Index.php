<?php   
class Aurigait_CountyCurrencySwitcher_Block_Index extends Mage_Core_Block_Template{   
  
    public function getCurrentCurrencyCode()
    {
        
        $curr_code_cookie= Mage::getModel('core/cookie')->get('currency_code');
    	$code=Mage::app()->getStore()->getCurrentCurrency()->getCode();
        
        if(!empty($curr_code_cookie) && $code!=$curr_code_cookie)
            {
                //Mage::app()->getStore()->setCurrentCurrencyCode($curr_code_cookie);
                $code=$curr_code_cookie; 
            }
        return $code;
    }
    
    public function getCountries()
    {
       /* $_countries = Mage::getResourceModel('directory/country_collection')
                    ->loadData()
                    ->toOptionArray(false);
        */
    	$_countries=$this->getShipToCounty();
        return $_countries;                        
    }
    public function getCurrentCountryCode()
    {
        $c_code= Mage::getModel('core/cookie')->get('webCode');
        return $c_code;
    }
    
    public function getClientIp()
    {

	    $ipaddress = '';
	    if ($_SERVER['HTTP_CLIENT_IP'])
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if($_SERVER['HTTP_X_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if($_SERVER['HTTP_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if($_SERVER['HTTP_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if($_SERVER['REMOTE_ADDR'])
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
		$ipaddress = 'UNKNOWN';
       
	    return $ipaddress;
    }
    
    public function getShipToCounty()
    {
	$countries=Mage::getStoreConfig('general/country/ship_to');
	$country_list=explode(',',$countries);
	
	ksort($country_list);
	$option=array();
	foreach($country_list as $county_code)
	{
	    $county_label = Mage::app()->getLocale()->getCountryTranslation($county_code);
	    $option[]=array('value'=>$county_code,'label'=>$county_label);
	}
	return $option;
	
    }
    
    public function showPopup()
    {
    	$code= Mage::getModel('core/cookie')->get('pouptime');
    	
    	if(empty($code))
    	{
    		Mage::getModel('core/cookie')->set('pouptime',2,time() + (86400));
    		$code=1;
    	}
    	return $code;
    }
}
