<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_Block_Adminhtml_Rewriterule_Edit extends Mage_Adminhtml_Block_Widget_Container
{

    protected $_blockGroup = 'arrakis_404evergone';
    /**
     * Part for building some blocks names
     *
     * @var string
     */
    protected $_controller = 'adminhtml_rewriterule';

    /**
     * Generated buttons html cache
     *
     * @var string
     */
    protected $_buttonsHtml;

    /**
     * Prepare page layout, basing on different registry and request variables
     *
     * @return Arrakis_404EverGone_Block_Adminhtml_Rewriterule_Edit
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('arrakis/404evergone/rewriterule/edit.phtml');

        $this->_addButton('back', array(
            'label'   => Mage::helper('adminhtml')->__('Back'),
            'onclick' => 'setLocation(\'' . Mage::helper('adminhtml')->getUrl('*/*/') . '\')',
            'class'   => 'back',
            'level'   => -1
        ));

        $this->_headerText = Mage::helper('arrakis_404evergone')->__('Add New 404 Rewrite Rule');

        // edit form for existing rewriterule
        if ($this->getRewriteruleId()) {
            $this->_headerText = Mage::helper('arrakis_404evergone')->__('Edit Rewrite 404 Rewrite Rule');
        }
        $this->_setFormChild();

        return parent::_prepareLayout();
    }

    /**
     * Add edit form as child block and add appropriate buttons
     *
     * @return Arrakis_404EverGone_Block_Adminhtml_Rewriterule_Edit
     */
    protected function _setFormChild()
    {
        $this->setChild('form', Mage::getBlockSingleton('arrakis_404evergone/adminhtml_rewriterule_edit_form'));
        if ($this->getRewriteruleId()) {
            $this->_addButton('reset', array(
                'label'   => Mage::helper('adminhtml')->__('Reset'),
                'onclick' => '$(\'edit_form\').reset()',
                'class'   => 'scalable',
                'level'   => -1
            ));
            $this->_addButton('delete', array(
                'label'   => Mage::helper('adminhtml')->__('Delete'),
                'onclick' => 'deleteConfirm(\'' . Mage::helper('adminhtml')->__('Are you sure you want to do this?')
                    . '\', \'' . Mage::helper('adminhtml')->getUrl('*/*/delete', array('id' => $this->getRewriteruleId())) . '\')',
                'class'   => 'scalable delete',
                'level'   => -1
            ));
        }
        $this->_addButton('save', array(
            'label'   => Mage::helper('adminhtml')->__('Save'),
            'onclick' => 'editForm.submit()',
            'class'   => 'save',
            'level'   => -1
        ));

        return $this;
    }

    /**
     * Get container buttons HTML
     *
     * Since buttons are set as children, we remove them as children after generating them
     * not to duplicate them in future
     *
     * @return string
     */
    public function getButtonsHtml($area = null)
    {
        if (null === $this->_buttonsHtml) {
            $this->_buttonsHtml = parent::getButtonsHtml();
            foreach ($this->_children as $alias => $child) {
                if (false !== strpos($alias, '_button')) {
                    $this->unsetChild($alias);
                }
            }
        }
        return $this->_buttonsHtml;
    }

    /**
     * Get current rewriterule instance id
     *
     * @return int
     */
    public function getRewriteruleId()
    {
        return Mage::registry('current_rewriterule')->getId();
    }
}
