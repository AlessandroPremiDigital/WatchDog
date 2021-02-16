<?php
/**
 * Customer shutdown handler to catch fatal errors
 */
function dog_shutdown_handler()
{
      $error = error_get_last();
      if( $error !== NULL && intval($error["type"]) <= E_PARSE) {
        $errno   = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr  = $error["message"];
        $message = "Error in file: " . $errfile . "----LINE: ".$errline."\nMessage: " . $errstr;
        if (class_exists('Mage'))
            Mage::dispatchEvent("CP_DOG_ERROR", array("message" => $message, "level" => $errno * 100));     
      }    
}
Class Cp_Dog_Model_Observer
{
    /*protected function _installed()
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $sql = "SELECT COUNT(*) FROM " . Mage::getSingleton("core/resource")->getTableName("core/resource") . " WHERE code = 'dog_setup'";
        $result = $read->fetchOne($sql);
        return intval($result) > 0;
    }*/

    public function setStatus() {
        Mage::log("CP Dog Cron WORKS!");
    }

    public function enableLogging($obs)
    {
        Mage::app()->getStore()->setConfig("dev/log/active", 1);
        Mage::setIsDeveloperMode(false);
        register_shutdown_function( "dog_shutdown_handler" );
        if (!Mage::helper("dog")->getConfig("update_report"))
        {
            $this->_catIntoReport();
            Mage::helper("dog")->setConfig("update_report", 1);
        }
        
    }
    
    public function logError($obs)
    {
        $message = $obs->getMessage();
        $level   = $obs->getLevel();
        if (strstr(strtolower($message), "deprecated")) $level = 6;
        if (strstr(strtolower($message), "warning")) $level    = 4;
        if (strstr(strtolower($message), "notice")) $level     = 5;
        if (strstr(strtolower($message), "fatal error")) $level= 2;
        switch($level)
        {
            case 0:
                $level_readable = "EMERGENCY (Priority 0)";
                break;
            case 1:
                $level_readable = "ALERT (Priority 1)";
                break;
            case 2:
            case 100:
            case 400:
                $level          = 2;
                $level_readable = "CRITICAL (Priority 2)";
                break;
            case 3:
                $level_readable = "ERROR (Priority 3)";
                break;
            case 4:
            case 200:
                $level = 4;
                $level_readable = "WARNING (Priority 4)";
                break;
            case 5:
            case 800:
                $level = 5;
                $level_readable = "NOTICE (Priority 5)";
                break;
            case 6:
            case 819200:
                $level = 6;
                $level_readable = "Deprecated Notice (Priority 6)";
                break;
            default:
                $level          = 3;
                $level_readable = "ERROR";
        }
        $error = Mage::getModel("dog/error");
        $origZone = date_default_timezone_get();
        date_default_timezone_set("UTC");
        $error->setData(
            array(
                "message" => $message,
                "level"   => $level,
                "human_readable"=> $level_readable,
                "date"    => date("Y-m-d H:i:s"),
                "store_id"=> Mage::app()->getStore()->getId()
            )
        );
        $error->save();
        date_default_timezone_set($origZone);
        return $this;           
    }

    public function cleanLogs()
    {
        foreach(Mage::getResourceModel("dog/error_collection") as $error)
        {
            $error->delete();
        }        
        $content = "";
        $logs = array("system.log", "exception.log");
        foreach($logs as $l)
        {
            $path = Mage::getBaseDir("var") . DS . "log" . DS . $l;
            @file_put_contents($path, $content);
        }
        return $this;
    }
    
    protected function _catIntoReport()
    {
        $_match  = '<?php';
        $_path   = Mage::getBaseDir() . DS . 'errors' . DS . 'report.php';
        $_old    = file_get_contents($_path);
        /** Make sure they aren't reinstalling... **/
        if (strstr($_old, "CP_DOG_ERROR")) return;
        $_updates=<<<HTML
<?php
\$message = \$e->getMessage() . "\\n\\n" . \$e->getTraceAsString();
Mage::dispatchEvent("CP_DOG_ERROR", array("message" => \$message, "level" => 3 ));
?>

HTML
;        
        $_cat   = $_updates.$_old;
        if (!is_writeable($_path))
        {
            chmod($_path, 0755);
        }
        if(false === file_put_contents($_path, $_cat))
        {
            Mage::helper("dog")->setConfig("update_report_error", "Unable to update report.php : check server permissions");
        }       
    }
}




