<?php
Class Cp_Dog_Controller_Adminhtml_Abstract
    extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('dog');
    }   
    
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');        
    }
   
    protected function _initAction()
    {     
        if (!Mage::helper("dog")->isConfigured() && $this->getRequest()->getActionName() != "settings")
        {
            $this->_getSession()->addSuccess(
                Mage::helper("dog")->__("Congratulations on installing CustomerParadigm's Dog Magento Service! Before we can start monitoring your site, please review the terms and conditions.")
            );                
            $this->_redirect("*/customerparadigm_dog/settings");
            return;
        }            
        if (Mage::helper("dog")->getConfig("update_report_error"))
        {
            $_retyUrl = $this->getUrl("*/customerparadigm_dog/retryreport");
            $this->_getSession()->setData("dog_ret_url", $this->getUrl("*/*/*"));
            $this->_getSession()->addError(
                Mage::helper("dog")->__("Please note your file permissions do not allow us to edit errors/report.php. As a result, some error reporting functions may not work. Please contact CustomerParadigm for more information. <br/><a href='$_retyUrl'>Click here to retry</a>")
            );
        }
        $this->loadLayout()
            ->_addBreadcrumb(Mage::helper('dog')->__('CustomerParadigm'), Mage::helper('dog')->__('CustomerParadigm'))
          ;
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper("dog")->__("Watchdog | CustomerParadigm Magento Monitoring Service"));
        $this->_setActiveMenu('cp');
        return $this;
    }      
}    