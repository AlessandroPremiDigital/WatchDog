<?php
Class Cp_Dog_Block_Adminhtml_Summary_Profile_Edit_Tab_Abstract
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_profile = null;
    
    public function getProfileId()
    {
        return $this->getRequest()->getParam('profile_id');
    }    
    
    public function isNew()
    {
        return $_isNew = !($this->getRequest()->getParam("profile_id") && intval($this->getRequest()->getParam("profile_id")));
    }
        
    public function getProfile()
    {
        if (is_null($this->_profile))
        {
            if ($this->isNew()) $this->_profile = false;
            
            else
            {
                $this->_profile = Mage::getModel("dog/summary_profile")->load($this->getProfileId());
            }
        }
        return $this->_profile;
    }
}    