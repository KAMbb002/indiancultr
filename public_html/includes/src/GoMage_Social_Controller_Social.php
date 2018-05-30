<?php
/**
 * GoMage Social Connector Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.0.0
 */ 

abstract class GoMage_Social_Controller_Social extends Mage_Core_Controller_Front_Action {
		
	abstract function getSocialType();
	
	protected function getSession(){
		return Mage::getSingleton('customer/session');
	}
	
	protected function createSocial($social_id, $customer_id){
		return Mage::getModel('gomage_social/entity')
					->setData('social_id', $social_id)
					->setData('type_id', $this->getSocialType())
					->setData('customer_id', $customer_id)
					->setData('website_id', Mage::app()->getWebsite()->getId())
					->save();
	}
	
	protected function createCustomer($profile){
		
		$customer = Mage::getModel('customer/customer');
		$password =  $customer->generatePassword(8); 
		
		if (is_array($profile)){
			$profile = (object)$profile;
		}
		if($this->getSocialType()==GoMage_Social_Model_Type::FACEBOOK)
		{
			$source="Facebook";
		}
		else if($this->getSocialType()==GoMage_Social_Model_Type::GOOGLE)
		{
			$source="Google";
		}
		$config    = Mage::getModel('eav/config');
		$attribute = $config->getAttribute('customer', 'source');
		$values    = $attribute->getSource()->getAllOptions(false);
		foreach($values as $value)
		{
			if($value['label']==$source)
			{
				$source_id=$value['value'];
				break;
			}
		}
        $customer->setData('firstname', $profile->first_name)
        		 ->setData('lastname', $profile->last_name)
        		 ->setData('email', $profile->email)
        		 ->setData('password', $password)
        		 ->setPasswordConfirmation($password)
			 ->setIsSubscribed(1)
			 ->setSource($source_id); 
        
        $errors = $customer->validate();

        if (is_array($errors) && count($errors)){
        	$this->getSession()->addError(implode(' ', $errors));
        	return false; 
        }
        		 
        $customer->save();
        $customer->sendNewAccountEmail(); 
        
        return $customer;
        
	}
        protected function updateExtrainfo($profile,$customer_id)
	{
		if($profile['gender']=='male')
		 $gender=1;
		 else
		 $gender=2;
		 
		$birthday= date('Y-m-d',strtotime($profile['birthday']));
		 
		$customer = Mage::getModel('customer/customer')->load($customer_id);
		$customer->setProfileLink($profile['link'])
		->setBirthday($birthday)
		->setHometown($profile['hometown']['name'])
		->setCurrentLocation($profile['location']['name'])
		->setWorkInformation($profile['work'][0]['employer']['name'])
		->setEducation($profile['education'][count($profile['education'])-1]['school']['name']."(". $profile['education'][count($profile['education'])-1]['type'].")")
		->setGender($gender)
		->save();
	}
	protected function updateGoogleData($profile,$customer_id)
	{
		if($profile['gender']=='male')
		 $gender=1;
		 else if($profile['gender']=='female')
		 $gender=2;
		 
		if($profile['birthday'] && (strtotime($profile['birthday']) >0))
		{
		   $bday=date('Y-m-d',strtotime($profile['birthday']));	
		}
		 
		 $currenctLocation='';
		 $hometowns='';
		foreach($profile['placesLived'] as $data)
		{
			if($data['primary']==1)
			$currenctLocation=$data['value'];
			else
			$hometowns.=$data['value'].",";
		}
		$works='';
		$education='';
		foreach($profile['organizations'] as $data)
		{
			if($data['type']=='work')
			{
				if($data['title'])
					$works.=$data['name']."(".$data['title']."),";
				else
					$works.=$data['name'].",";
			}
			else if($data['type']=='school')
			{
				if($data['title'])
					$education.=$data['title']."(".$data['name']."),";
				else
					$education.=$data['name'].",";
			}
		}
		$works=substr($works,0,-1);
		$education=substr($education,0,-1);
		
		$hometowns=substr($hometowns,0,-1);
		$customer = Mage::getModel('customer/customer')->load($customer_id);
		$customer->setGender($gender)
		->setBirthday($bday)
		->setProfileLink($profile['url'])
		->setHometown($hometowns)
		->setCurrentLocation($currenctLocation)
		->setWorkInformation($works)
		->setEducation($education)->save();
	}
	protected function _getRedirectUrl($url){
		if (!$url){
		if(Mage::getSingleton('core/session')->getData('wClick'))
		{
			$pro_id=Mage::getSingleton('core/session')->getData('WPRODUCTID');
			$sup=Mage::getSingleton('core/session')->getData('WSUP');
			$sup_id=Mage::getSingleton('core/session')->getData('WSUPID');
			$returnurl=Mage::getSingleton('core/session')->getData('returnurl');
			$url=Mage::getUrl('waitlist/index/addWaitlistSocial')."?product=".$pro_id."&super_attribute=".$sup."&super_attribute_value=".$sup_id."&retUrl=".$returnurl;
			Mage::getSingleton('core/session')->unsetData('wClick');
			Mage::getSingleton('core/session')->unsetData('WPRODUCTID');
			Mage::getSingleton('core/session')->unsetData('WSUP');
			Mage::getSingleton('core/session')->unsetData('WSUPID');
			Mage::getSingleton('core/session')->unsetData('returnurl');
		}
		else if(Mage::getSingleton('core/session')->getData('wsClick'))
		{
			$pro_id=Mage::getSingleton('core/session')->getData('WPRODUCTID');
			$sup=Mage::getSingleton('core/session')->getData('WSUP');
			$sup_id=Mage::getSingleton('core/session')->getData('WSUPID');
			$returnurl=Mage::getSingleton('core/session')->getData('returnurl');
			$url=Mage::getUrl('waitlist/index/addWishlistSocial')."?product=".$pro_id."&super_attribute=".$sup."&super_attribute_value=".$sup_id."&retUrl=".$returnurl;
			Mage::getSingleton('core/session')->unsetData('wsClick');
			Mage::getSingleton('core/session')->unsetData('WPRODUCTID');
			Mage::getSingleton('core/session')->unsetData('WSUP');
			Mage::getSingleton('core/session')->unsetData('WSUPID');
			Mage::getSingleton('core/session')->unsetData('returnurl');
		}
		else
		{
		
			if ( Mage::getSingleton('core/session')->getData('gs_url')){
				$url = Mage::getSingleton('core/session')->getData('gs_url');
				$url=Mage::helper('core')->urlDecode($url);
				Mage::getSingleton('core/session')->unsetData('gs_url');
			}
	    
			if (!$url){
				 $url = $this->getRequest()->getParam('gs_url', '');
				$url = Mage::helper('core')->urlDecode($url);
			}
		}
        }
        if (!$url){
                $url = Mage::getBaseUrl();
        }
    	return $url;
	}
	
	protected function _redirectUrl($url='')
    {
    	
    	return parent::_redirectUrl($this->_getRedirectUrl($url));
    }

}
