<?php
Class Cp_Dog_Resource_Store_Collection
    extends Mage_Core_Model_Resource_Store_Collection
{
    protected function _initSelect()
    {
        parent::_initSelect();
        
        $this->addFieldToFilter("monitor", 1); 
      
        return $this;
    }    
}    