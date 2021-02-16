<?php
Class Cp_Dog_Model_Summary_Profile_Report_Orders
    extends Cp_Dog_Model_Summary_Profile_Report_Abstract
{
    public function getTitle()
    {
        return Mage::helper("dog")->__('Sales Orders');
    }
    
    public function getDescriptionHtml()
    {
$html =<<<HTML
<div class="report-description">
<p class="abbr">The <strong>Sales Orders</strong> report will give you header-level information about every order that was placed on your store </p>

<p>Daily Information: </p>
<ul>
 <li>A report in the format: <strong>Order # | Date | Customer Name | Amount | Order Status </strong></li>
</ul>
</div>
HTML;
      
        return $html;
    }
    
    protected function _loadData()
    {
        $collection = Mage::getResourceModel("sales/order_collection")
                        ->removeAllFieldsFromSelect()
                        ->addFieldToFilter("created_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()))
                    #    ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ->addFieldToSelect("base_grand_total")
                        ->addFieldToSelect("increment_id")
                        ->addFieldToSelect("created_at")
                        ->addFieldToSelect("status")
                        ;            
         $collection    ->addExpressionFieldToSelect('full_name', "CONCAT({{cf}}, ' ', {{cl}}, ' (', {{ce}}, ')')", array("cf" => "main_table.customer_firstname", "cl" => "main_table.customer_lastname", "ce" => "customer_email"))                  
                        ->getSelect()->order("main_table.entity_id DESC")                        ;
        $this->setData("orders", $collection);
        return $this;
    }
}    