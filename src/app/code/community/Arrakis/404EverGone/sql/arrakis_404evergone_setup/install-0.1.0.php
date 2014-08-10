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
$table = $installer->getConnection()
    ->newTable($installer->getTable('arrakis_404evergone/rewriterule'))
    ->addColumn('rule_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Rule Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Store Id')
    ->addColumn('request_path_regex', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Request path regex')
    ->addColumn('target_path', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Target path')
    ->addColumn('options', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Options')
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => 1,
        ), 'Is active')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Description')
    ->setComment('404 rewrites by regex');
$installer->getConnection()->createTable($table);

$installer->endSetup();
