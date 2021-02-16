<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Sales_Orderrate
    extends Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("cp/dog/trigger/template/renderer/sales/orderrate.phtml");
    }
    public function getDescriptionHtml()
    {
        $imageSrc = Mage::getDesign()->getSkinUrl("cp/images/watchdog-stopwatch-100.jpg");
        $imageSrc = str_replace("http:", "", $imageSrc);
        $imageSrc = str_replace("https:", "", $imageSrc);
$_html =<<<HTML
<table >
<tr>
<td>
<div class="trigger-desc">
<img class="stopwatch" src="$imageSrc" height="100" />
<div class="template-header">Order Alert Trigger</div>
<div class="template-details">
Please notify me if my store:
<ul style="">
<li> Has a spike in sales during a period of time</li>
<li> Has no sales during a specific period of time</li>
</ul>
For example, you can have the system send an alert:
<ul >
<li> Send an alert if there are more than 25 orders placed in an hour</li>
<li> Send an alert if there are less than 5 orders placed every five hours</li>
</ul>
(You can configure the options on the next screen.)
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
	return Mage::getBlockSingleton('dog/adminhtml_trigger_template_renderer_sales_orderrate_form');
    }
}    
