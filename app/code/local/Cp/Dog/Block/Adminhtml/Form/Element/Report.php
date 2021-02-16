<?php
Class Cp_Dog_Block_Adminhtml_Form_Element_Report
    extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('report');
    }
    
    public function getLabelElementHtml()
    {
        $checked = '';
        if ($this->getChecked())
        {
            $checked = ' checked="checked" '; 
        }
        $html = '<input' . $checked . ' type="checkbox" name="' . $this->getName() . '" value="' . $this->getValue() . '" /> <h3>' . $this->getLabel() . '</h3>';
        return $html;        
    }
    
    public function getDescriptionHtml()
    {
        return $this->getDescription();
    }

    public function getElementHtml()
    {
        return $this->_getDescription();
    }

/** PUTTING THE ELEMENT IN THE LABEL FOR SAKE OF DISPLAY ONLY **/
    public function getLabelHtml($idSuffix = ''){
        #return $this->_getLabelAndElement();
    }    
    
    
    protected function _getDescription()
    {
        return $this->getDescription();
    }
}    