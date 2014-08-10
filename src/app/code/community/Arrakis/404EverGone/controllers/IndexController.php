<?php
require_once 'Mage/Cms/controllers/IndexController.php';

/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_IndexController extends Mage_Cms_IndexController
{
    /**
     * Redirect to a new URL if a matching rewrite rule is found or render a 404 Not Found page
     *
     * @param string $coreRoute
     */
    public function noRouteAction($coreRoute = null)
    {
        if (Mage::getStoreConfigFlag('advanced/modules_disable_output/' . $this->_getRealModuleName())) {
            return parent::noRouteAction($coreRoute);
        }

        $rule = Mage::getModel('arrakis_404evergone/rewriterule');
        if (!$rule->rewrite($this->getRequest(), $this->getResponse())) {
            return parent::noRouteAction($coreRoute);
        }

        // If we get there, it means that rewrite() found a match but did not send a Location header (HTTP 301 or 302),
        // so we must reset the request internal data and dispatch it again
        $this->getRequest()->setModuleName(null)
            ->setControllerName(null)
            ->setActionName(null);
        Mage::app()->getFrontController()->dispatch();
    }
}
