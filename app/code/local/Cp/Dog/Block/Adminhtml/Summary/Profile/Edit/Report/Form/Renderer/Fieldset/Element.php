<?php
Class Cp_Dog_Block_Adminhtml_Summary_Profile_Edit_Report_Form_Renderer_Fieldset_Element
    extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("cp/dog/summary/profile/edit/reports/renderer/fieldset/element.phtml");
    }
}    