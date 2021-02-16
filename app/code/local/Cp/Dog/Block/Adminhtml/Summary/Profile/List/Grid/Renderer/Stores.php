<?php
Class Cp_Dog_Block_Adminhtml_Summary_Profile_List_Grid_Renderer_Stores
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $store_ids = @unserialize($row["store_ids"]);
        $stores      = array();
        foreach($store_ids as $c)
        {
            $stores[] = Mage::getModel("core/store")->load($c)->getName();
        }
        return implode(" , " , $stores);
    }
}