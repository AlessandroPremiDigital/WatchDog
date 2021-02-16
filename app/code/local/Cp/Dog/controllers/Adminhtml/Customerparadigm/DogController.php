<?php
/**
 * @class Cp_Helper_Adminthml_Customerparadigm_MagentoController
 */
 class Cp_Dog_Adminhtml_Customerparadigm_DogController 
    extends Cp_Dog_Controller_Adminhtml_Abstract
{
    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }
    
    public function helpAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }
    
    public function settingsAction()
    {
        if ($this->getRequest()->isPost() && $this->getRequest()->getParam("tc_agree"))
        {
            Mage::helper("dog")->setConfig("license_agreement", 1);
            $this->_getSession()->addSuccess(Mage::helper("dog")->__("Thank you for using the watchdog extension! Before we can start monitoring your site, we need a little information."));
        }
        elseif ($this->getRequest()->isPost())
        {
            $_master_contact = $this->getRequest()->getParam("master_contact");
            $_stores         = $this->getRequest()->getParam("store_ids");
            $_emailFrom      = $this->getRequest()->getParam("email_from_address");
            $_emailName      = $this->getRequest()->getParam("email_from_name");
            
            Mage::helper("dog")->setConfig("email_from_address", $_emailFrom);
            Mage::helper("dog")->setConfig("email_from_name", $_emailName);
            
            $_dataHasChanged = false;
            
            if (! ($_current_mc= Mage::helper("dog")->getConfig("master_contact"))
            || $_current_mc != $_master_contact //If it doesn't exist, or it has changed. 
            )
            {
                $_dataHasChanged = true; 
                Mage::helper("dog")->getMasterContact()->setData("config_value", $_master_contact)
                    ->setData("config_key", "master_contact")->save();
            }
            
            foreach($_stores as $_s)
            {
                $_storeModel = Mage::getModel("core/store")->load($_s);
                if (!$_storeModel->getId())
                {
                    Mage::throwException(Mage::helper("dog")->__("Invalid store selected for monitoring. Please contact CustomerParadigm. Store Id: " . $_s));
                }
                
                if (!$_storeModel->getMonitor())
                {
                    $_dataHasChanged = true;
                    $_storeModel->setMonitor(1)->save();
                }
            }
            
            $this->_getSession()->addSuccess(Mage::helper("dog")->__("Updated master settings"));
            
            
                
        }
        $this->_initAction();
        
        if(!Mage::helper("dog")->isConfigured() && !Mage::helper("dog")->getConfig("license_agreement"))
        {
            $this->getLayout()->getBlock("dog.settings")->setTemplate("cp/dog/settings/license.phtml");
        }
        $this->renderLayout();
    }

    public function retryreportAction()
    {
        $_ret_url = $this->_getSession()->getData("dog_ret_url");
        Mage::getBaseDir() . DS . 'errors' . DS . 'report.php';
        $_path   = Mage::getBaseDir() . DS . 'errors' . DS . 'report.php';
        if (is_writeable($_path))
        {
            Mage::getModel("dog/config")->load("update_report_error", "config_key")->delete();
            Mage::helper("dog")->setConfig("update_report", 0);
            $this->_getSession()->addSuccess(Mage::helper("dog")->__("errors/report.php is now writeable!"));
        }
        else
        {
            $this->_getSession()->addError(Mage::helper("dog")->__("errors/report.php is still not writeable"));
        }
        $this->_redirectUrl($_ret_url);
        return;
    }
    
    public function contactsAction()
    {
        if ($this->getRequest()->isPost())
        {
            $_contacts = $this->getRequest()->getParam('contacts');
            $_deletes  = $this->getRequest()->getParam('delete_contact');
            
            if ($_contacts && is_array($_contacts))
                foreach($_contacts as $_id => $_data)
                {
                    $_contact = Mage::getModel("dog/contact");
                    
                    if (substr($_id, 0,1) != "c") //New contacts have a c prepended to their ids
                        $_contact->load($_id);
                    foreach($_data as $_k => $_v)
                        $_contact->setData($_k, $_v); 
                        
                    $_contact->save();
                }    
            
            if ($_deletes && is_array($_deletes))
                foreach($_deletes as $_d)
                {
                    Mage::getModel("dog/contact")->load($_d)->delete();
                }
            $this->_getSession()->addSuccess("Successfully updated contacts."); 
        }        
        $this->_initAction();
        $this->renderLayout(); 
    }
}    
