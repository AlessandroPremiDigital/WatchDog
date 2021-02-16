<?php
 
class Cp_Dog_Block_Adminhtml_Settings_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/*', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
        ));
 
        $form->setUseContainer(true);
 
        $this->setForm($form);
 
        $fieldset = $form->addFieldset('master_emails', array(
             'legend' =>Mage::helper('dog')->__('Master Contact Information')
        ));
 
        $fieldset->addField('master_contact', 'text', array(
             'label'     => Mage::helper('dog')->__('Master Email Addresses'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'master_contact',
             'note'     => Mage::helper('dog')->__('Enter a comma (,) delimited list of addresses. These will be copied on ALL notifications from the system'),
        ));
        
        $fieldset = $form->addFieldset('alert_from', array(
             'legend' =>Mage::helper('dog')->__('Email Sender')
        ));
 
        $fieldset->addField('email_from_address', 'text', array(
             'label'     => Mage::helper('dog')->__('Sender Address'),
             'class'     => 'validate-email',
             'required'  => true,
             'name'      => 'email_from_address',
           #  'value'     => 'email@example.com',
             'note'     => Mage::helper('dog')->__('ie: email@example.com; This address will appear in the FROM line of all email alerts.For best results, use an email address that is associated with the domain name of the web server.'),
        ));  
        
        $fieldset->addField('email_from_name', 'text', array(
             'label'     => Mage::helper('dog')->__('Sender Name'),
            # 'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'email_from_name',
           #  'value'     => 'Example Sender',
             'note'     => Mage::helper('dog')->__('This name will appear in the FROM line of all email alerts'),
        ));               
 
         $fieldset = $form->addFieldset('stores', array(
             'legend' =>Mage::helper('dog')->__('Select Stores to Monitor')
        ));
 
        $_selectedStores = Mage::getResourceModel("dog/store_collection");
        
        $_arr = array();
        foreach($_selectedStores as $s)
        {
            $_arr[] = "{$s->getId()}";
        }
        
        $data = array();
        
        $data["master_contact"] = (!$_conf = Mage::helper("dog")->getConfig("master_contact")) ? "" : $_conf;
        
        $data["dog_stores"] = $_arr;
        
        $data["email_from_address"] = (!$_conf = Mage::helper("dog")->getConfig("email_from_address")) ? "" : $_conf;
        
        $data["email_from_name"] = (!$_conf = Mage::helper("dog")->getConfig("email_from_name")) ? "" : $_conf;
        
        $_storeValues = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm();
        
        foreach($_storeValues as $i=> & $s)
        {
            if (sizeof($s["value"]))
            {
                foreach($s["value"] as & $store)
                {
                    $_url   = Mage::app()->getStore($store["value"])->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
                    $store["label"] .= ' ' . $_url;
                }
            }
        }
        
        $fieldset->addField('dog_stores', 'multiselect', array(
             'label'     => Mage::helper('dog')->__('Stores'),
             'class'     => 'required-entry',
             'required'  => true,
             'width'     => 400,
             'name'      => 'store_ids[]',
             'values'    => $_storeValues,
             'note'     => Mage::helper('dog')->__('Select all stores you would like WatchDog to monitor')
        ));
        
        if ( ($_account = Mage::helper("dog")->getConfig("external_account_id")) && strlen($_account))
        {
            $data['external_account_id'] = $_account;
            $fieldset = $form->addFieldset('acccount_settings', array(
                 'legend' =>Mage::helper('dog')->__('Account Settings ')
            ));
     
            $fieldset->addField('external_account_id', 'text', array(
                 'label'     => Mage::helper('dog')->__('External Account Id'),
                 'class'     => 'required-entry',
                 'required'  => true,
                 'disabled'   => true,
                 'name'      => 'external_account_id',
                 'note'     => Mage::helper('dog')->__("The Watchdog system does not rely on a cron in order to function.  Instead, Customer Paradigm's Watchdog servers will ping your Watchdog script every 5 minutes to run the Watchdog Alerts, Triggers and Sales Summaries.  If an error 200 appears on your site, Customer Paradigm's system will send an email to your master email addresses letting you know that there is an error on your site.  In order to provide this free service, Customer Paradigm stores the website's URL, your master email addresses and store names on our Watchdog Server.  If you do not wish to have this information stored by Customer Paradigm, please uninstall this free Magento extension.  This is a free service provided by Customer Paradigm, and Customer Paradigm assumes no liability for missing, delayed or undeliverable Watchdog alert emails sent from your system."),
            ));            
        }
        
        $form->setValues($data);
 
        return parent::_prepareForm();
    }
}