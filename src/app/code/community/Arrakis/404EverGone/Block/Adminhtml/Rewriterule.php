<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_Block_Adminhtml_Rewriterule extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    protected $_blockGroup = 'arrakis_404evergone';

    /**
     * Part for generating apropriate grid block name
     *
     * @var string
     */
    protected $_controller = 'adminhtml_rewriterule';

    /**
     * Set custom labels and headers
     *
     */
    public function __construct()
    {
        $this->_headerText = Mage::helper('arrakis_404evergone')->__('404 Rewrite Rule Management');
        $this->_addButtonLabel = Mage::helper('arrakis_404evergone')->__('Add 404 Rewrite Rule');
        parent::__construct();
    }

    /**
     * Customize grid row URLs
     *
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/edit');
    }
}
