<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */

/** @var $installer Arrakis_404EverGone_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
/**
 * Create table 'arrakis_404evergone/rewriterule'
 */
$connection = $installer->getConnection();

$connection->addColumn($installer->getTable('arrakis_404evergone/rewriterule'), 'use_count', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'default'   => 0,
    'comment'   => 'Use count',
));

$installer->endSetup();
