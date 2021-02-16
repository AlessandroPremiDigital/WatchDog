<?php
Class Cp_Dog_Resource_Error extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init("dog/error", "error_id");
    }
}
