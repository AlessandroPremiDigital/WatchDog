<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Edit_Tab_Abstract
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_trigger     = null;
    protected $_triggerable = null;
    
    public function getTriggerId()
    {
        return $this->getRequest()->getParam('trigger_id');
    }    
    
    public function isNew()
    {
        return $_isNew = !($this->getRequest()->getParam("trigger_id") && intval($this->getRequest()->getParam("trigger_id")));
    }
        
    public function getTrigger()
    {
        if (is_null($this->_trigger))
        {
            if ($this->isNew()) $this->_trigger = false;
            
            else
            {
                $this->_trigger   = Mage::getModel("dog/trigger")->load($this->getTriggerId());
            }
        }
        return $this->_trigger;
    }
}    