<?php
Class Cp_Dog_Model_Summary_Profile_Report_Items
    extends Cp_Dog_Model_Summary_Profile_Report_Abstract
{
    public function getTitle()
    {
        return Mage::helper("dog")->__('Bestsellers');
    }
    
    public function getDescriptionHtml()
    {
$html =<<<HTML
<div class="report-description">
<p class="abbr">The <strong>Bestsellersms</strong> report will give you detailed information about the items sold on your store each day </p>

<p>Daily Information: </p>
<ul>
 <li>A report in the format: <strong>Item # (Sku) | Qty Ordered | Product Price | Total Value | Avg Unit Price</strong></li>
 <li><em><strong>*</strong>Above report will be sorted by best-worst sellers (Max of 25 products)</em>
</ul>
HTML;
      
        return $html;
    }
    
    protected function _loadData()
    {
        $collection = Mage::getResourceModel("sales/order_item_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('item_id')
                        ->addFieldToFilter("main_table.created_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()))
                    #    ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ->addFieldToSelect("product_id")
                        ;            
         $collection    ->addExpressionFieldToSelect("item_value", "SUM({{q}})", array("q" => "main_table.base_row_total"))
                        ->addExpressionFieldToSelect("item_qty", "SUM({{q}})", array("q" => "main_table.qty_ordered"))
                        ->getSelect()
                        ->group("main_table.product_id")
                        ->order("item_qty DESC")
                        ->limit("25")
                        ;  
         $this->setData("items", $collection);       
    }
}    