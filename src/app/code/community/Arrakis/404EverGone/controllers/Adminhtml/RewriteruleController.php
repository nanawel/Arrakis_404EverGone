<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_Adminhtml_RewriteruleController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Instantiate rewriterule, product and category
     *
     * @return Arrakis_404EverGone_Adminhtml_RewriteruleController
     */
    protected function _initLayout()
    {
        $this->_title($this->__('404 Rewrite Rule Management'));
        $this->loadLayout();
        $this->_setActiveMenu('catalog/rewriterule');
        return $this;
    }

    public function preDispatch()
    {
        // Load rule if any
        $this->_getCurrentRewriteRule();

        return parent::preDispatch();
    }

    /**
     * Show rewriterules index page
     *
     */
    public function indexAction()
    {
        $this->_initLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('arrakis_404evergone/adminhtml_rewriterule')
        );
        $this->renderLayout();
    }

    /**
     * Show rewriterule edit/create page
     *
     */
    public function editAction()
    {
        $this->_initLayout();
        $this->_addContent($this->getLayout()->createBlock('arrakis_404evergone/adminhtml_rewriterule_edit'));
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * rewriterule save action
     *
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $session = Mage::getSingleton('adminhtml/session');
            try {
                $model = $this->_getCurrentRewriteRule();

                // Validate request path
                $requestPathRegex = $this->getRequest()->getParam('request_path_regex');
                $model->validateRequestPathRegex($requestPathRegex);

                // Proceed and save request
                $model->setIsActive($this->getRequest()->getParam('is_active'))
                    ->setTargetPath($this->getRequest()->getParam('target_path'))
                    ->setOptions($this->getRequest()->getParam('options'))
                    ->setDescription($this->getRequest()->getParam('description'))
                    ->setRequestPathRegex($requestPathRegex)
                    ->setStoreId($this->getRequest()->getParam('store_id', 0));

                $model->save();
                $session->addSuccess(Mage::helper('arrakis_404evergone')->__('The 404 rewrite rule has been saved.'));

                // Check given test path
                if ($testPath = $this->getRequest()->getParam('test_path')) {
                    try {
                        $testPathMatches = $model->matchPath($testPath);
                    }
                    catch (Exception $e) {
                        Mage::logException($e);
                        $testPathMatches = false;
                    }

                    if ($testPathMatches) {
                        $session->addSuccess(
                            Mage::helper('arrakis_404evergone')->__('Test path "%s" matches regex "%s".', $testPath, $requestPathRegex)
                        );
                    }
                    else {
                        $session->addWarning(
                            Mage::helper('arrakis_404evergone')->__('Test path "%s" does NOT match regex "%s".', $testPath, $requestPathRegex)
                        );
                    }
                }

                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage())
                    ->setRewriteruleData($data);
            } catch (Exception $e) {
                $session->addException($e, Mage::helper('arrakis_404evergone')->__('An error occurred while saving 404 rewrite rule.'))
                    ->setRewriteruleData($data);
                $session->addError($e->getMessage());
                // return intentionally omitted
            }
        }
        $this->_redirectReferer();
    }

    /**
     * rewriterule delete action
     *
     */
    public function deleteAction()
    {
        if ($this->_getCurrentRewriteRule()->getId()) {
            try {
                $this->_getCurrentRewriteRule()->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('arrakis_404evergone')->__('The 404 rewrite rule has been deleted.')
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')
                    ->addException($e, Mage::helper('arrakis_404evergone')->__('An error occurred while deleting 404 rewrite rule.'));
                $this->_redirect('*/*/edit/', array('id' => $this->_getCurrentRewriteRule()->getId()));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export customer grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = '404rewriterule.csv';
        $content    = $this->getLayout()->createBlock('arrakis_404evergone/adminhtml_rewriterule_grid')
            ->getCsvFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export customer grid to XML format
     */
    public function exportXmlAction()
    {
        $fileName   = '404rewriterule.xml';
        $content    = $this->getLayout()->createBlock('arrakis_404evergone/adminhtml_rewriterule_grid')
            ->getExcelFile();

        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check whether this controller is allowed in admin permissions
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/rewriterule');
    }

    protected function _getCurrentRewriteRule()
    {
        if (!Mage::registry('current_rewriterule')) {
            $ruleId = $this->getRequest()->getParam('id');
            Mage::register('current_rewriterule', Mage::getModel('arrakis_404evergone/rewriterule')->load($ruleId));
        }
        return Mage::registry('current_rewriterule');
    }
}
