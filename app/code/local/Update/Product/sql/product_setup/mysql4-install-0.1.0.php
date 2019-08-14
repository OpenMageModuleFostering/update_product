<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('product')};
CREATE TABLE {$this->getTable('product')} (
  `product_id` int(11) unsigned NOT NULL auto_increment,
  `Pfund` varchar(255) NOT NULL default '',
  `Euro` varchar(255) NOT NULL default '',
  `USD` varchar(255) NOT NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 