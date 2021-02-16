<?php
Class Cp_Dog_Helper_Trigger extends Cp_Dog_Helper_Data
{
    public function getTemplateCollection()
    {
        return Mage::getResourceModel("dog/trigger_template_collection");
    }
}
