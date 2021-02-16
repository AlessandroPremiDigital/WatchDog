<?php
/**
 * @class Cp_Helper_Adminthml_Customerparadigm_MagentoController
 */
 class Cp_Dog_Adminhtml_Customerparadigm_Dog_SummaryController 
    extends Cp_Dog_Controller_Adminhtml_Abstract
{  
   public function indexAction()
   {
       $this->_initAction();
       $this->renderLayout(); 
   }    
   
   public function listAction()
   {
       $this->_initAction();
       $this->renderLayout();
   }
   
   public function editAction()
   {
      if ($this->getRequest()->getParam("delete"))
      {
          Mage::getModel("dog/summary_profile")->load($this->getRequest()->getParam('profile_id'))->delete();
          $this->_getSession()->addSuccess(Mage::helper("dog")->__("Successfully Deleted Summary Profile."));
          return $this->_redirect("*/*/list");
      }       
      if ($this->getRequest()->isPost())
      {
          $_isNew = !($this->getRequest()->getParam('profile_id') && intval($this->getRequest()->getParam('profile_id')));
          
          $model  = Mage::getModel("dog/summary_profile");
          
          if (!$_isNew) 
            $model->load($this->getRequest()->getParam('profile_id'));
          
          foreach($this->getRequest()->getParams() as $k => $v)
          {
              switch($k)
              {
                  case "form_key":
                  case "profile_id":
                      //do nothing
                      break;
                  default:
                      $model->setData($k, $v);
                      break;
              }
          }
          
          try
          {
              $model->save();

              if ($_isNew)
                
                $_text = Mage::helper("dog")->__("Created New Daily Summary Profile, Id: " . $model->getId());
              else
                $_text = Mage::helper("dog")->__("Successfully Updated Summary Profile");
              
              $this->_getSession()->addSuccess($_text);
              if ($_isNew)
              {
                
                return $this->_redirect("*/*/*/profile_id/" . $model->getId());
              }
          }
          catch (Mage_Core_Exception $e)
          {
              $_text = Mage::helper("dog")->__("There was an error updating the summary profile. Message: " . $e->getMessage());
              $this->_getSession()->addError($_text);
          }
      }
      $this->_initAction();
      $this->renderLayout(); 
   }
}