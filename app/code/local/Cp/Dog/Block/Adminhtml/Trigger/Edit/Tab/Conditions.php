<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Edit_Tab_Conditions
    extends Cp_Dog_Block_Adminhtml_Trigger_Edit_Tab_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cp/dog/trigger/edit/conditions/form.phtml');
       # $this->setData("templates", Mage::helper("dog/trigger")->getTemplateCollection());
	if(Mage::registry('current_trigger') !== null)
		$this->setIsNew(false);
	else
		$this->setIsNew(true);
        /**if (!$this->isNew())
        {
            $this->setData("active_template_id", $this->getTrigger()->getTemplateId());
        }*/
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setChild('template_types', $this->getLayout()->createBlock("dog/adminhtml_trigger_template_selector")
                                                            ->setData("trigger", $this->getTrigger())
                                                            
                       );
    }
    
    public function getActiveStep()
    {
	if($this->isNew())
		return 1;
	else
		return 2;
    }
    
    public function getActiveStepJson()
    {
	if($this->isNew())
		return json_encode(array());
	else {
		$templateId	= Mage::registry('current_trigger')->getTemplateId();;
		return json_encode(array('template_id' => $templateId));
	}
    }
}    
