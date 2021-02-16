<?php
Class Cp_Dog_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CERBERUS_ENDPOINT = "http://watchdog.customerparadigm.com/api/account/register";
    
    const NEW_ACCOUNT_ID    		= 'NEW';
    const EXTERNAL_SUCCESS  		= 'S';
    const EXTERNAL_ERROR    		= 'E';
    const EVENT_PREFIX	    		= 'CP_DOG';
    const DEFAULT_SUMMARY_PROFILE_EMAIL	= "cp_dog_summary_profile";
    
  /*  const GROUP_TECHNICAL   = "technical";
    const GROUP_GENERAL     = "general";
    const GROUP_SALES       = "sales";
    const GROUP_SERVICE     = "customerservice";
    const GROUP_MARKETING   = "marketing"; */
        
    protected function _getRandomHash()
    {
        $_random = Mage::helper("core")->getRandomString(7);
        $_hash   = md5($_random . date('c'));  
        return $_hash;
    }
    protected function _getCronRunUrl() {
	return 'dog/cron/run/';	
    }
    
    public function synch()
    {
	
	//$this->setConfig('external_account_id', $_newAccountId);
        
        return true;   
    }

    /**
     * @todo This function needs to return all additional contacts for a store that should receive remote monitoring emails
     * @return array | array of email addresses
     */
    public function getStoreExternalContacts()
    {
        return array();
    }
    
    public function getContactsHelp()
    {
$js =<<<JS
_(document).ready(function(){
    var el = _("#contacts");
el.prepend('<div class="contacts-description">By default all reports are emailed to the Master Contacts, listed above.  If you would like to have this specific report sent to an additional person, please select from the email contacts below.  <span class="tz-default">To add a new email contact, please visit<br/><strong>CustomerParadigm->Watchdog->Contacts</strong></span></div>');    
})
JS
;       
    return $js;
    }


    
    public function getMonitorStores()
    {
        return Mage::getResourceModel("dog/store_collection");
    }
    
    public function getConfig($key)
    {
        $_model = Mage::getModel("dog/config")->load($key, "config_key");
        if (!$_model->getId()) return null;
        
        else return $_model->getConfigValue();
    }
    
    public function setConfig($key, $value)
    {
        $_model = Mage::getModel("dog/config")->load($key, "config_key");
        $_model->setData("config_key", $key)->setData("config_value", $value)->save();
        return $this;
    }
    
    public function getMasterContacts()
    {
        return explode(",", $this->getMasterContact()->getConfigValue());
    }
        
    public function getMasterContact()
    {
        return $_master_contact = Mage::getModel("dog/config")->load("master_contact", "config_key");        
    }
        
    public function isConfigured()
    {
        if (!$this->getConfig("master_contact") || !strlen($this->getConfig("master_contact"))) return false;
        return true;
    }
    public function dispatchEvent($suffix, $data=array()) 
    {
	   Mage::dispatchEvent(self::EVENT_PREFIX.'_'.$suffix, $data);
	return $this;
    }
    public function getBaseEmailTemplate() {
	return Mage::getModel('core/email_template')->load(self::BASE_EMAIL_TEMPLATE_NAME, 'template_code');
    }
    public function sendAlertEmail($reports_html,$profile_name, $contacts, $_template) {
	if(empty($contacts))
		$contacts	= array();

//	$_template = self::DEFAULT_SUMMARY_PROFILE_EMAIL;
        $_template_params = array(
            "reports_html" => $reports_html,
            "profile_name" => ucwords($profile_name),
        );
	$mailer = Mage::getModel('core/email_template_mailer');

        $emailInfo = Mage::getModel('core/email_info');
        foreach($contacts as $c_id)
        {
            $contact = Mage::getModel("dog/contact")->load($c_id);
            $emailInfo->addTo($contact->getEmail(), $contact->getName());
        }

        //$emailInfo->addBcc("benkornmeier@gmail.com");

        foreach($this->getMasterContacts() as $c)
        {
            $emailInfo->addTo($c);
        }
	$mailer->addEmailInfo($emailInfo);
        // Set all required params and send emails
        $mailer->setSender(array(
            "name"  => Mage::helper("dog")->__(Mage::helper("dog")->getConfig("email_from_name")), 
            "email" => Mage::helper("dog")->__(Mage::helper("dog")->getConfig("email_from_address")) 
        ));
        $mailer->setStoreId(0);
        $mailer->setTemplateId($_template);
        $mailer->setTemplateParams($_template_params);
	
	return $mailer->send();
	
    }
}
