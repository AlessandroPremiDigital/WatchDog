<?php
Class Cp_Dog_Block_Adminhtml_Form_Contacts_Renderer_Fieldset_Element
    extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("cp/dog/form/contacts/renderer/fieldset/element.phtml");
    }
}    