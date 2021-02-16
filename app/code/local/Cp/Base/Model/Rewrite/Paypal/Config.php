<?php
Class Cp_Base_Model_Rewrite_Paypal_Config extends Mage_Paypal_Model_Config
{
    public function getBuildNotationCode($countryCode = null)
    {
        if ( Mage::getConfig ()->getModuleConfig( 'Enterprise_Enterprise') )
            $_bnNumber = 'CParadigm_SI_MagentoEE_PPA';
        else
            $_bnNumber = 'CParadigm_SI_MagentoCE_PPA';
        return $_bnNumber;
    }    
}
