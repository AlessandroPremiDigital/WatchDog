<?php
Abstract Class Cp_Dog_Model_Summary_Profile_Report_Abstract
    extends Varien_Object
{
    protected $_dataIsLoaded = false;
    
    abstract public function getTitle();
    
    abstract public function getDescriptionHtml();
    
    
    /** Give each subclass the opportunity to load additional variables before the template is rendered **/ 
    protected function _beforeTemplateRender()
    {
        
    }
    
    /** Give each report the opportunity to load data **/
    public function loadData($force = false)
    {
        if ($force || !$this->_dataIsLoaded)
        {
            $this->_loadData();
            $this->_dataIsLoaded = true;
        }
    }
    
    
    /** Allow each report to override this. The idea is to use $this->setData instead of adding extra variables **/
    protected function _loadData()
    {
        
    }
    
    public function getHtml()
    {
        ob_start();
       
        /** Render the report template like such **/ 
        
        # First, grab the template path for the report  
        $includeFilePath = Mage::getModuleDir("Model", "Cp_Dog") .DS. "Model" . DS. "Summary" . DS . "Profile" . DS . "Report" . DS . "template" . DS . $this->getCode() . ".phtml";     
        
        $this->loadData();
        $this->_beforeTemplateRender();
        
        # Now simply include it. Template file will have access to all the model's methods!
        require_once($includeFilePath);
        
        $reportHtml = ob_get_contents(); ob_end_clean();
        
        return $reportHtml;
    }
    
    
    protected function _moneyFormat($value)
    {
        return Mage::helper('core')->currency($value,true,false);
        /*if (function_exists("money_format"))
        {
            setlocale(LC_MONETARY, 'en_US');
            $value = money_format("%n", floatval($value));
        }
        else
        {
            $value = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol() .round(floatval($value), 2);
        }
        return  $value;*/
    }    
}    