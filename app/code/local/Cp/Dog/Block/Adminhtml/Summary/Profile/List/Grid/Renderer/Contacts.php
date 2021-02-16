<?php
Class Cp_Dog_Block_Adminhtml_Summary_Profile_List_Grid_Renderer_Contacts
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $contact_ids = @unserialize($row["contacts"]);
        $emails      = array();
        foreach($contact_ids as $c)
        {
            $emails[] = Mage::getModel("dog/contact")->load($c)->getEmail();
        }
        return implode(" , " , $emails);
    }
}