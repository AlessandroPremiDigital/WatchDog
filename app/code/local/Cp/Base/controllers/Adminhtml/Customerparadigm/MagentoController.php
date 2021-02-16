<?php
/**
 * @class Cp_Helper_Adminthml_Customerparadigm_MagentoController
 */
 class Cp_Base_Adminhtml_Customerparadigm_MagentoController 
    extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout()
            ->_addBreadcrumb(Mage::helper('cp_base')->__('CustomerParadigm'), Mage::helper('cp_base')->__('CustomerParadigm'))
          ;
        return $this;
    }       
    
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}    
