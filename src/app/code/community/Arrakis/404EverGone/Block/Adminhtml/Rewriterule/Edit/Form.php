<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_Block_Adminhtml_Rewriterule_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Set form id and title
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('rewriterule_form');
//        $this->setTitle(Mage::helper('arrakis_404evergone')->__('Block Information'));
    }

    /**
     * Prepare the form layout
     *
     * @return Arrakis_404EverGone_Block_Adminhtml_Rewriterule_Edit_Form
     */
    protected function _prepareForm()
    {
        $model    = Mage::registry('current_rewriterule');

        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post'
            )
        );

        // set form data either from model values or from session
        $formValues = array(
            'store_id'              => $model->getStoreId(),
            'request_path_regex'    => $model->getRequestPathRegex(),
            'target_path'           => $model->getTargetPath(),
            'options'               => $model->getOptions(),
            'is_active'             => $model->getIsActive(),
            'description'           => $model->getDescription(),
        );
        if ($sessionData = Mage::getSingleton('adminhtml/session')->getData('rewriterule_data', true)) {
            foreach ($formValues as $key => $value) {
                if (isset($sessionData[$key])) {
                    $formValues[$key] = $sessionData[$key];
                }
            }
        }

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->__('404 Rewrite Rule Information')
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => $this->__('Active'),
            'title'     => $this->__('Active'),
            'name'      => 'is_active',
            'required'  => true,
            'options'   => array(
                1 => $this->__('Yes'),
                0 => $this->__('No')
            ),
            'value'     => $formValues['is_active']
        ));

        $isFilterAllowed = false;
        // get store switcher or a hidden field with its id
        if (!Mage::app()->isSingleStoreMode()) {
            $stores  = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm();
            $entityStores = array();
            $noStoreError = false;

            $element = $fieldset->addField('store_id', 'select', array(
                'label'     => $this->__('Store'),
                'title'     => $this->__('Store'),
                'name'      => 'store_id',
                'required'  => true,
                'values'    => $stores,
                'value'     => $formValues['store_id'],
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $element->setRenderer($renderer);
            if ($noStoreError) {
                $element->setAfterElementHtml($noStoreError);
            }
        } else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'store_id',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }

        $fieldset->addField('request_path_regex', 'text', array(
            'label'     => $this->__('Request Path Regex'),
            'title'     => $this->__('Request Path Regex'),
            'name'      => 'request_path_regex',
            'required'  => true,
            'value'     => $formValues['request_path_regex'],
            'note'      => $this->__('Matching pattern for this rule, using '
                            . '<a href="https://dev.mysql.com/doc/refman/5.0/en/regexp.html" target="_blank">MySQL regular expressions</a>. '
                            . 'Ex: "^/abc$" will match exact path "/abc".')
        ));

        $fieldset->addField('target_path', 'text', array(
            'label'     => $this->__('Target Path'),
            'title'     => $this->__('Target Path'),
            'name'      => 'target_path',
            'required'  => true,
            'value'     => $formValues['target_path'],
            'note'      => $this->__('No leading slash needed')
        ));

        $fieldset->addField('options', 'select', array(
            'label'     => $this->__('Redirect'),
            'title'     => $this->__('Redirect'),
            'name'      => 'options',
            'options'   => array(
                ''   => $this->__('No'),
                'R'  => $this->__('Temporary (302)'),
                'RP' => $this->__('Permanent (301)'),
            ),
            'value'     => $formValues['options']
        ));

        $fieldset->addField('description', 'textarea', array(
            'label'     => $this->__('Description'),
            'title'     => $this->__('Description'),
            'name'      => 'description',
            'cols'      => 20,
            'rows'      => 5,
            'value'     => $formValues['description'],
            'wrap'      => 'soft'
        ));

        $fieldset->addField('test_path', 'text', array(
                'label'     => $this->__('Test path'),
                'title'     => $this->__('Test path'),
                'name'      => 'test_path',
                'value'     => '',
                'note'      => $this->__('This path will be tested when form is saved')
            ));

        $form->setUseContainer(true);
        $form->setAction(Mage::helper('adminhtml')->getUrl('*/*/save', array(
            'id'       => $model->getId(),
        )));
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
