<?php
$installer = $this;
$installer->startSetup();
$prefix = Mage::getConfig()->getTablePrefix();
$table = $installer->getTable("dog/config");
$installer->run("DROP TABLE IF EXISTS `{$table}`");
$installer->run("CREATE TABLE `{$table}` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) DEFAULT NULL,
  `config_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB");

$table = $installer->getTable("dog/contact");
$installer->run("DROP TABLE IF EXISTS `{$table}`");
$installer->run("CREATE TABLE `{$table}` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contact_id`),
  KEY `index_dog_contact_email` (`email`)
) ENGINE=InnoDB");

$table = $installer->getTable("dog/error");
$installer->run("DROP TABLE IF EXISTS `{$table}`");
$installer->run("CREATE TABLE `{$table}` (
  `error_id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text,
  `level` varchar(5) DEFAULT NULL,
  `human_readable` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `store_id` smallint(6) NOT NULL,
  PRIMARY KEY (`error_id`),
  KEY `index_dog_error_time` (`date`),
  KEY `index_dog_error_store_id` (`store_id`)
) ENGINE=InnoDB");

$table = $installer->getTable("dog/trigger_template");
$installer->run("DROP TABLE IF EXISTS `{$table}`");
$installer->run("CREATE TABLE `{$table}` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `category` varchar(200) DEFAULT NULL,
  `renderer` varchar(255) DEFAULT NULL,
  `class_name` varchar(255) DEFAULT NULL,
  `friendly_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB");

$table = $installer->getTable("dog/trigger");
$installer->run("DROP TABLE IF EXISTS `{$table}`");
$installer->run("CREATE TABLE `{$table}` (
  `trigger_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `class_data` blob,
  `code` varchar(200) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `job_schedule` varchar(200) DEFAULT NULL,
  `enabled` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`trigger_id`),
  KEY `fk_trigger_template_id` (`template_id`),
  KEY `index_dog_trigger_enabled` (`enabled`),
  CONSTRAINT `fk_trigger_template_id` FOREIGN KEY (`template_id`) REFERENCES `{$prefix}dog_trigger_template` (`template_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB");

$table = $installer->getTable("dog/trigger_run");
$installer->run("DROP TABLE IF EXISTS `{$table}`");
$installer->run("CREATE TABLE `{$table}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_id` int(11) DEFAULT NULL,
  `run_number` int(11) DEFAULT NULL,
  `run_time` datetime DEFAULT NULL,
  `status` varchar(1) DEFAULT 'S',
  `message` mediumtext,
  PRIMARY KEY (`id`),
  KEY `fk_trigger_id_from_run` (`trigger_id`),
  KEY `index_dog_trigger_run_date` (`run_time`),
  KEY `idx_dog_trigger_run_run_number` (`run_number`),
  CONSTRAINT `fk_trigger_id_from_run` FOREIGN KEY (`trigger_id`) REFERENCES `{$prefix}dog_trigger` (`trigger_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB");

$table = $installer->getTable("dog/summary_profile");
$installer->run("DROP TABLE IF EXISTS `{$table}`");
$installer->run("CREATE TABLE `{$table}` (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `store_ids` text,
  `contacts` text,
  `reports` text,
  `send_time` int(11) NOT NULL,
  `trigger_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`profile_id`),
  KEY `fk_trigger_id` (`trigger_id`),
  CONSTRAINT `fk_trigger_id` FOREIGN KEY (`trigger_id`) REFERENCES `{$prefix}dog_trigger` (`trigger_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB");

$table = $installer->getTable("core/store");
$installer->getConnection()
    ->addColumn($table, 'monitor', array(
        'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable' => false,
        'default' => 0,
        'comment' => 'Whether watchdog should monitor this store'
    )
);
#$installer->run("ALTER TABLE {$table} ADD COLUMN monitor TINYINT");
 
/** Added so that users can choose to send alerts to CP */
$table = $installer->getTable("dog/contact");
$installer->run("INSERT INTO {$table} (email,name) VALUES ('watchdog@customerparadigm.com','Send To Customer Paradigm')");

$table = $installer->getTable("dog/trigger_template");
$installer->run("INSERT INTO {$table} VALUES (1,'orderrate','base','dog/adminhtml_trigger_template_renderer_sales_orderrate','dog/triggerable_order','Order Rate Trigger'),(2,'error','base','dog/adminhtml_trigger_template_renderer_core_exception','dog/triggerable_error','Error Trigger')");

$installer->endSetup();
