<?php
class Aurigait_CountyCurrencySwitcher_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getCountryByIp($ip)
	{
	      $ipnum=ip2long($ip);
	     if($ipnum!='')
	     {	
	      $sql="select code from geoip_database where $ipnum between begin_num and end_num";
	      $resource = Mage::getSingleton('core/resource');
	      $readConnection = $resource->getConnection('core_read');
	      $c_code = $readConnection->fetchCol($sql);
	      return $c_code[0];	
	     }
	    else
	    {
		return 'US';
	     }				
	}	
}
