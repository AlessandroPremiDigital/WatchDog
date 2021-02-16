<?php
Class Cp_Dog_Model_Summary_Profile_Report_Totals
    extends Cp_Dog_Model_Summary_Profile_Report_Abstract
{
    public function getTitle()
    {
        return Mage::helper("dog")->__('Sales Totals');
    }
    
    public function getDescriptionHtml()
    {
$html =<<<HTML
<div class="report-description">
<p class="abbr">The <strong>Sales Totals</strong> report will give you information regarding the monetary performance of your store(s). </p>

<p>Daily Information: </p>
<ul>
 <li>Avg Order Size for the given day</li>
 <li>Total Ordered Today</li>
 <li>Total Invoiced Today</li>
 <li>Total Refunded Today</li>
</ul>
<p>Additional Information: </p>
<ul>
    <li>WTD (Week-to-date) Sales</li>
    <li>MTD (Month-to-date) Sales</li>
    <li>YTD (Year-to-date) Sales</li>
    <li>Lifetime Sales</li>
</ul>
</div>
HTML;
      
        return $html;
    }
    
    protected function _loadData()
    {
        $_today = $this->_getTodayData();
        foreach($_today as $k=>$v)
        {
             if (!$v)
                $v = 0.00;
             if (!strstr($k, "count")) $v = $this->_moneyFormat($v);
             $this->setData($k, $v);
        }
        
        $_week  = $this->_getWeekData();
        $this->setWtdOrdered($this->_moneyFormat($_week));
        
        $_month = $this->_getMonthData();
        $this->setMtdOrdered($this->_moneyFormat($_month));
        
        $_year  = $this->_getYearData();
        $this->setYtdOrdered($this->_moneyFormat($_year));
        
        $_life  = $this->_getLifetimeData();
        $this->setLifetimeOrdered($this->_moneyFormat($_life));

        return $this;
    }
    
    protected function _getTodayData()
    {
        $collection = Mage::getResourceModel("sales/order_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('entity_id')
                        ->addFieldToFilter("created_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()))
                        ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ;
        $collection     ->addExpressionFieldToSelect('total_ordered', 'SUM({{q}})', array('q' => 'main_table.base_grand_total'))
                        ->addExpressionFieldToSelect('total_count_ordered', 'COUNT({{q}})', array('q' => 'main_table.entity_id'))
                        ;                    
        $_totals = $collection->getFirstItem();
        
        if ($_totals["total_count_ordered"] == 0)
        {
            $_totals["avg_order_size"] = 0;
        }
        else
        {
            $_totals["avg_order_size"] = $_totals['total_ordered'] / $_totals['total_count_ordered'];
        }
        
        $_reportData = $_totals->getData();
        
        $collection = Mage::getResourceModel("sales/order_invoice_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('entity_id')
                        ->addFieldToFilter("created_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()))
                        ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ;
        $collection     ->addExpressionFieldToSelect('total_invoiced', 'SUM({{q}})', array('q' => 'main_table.base_grand_total'))
                        ->addExpressionFieldToSelect('total_count_invoices', 'COUNT({{q}})', array('q' => 'main_table.entity_id'))
                        ;    
                        
       $_invoiceTotals = $collection->getFirstItem();
       
       $_reportData = array_merge($_reportData, $_invoiceTotals->getData());    
       
        $collection = Mage::getResourceModel("sales/order_creditmemo_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('entity_id')
                        ->addFieldToFilter("created_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()))
                        ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ;
        $collection     ->addExpressionFieldToSelect('total_refunded', 'SUM({{q}})', array('q' => 'main_table.base_grand_total'))
                        ->addExpressionFieldToSelect('total_count_refunded', 'COUNT({{q}})', array('q' => 'main_table.entity_id'))
                        ;    
                        
       $_creditmemoTotals = $collection->getFirstItem();
       
       $_reportData = array_merge($_reportData, $_creditmemoTotals->getData());           
       
       return $_reportData;
              
    }

    protected function _getWeekData()
    {
        $collection = Mage::getResourceModel("sales/order_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('entity_id')
                        ->addFieldToFilter("created_at", array("from" => $this->getBeginWeek(), "to" => $this->getNow()))
                        ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ;
        $collection     ->addExpressionFieldToSelect('total_ordered', 'SUM({{q}})', array('q' => 'main_table.base_grand_total'))
                        ->addExpressionFieldToSelect('total_count_ordered', 'COUNT({{q}})', array('q' => 'main_table.entity_id'))
                        ;
        $_totals = $collection->getFirstItem();   
        
        return $_totals["total_ordered"];     
    }

    protected function _getMonthData()
    {
        $collection = Mage::getResourceModel("sales/order_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('entity_id')
                        ->addFieldToFilter("created_at", array("from" => $this->getBeginMonth(), "to" => $this->getNow()))
                        ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ;
        $collection     ->addExpressionFieldToSelect('total_ordered', 'SUM({{q}})', array('q' => 'main_table.base_grand_total'))
                        ->addExpressionFieldToSelect('total_count_ordered', 'COUNT({{q}})', array('q' => 'main_table.entity_id'))
                        ;
        $_totals = $collection->getFirstItem();   
        
        return  $_totals["total_ordered"];     
    }

    protected function _getYearData()
    {
        $collection = Mage::getResourceModel("sales/order_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('entity_id')
                        ->addFieldToFilter("created_at", array("from" => $this->getBeginYear(), "to" => $this->getNow()))
                        ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ;
        $collection     ->addExpressionFieldToSelect('total_ordered', 'SUM({{q}})', array('q' => 'main_table.base_grand_total'))
                        ->addExpressionFieldToSelect('total_count_ordered', 'COUNT({{q}})', array('q' => 'main_table.entity_id'))
                        ;
        $_totals = $collection->getFirstItem();   
        
        return $_totals["total_ordered"];     
    }

    protected function _getLifetimeData()
    {
        $collection = Mage::getResourceModel("sales/order_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('entity_id')
                      #  ->addFieldToFilter("created_at", array("from" => $this->getBeginWeek(), "to" => date("Y-m-d 23:59:59")))
                        ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ;
        $collection     ->addExpressionFieldToSelect('total_ordered', 'SUM({{q}})', array('q' => 'main_table.base_grand_total'))
                        ->addExpressionFieldToSelect('total_count_ordered', 'COUNT({{q}})', array('q' => 'main_table.entity_id'))
                        ;
        $_totals = $collection->getFirstItem();   
        
        return $_totals["total_ordered"];     
    }
}    