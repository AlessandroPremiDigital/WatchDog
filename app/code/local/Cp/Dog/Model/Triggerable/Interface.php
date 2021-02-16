<?php
Interface Cp_Dog_Model_Triggerable_Interface
{
    public function run(Cp_Dog_Model_Trigger $trigger);
    public function canCreate();
    public function populateData();
}
