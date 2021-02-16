<?php
Class Cp_Dog_Block_Adminhtml_Summary_Profile_List_Grid_Renderer_Sendtime
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $_time = $row->getSendTime() / 3600;
        
        if ($_time > 12) $_time -= 12;
        
        return $_time . ":00 PM";
    }
}