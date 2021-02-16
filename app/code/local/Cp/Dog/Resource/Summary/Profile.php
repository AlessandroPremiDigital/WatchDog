<?php
Class Cp_Dog_Resource_Summary_Profile
    extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init("dog/summary_profile", "profile_id");
    }
}    