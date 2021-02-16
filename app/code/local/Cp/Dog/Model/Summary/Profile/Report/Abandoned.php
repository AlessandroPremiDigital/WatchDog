<?php
Class Cp_Dog_Model_Summary_Profile_Report_Abandoned
    extends Cp_Dog_Model_Summary_Profile_Report_Abstract
{
    public function getTitle()
    {
        return Mage::helper("dog")->__('Abandoned Carts');
    }
    
    public function getDescriptionHtml()
    {
$html =<<<HTML
<div class="report-description">
<p class="abbr">The <strong>Abandoned Carts</strong> report lets you know how many customers did not complete their orders, and shows you the potential revenue in their carts.</p>

<p>Daily Information: </p>
<ul>
 <li>Total # of Abandoned Carts</li>
 <li>Total $$ of Abandoned Carts</li>
 <li>A report in the format: <strong>Customer Name / Customer Email | Time last Updated | Abandoned Value</strong></li>
</ul>
</div>
HTML;
      
        return $html;
    }
    
    protected function _loadData()
    {
        $collection = Mage::getResourceModel("sales/quote_collection")
                        ->removeAllFieldsFromSelect()
                     #   ->removeFieldFromSelect('entity_id')
                        ->addFieldToFilter("main_table.is_active", 1)
                        ->addFieldToFilter("main_table.base_grand_total", array("gt" => 0.00))
                     /** Notice we use updated_at here instead of created_at **/
                        ->addFieldToFilter("main_table.updated_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()))
                    #    ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ->addFieldToSelect("base_grand_total")
                        ->addFieldToSelect("customer_firstname")
                        ->addFieldToSelect("customer_lastname")
                        ->addFieldToSelect("customer_email")
                        ->addFieldToSelect("updated_at")
                        ;            
         $collection    ->getSelect()
                        ->order("base_grand_total DESC")
                        ;  
         $_count = 0;               
         $_total = 0;
         foreach($collection as $row)
         {
             if ( (!$row->getData("customer_firstname") || !strlen($row->getData("customer_firstname")))
             || (!$row->getData("customer_lastname") || !strlen($row->getData("customer_lastname")))
             )
             {
                 $row->setFullname("Anonymous (Did not reach checkout)");
             }
             else
             {
                 $_fullname = $row->getCustomerFirstname() . " " . $row->getCustomerLastname();
                 if ($row->getCustomerEmail() && strlen($row->getCustomerEmail()))
                 {
                     $_fullname .= ' (' . $row->getCustomerEmail() . ')';
                 }
                 $row->setData("fullname", $_fullname);
             }
             $_count++;
             $_total += floatval($row->getBaseGrandTotal());
         }
         $this->setData("items", $collection);   
         $this->setData("total_count", $_count);
         $this->setData("total_value", $this->_moneyFormat($_total));

         return $this;          
    }
}    