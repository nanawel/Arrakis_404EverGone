<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_Block_Adminhtml_Rewriterule_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('rewriteruleGrid');
        $this->setDefaultSort('rule_id');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('arrakis_404evergone/rewriterule_collection');
        $this->setCollection($collection);
        parent::_prepareCollection();

        if($this->_isExport) {
            $this->removeColumn('actions');
        }

        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('rule_id', array(
            'header'    => $this->__('Rule ID'),
            'width'     => '50px',
            'index'     => 'rule_id',
            'type'  => 'number'
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => $this->__('Store View'),
                'width'     => '200px',
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view' => true,
            ));
        }

        $this->addColumn('request_path_regex', array(
            'header'    => $this->__('Request Path Regex'),
            'width'     => '60px',
            'index'     => 'request_path_regex'
        ));

        $this->addColumn('target_path', array(
            'header'    => $this->__('Target Path'),
            'width'     => '50px',
            'index'     => 'target_path'
        ));

        $this->addColumn('options', array(
            'header'    => $this->__('Options'),
            'width'     => '50px',
            'index'     => 'options'
        ));

        $this->addColumn('is_active', array(
            'header'    => $this->__('Active'),
            'width'     => '50px',
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                1 => $this->__('Yes'),
                0 => $this->__('No')
            ),
        ));

        $this->addColumn('use_count', array(
            'header'    => $this->__('Use count'),
            'width'     => '30px',
            'index'     => 'use_count',
            'type'      => 'number'
        ));

        $this->addColumn('actions', array(
            'header'    => $this->__('Action'),
            'width'     => '15px',
            'sortable'  => false,
            'filter'    => false,
            'type'      => 'action',
            'actions'   => array(
                array(
                    'url'       => $this->getUrl('*/*/edit') . 'id/$rule_id',
                    'caption'   => $this->__('Edit'),
                ),
            )
        ));

        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportXml', $this->__('XML'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id'=>$row->getId()));
    }

}

