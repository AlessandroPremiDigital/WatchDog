<?php
Class Cp_Dog_Helper_Summary extends Mage_Core_Helper_Abstract
{
    const SUMMARY_PATH = "global/dog/summary/profile";
    
    protected $_list = null;
    
    public function getReports($codes = false)
    {
        if (is_null($this->_list))
        {
            $_list = new Varien_Data_Collection();
            foreach(Mage::getConfig()->getNode(self::SUMMARY_PATH."/reports")->children() as $code => $child)
            {
                $groupedClass = (string)$child->class;
                $instance = Mage::getModel($groupedClass);
                $instance->setData("code", $code); 
                $_list->addItem($instance);
            }            
            $this->_list = $_list;
        }
        
        /** Return the whole list **/
        if (!$codes || !is_array($codes))
            return $_list;
        else
        {
            $_partialList = new Varien_Data_Collection();
            foreach($this->_list as $report)
            {
                if (in_array($report->getCode(), $codes))
                {
                    $_partialList->addItem($report);
                }
            }
            return $_partialList;
        }
    }
    
    public function getReportByCode($code)
    {
        foreach($this->_list as $report)
        {
            if ($report->getCode() == $code)
                return $report;
        }
        return null;
    }
    public function getSendTimeOptions()
    {
        $_options = array();
        
        for($i = 1; $i < 24; $i++)
        {
            $hour = $i;
            if ($hour > 12)
            {
                $label = ($hour - 12) . ":00 PM";
            }
            else if ($hour == 12)
            {
                $label = $hour.":00 PM";
            }
            else
            {
                $label = ($hour) . ":00 AM";    
            }
            $_options[$hour * 3600] = $label;
        }
        $_options[(23 * 3600) + (55*60)] = "11:55 PM";
        return $_options;
    }    
}
    