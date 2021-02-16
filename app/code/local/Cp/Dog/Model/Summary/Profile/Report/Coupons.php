<?php
Class Cp_Dog_Model_Summary_Profile_Report_Coupons
    extends Cp_Dog_Model_Summary_Profile_Report_Abstract
{
    public function getTitle()
    {
        return Mage::helper("dog")->__('Coupons Used');
    }
    
    public function getDescriptionHtml()
    {
$html =<<<HTML
<div class="report-description">
<p class="abbr">The <strong>Coupons Used</strong> report is vital for any marketing team. It gives you detailed information about coupon usage on your site</p>

<p>Daily Information: </p>
<ul>
 <li>Total # of Coupons Used</li>
 <li>Total $$ discounted by Coupons</li>
 <li>A report in the format: <strong>Coupon Code | Coupon Name | Coupon Type | # Uses | Total Discount Amount</strong></li>
</ul>
</div>
HTML;
      
        return $html;
    }
    
    protected function _loadData()
    {
        $collection = Mage::getResourceModel("sales/order_collection")
                        ->removeAllFieldsFromSelect()
                        ->removeFieldFromSelect('entity_id')
                        ->addFieldToFilter("created_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()))
                        ->addFieldToFilter("state", array("neq" => Mage_Sales_Model_Order::STATE_CANCELED))
                        ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                        ->addFieldToFilter("coupon_code", array("neq" => "", "notnull"))
                        ->addFieldToSelect("coupon_code")
                        ->addFieldToSelect("coupon_rule_name")
                        ;
        $collection     ->addExpressionFieldToSelect('total_uses', 'ifnull(COUNT({{c}}),0)', array('c' => '*'))
                        ->addExpressionFieldToSelect('total_discount', 'ifnull(SUM({{q}}),0) * -1', array('q' => 'main_table.base_discount_amount'))
                        ->getSelect()
                        ->group("main_table.coupon_code")
                        ->order("total_discount DESC")
                        ;
         $_count = 0;               
         $_total = 0;
         foreach($collection as $row)
         {
             $_count += $row->getTotalUses();
             $_total += $row->getTotalDiscount();
             $_coupon = Mage::getModel("salesrule/coupon")->loadByCode($row->getCouponCode());
             if ($_coupon->getId())
             {
                 $_rule   = Mage::getModel("salesrule/rule")->load($_coupon->getRuleId());
                 $_action = "Fixed Amount Discount";
                 if ($_rule->getSimpleAction() == "by_percent") $_action = "Percentage Discount";
                 $row->setCouponType($_action);
             }
         }
         $this->setData("coupons", $collection);   
         $this->setData("total_count", $_count);
         $this->setData("total_value", $this->_moneyFormat($_total));

         return $this;           
    }
}    