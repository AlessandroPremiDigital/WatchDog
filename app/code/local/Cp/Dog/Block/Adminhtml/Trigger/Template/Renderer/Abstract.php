<?php
Abstract Class Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Abstract
    extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
    }  
    
    protected function _moneyFormat($value)
    {
        if (function_exists("money_format"))
        {
            $value = money_format("%n", floatval($value));
        }
        else
        {
            $value = round(floatval($value), 2);
        }
        return Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol() . $value;
    }    
    
    public function getDescriptionHtml()
    {
        return 'Trigger Template Descriptor Html'; 
    }
}    