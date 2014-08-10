<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_Model_Rewriterule extends Mage_Core_Model_Abstract
{
    const REGEX_REWRITE_RULE_REQUEST_PATH_ALIAS = 'regex_rewrite_rule_request_path';

    /**
     * Initialize customer model
     */
    function _construct()
    {
        $this->_init('arrakis_404evergone/rewriterule');
    }

    /**
     * Load first matching rule
     * 
     * @param Mage_Core_Controller_Request_Http|string $request
     */
    public function loadByRequest($request)
    {
        $this->getResource()->loadByRequest($this, is_string($request) ? $request : $request->getRequestUri());
        return $this;
    }

    public function hasOption($key)
    {
        $optArr = explode(',', $this->getOptions());

        return array_search($key, $optArr) !== false;
    }

    /**
     * Implement logic of regex rewrites
     * @see Mage_Core_Model_Url_Rewrite
     *
     * @param   Zend_Controller_Request_Http $request
     * @param   Zend_Controller_Response_Http $response
     * @return  Mage_Core_Model_Url
     */
    public function rewrite(Zend_Controller_Request_Http $request=null, Zend_Controller_Response_Http $response=null)
    {
        Varien_Profiler::start('404EVERGONE_REWRITERULE_REWRITE');

        if (!Mage::isInstalled()) {
            return false;
        }
        if (is_null($request)) {
            $request = Mage::app()->getFrontController()->getRequest();
        }
        if (is_null($response)) {
            $response = Mage::app()->getFrontController()->getResponse();
        }
        if (is_null($this->getStoreId()) || false===$this->getStoreId()) {
            $this->setStoreId(Mage::app()->getStore()->getId());
        }
        if (is_null($this->getIsActive())) {
            $this->setIsActive(1);
        }

        if (!$this->getId() && isset($_GET['___from_store'])) {
            try {
                $fromStoreId = Mage::app()->getStore($_GET['___from_store'])->getId();
            }
            catch (Exception $e) {
                return false;
            }

            $this->setStoreId($fromStoreId)
                ->loadByRequest($request);
        }
        if (!$this->getId() && isset($_GET['___store'])) {
            try {
                $fromStoreId = Mage::app()->getStore($_GET['___store'])->getId();
            }
            catch (Exception $e) {
                return false;
            }

            $this->setStoreId($fromStoreId)
                ->loadByRequest($request);
        }
        if (!$this->getId()) {
            $this->loadByRequest($request);
        }
        if (!$this->getId()) {
            return false;
        }

        $request->setAlias(self::REGEX_REWRITE_RULE_REQUEST_PATH_ALIAS, $this->getRequestPathRegex());
        $external = substr($this->getTargetPath(), 0, 6);
        $isPermanentRedirectOption = $this->hasOption('RP');
        if ($external === 'http:/' || $external === 'https:') {
            $destinationStoreCode = Mage::app()->getStore($this->getStoreId())->getCode();
            Mage::app()->getCookie()->set(Mage_Core_Model_Store::COOKIE_NAME, $destinationStoreCode, true);

            $this->_sendRedirectHeaders($this->getTargetPath(), $isPermanentRedirectOption);
        }
        else {
            $targetUrl = $request->getBaseUrl(). '/' . $this->getTargetPath();
        }

        $isRedirectOption = $this->hasOption('R');
        if ($isRedirectOption || $isPermanentRedirectOption) {
            if (Mage::getStoreConfig('web/url/use_store') && $storeCode = Mage::app()->getStore()->getCode()) {
                $targetUrl = $request->getBaseUrl(). '/' . $storeCode . '/' .$this->getTargetPath();
            }

            $this->_sendRedirectHeaders($targetUrl, $isPermanentRedirectOption);
        }

        if (Mage::getStoreConfig('web/url/use_store') && $storeCode = Mage::app()->getStore()->getCode()) {
            $targetUrl = $request->getBaseUrl(). '/' . $storeCode . '/' .$this->getTargetPath();
        }

//        NOT USED YET
//        $queryString = $this->_getQueryString();
//        if ($queryString) {
//            $targetUrl .= '?'.$queryString;
//        }

        $request->setRequestUri($targetUrl);
        $request->setPathInfo($this->getTargetPath());

        $this->incrementUseCount();

        Varien_Profiler::stop('404EVERGONE_REWRITERULE_REWRITE');
        return true;
    }

    /**
     * Add location header and disable browser page caching
     * @see Mage_Core_Model_Url_Rewrite
     *
     * @param string $url
     * @param bool $isPermanent
     */
    protected function _sendRedirectHeaders($url, $isPermanent = false)
    {
        if ($isPermanent) {
            header('HTTP/1.1 301 Moved Permanently');
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
        header('Location: ' . $url);
        exit;
    }

    public function matchPath($path)
    {
        return $this->getResource()->matchPath($this, $path);
    }

    public function validateRequestPathRegex($regex)
    {
        return $this->getResource()->validateRequestPathRegex($regex);
    }

    public function incrementUseCount()
    {
        $this->getResource()->incrementUseCount($this);
        return $this;
    }
}
