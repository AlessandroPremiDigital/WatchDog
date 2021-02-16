<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Core_Exception extends Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Abstract {

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("cp/dog/trigger/template/renderer/core/exception.phtml");
    }
    public function getDescriptionHtml()
    {
        $imageSrc = Mage::getDesign()->getSkinUrl("cp/images/watchdog-error-100.jpg");
        $imageSrc = str_replace("http:", "", $imageSrc);
        $imageSrc = str_replace("https:", "", $imageSrc);
$_html =<<<HTML
<table >
<tr>
<td>
<div class="trigger-desc">
<img class="stopwatch" src="$imageSrc" height="100" />
<div class="template-header">Magento Errors</div>
<div class="template-details">
Please notify me if my store is reporting errors.<div class="clear" style = "margin-bottom:7px;"></div>
For example, you can have the system send an alert:
<ul >
<li> Send an alert every five minutes for Critical Site Errors, and include additional people.</li>
<li> Send an alert every two hours for general errors on the Magento site.</li>
</ul>
<div style="max-width:500px;">Our system has 4 levels of error detection:, Magento exceptions & reports, Magento errors and notices, PHP (Fatal Errors, Warnings, and Notices), HTTP Status Errors</div>
</div>
</div>
</td>
</tr>
</table>
HTML
; 
     return $_html;
    }
    public function getForm() {
	return Mage::getBlockSingleton('dog/adminhtml_trigger_template_renderer_core_exception_form');
    }
}    
