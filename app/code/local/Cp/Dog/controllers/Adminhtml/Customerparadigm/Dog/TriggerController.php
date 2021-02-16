<?php
/**
 * @class Cp_Helper_Adminthml_Customerparadigm_MagentoController
 */
 class Cp_Dog_Adminhtml_Customerparadigm_Dog_TriggerController
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
   protected function _initTrigger() {
	$params	= $this->getRequest()->getParams();
	if(isset($params['trigger_id'])) {
		$trigger	= Mage::getModel('dog/trigger')->load($params['trigger_id']);
		if($trigger->getTriggerId() == null) {
			$this->_getSession()->addError('Could not find trigger.');
			$this->_redirect('*/*/list');
			return;
		}
		Mage::register('current_trigger', $trigger);
	}
	return $this;
   }
   
   public function editAction()
   {
	$params	= $this->getRequest()->getParams();
       $this->_initTrigger();
       $this->_initAction();
		
	if(isset($params['state'])) {
		try {
			$service	= Mage::getModel('dog/trigger_service')->create($params);
		} catch(Exception $ex) {
			$redirectParams		= array();
		//	echo $ex->getMessage().chr(10);
		//	die($ex->getTraceAsString());
			$this->_getSession()->addError(
                            Mage::helper('adminhtml')->__($ex->getMessage()));
			if(Mage::registry('current_trigger') !== null) {
				$redirectParams['trigger_id'] = Mage::registry('current_trigger')->getId();
			}
			$this->_redirect('*/*/edit',$redirectParams);
			return;
		}
		
		$this->_redirect('*/*/list');
	}
       $this->renderLayout();
   }
   public function deleteAction() {
	$parms	= $this->getRequest()->getParams();
	try {
		Mage::getModel('dog/trigger')->load($parms['id'])->delete();
		$this->_getSession()->addSuccess('Trigger was deleted!');
	} catch(Exception $e) {
		$this->_getSession()->addError('Unable to delete');	
	}
	$this->_redirect('*/*/list');	
   }
   public function editajaxAction()
   {
       if (!$this->getRequest()->getParam('template_id'))
       {
           echo '';
           exit;
       }
	$params	= $this->getRequest()->getParams(); //Move to abstract
       $this->_initTrigger();
	/**
	if(isset($params['trigger_id']) && $params['trigger_id'] != 0) {
		$trigger	= Mage::getModel('dog/trigger')->load($params['trigger_id']);
		Mage::register('current_trigger', $trigger);
	}*/
       
       $this->_initAction();
       
       $_template_id = $this->getRequest()->getParam('template_id');
       
       $_template    = Mage::getModel("dog/trigger_template")->load($_template_id);
       
       echo $this->getLayout()->createBlock($_template->getRenderer())->toHtml();
       exit;
   }
}
