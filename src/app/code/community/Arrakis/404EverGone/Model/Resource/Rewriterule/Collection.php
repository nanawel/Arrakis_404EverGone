<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_Model_Resource_Rewriterule_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize collection
     *
     */
    public function _construct()
    {
        $this->_init('arrakis_404evergone/rewriterule');
    }

    /**
     * Redefine default filters
     *
     * @param string $field
     * @param mixed $condition
     * @return Varien_Data_Collection_Db
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == 'stores') {
            return $this->addStoresFilter($condition);
        } else {
            return parent::addFieldToFilter($field, $condition);
        }
    }

    /**
     * Add store filter
     *
     * @deprecated
     * @param mixed $store
     * @return Mage_Poll_Model_Resource_Poll_Collection
     */
    public function addStoresFilter($store)
    {
        return $this->addStoreFilter($store);
    }

    /**
     * Add Stores Filter
     *
     * @param mixed $storeId
     * @param bool  $withAdmin
     * @return Mage_Poll_Model_Resource_Poll_Collection
     */
    public function addStoreFilter($storeId, $withAdmin = true)
    {
        //FIXME $withAdmin
        $this->getSelect()
            ->where('main_table.store_id = ?', Mage::app()->getStore($storeId)->getId());

        return $this;
    }
}
