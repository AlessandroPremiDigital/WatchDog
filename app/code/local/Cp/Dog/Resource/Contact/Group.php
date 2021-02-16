<?php
Class Cp_Dog_Resource_Contact_Group extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init("dog/contact_group", "group_id");
    }
}
