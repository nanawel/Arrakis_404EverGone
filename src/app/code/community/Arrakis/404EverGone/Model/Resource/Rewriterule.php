<?php
/**
 * 4-o-4ever Gone!
 * URL Rewrite rules for Magento 404
 *
 * @category   Arrakis
 * @package    Arrakis_404EverGone
 * @author     Anael Ollier <nanawel {at} gmail NOSPAM {dot} com>
 */
class Arrakis_404EverGone_Model_Resource_Rewriterule extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('arrakis_404evergone/rewriterule', 'rule_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        // Prevent overwriting counter
        $object->unsUseCount();

        return $this;
    }


    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Arrakis_404EverGone_Model_Rewriterule $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        /** @var $select Varien_Db_Select */
        $select = parent::_getLoadSelect($field, $value, $object);

        if (!is_null($object->getStoreId())) {
            $select->where('store_id IN(?)', array(Mage_Core_Model_App::ADMIN_STORE_ID, $object->getStoreId()));
            $select->order('store_id ' . Varien_Db_Select::SQL_DESC);
            $select->limit(1);
        }
        return $select;
    }
    
    /**
     * Load first matching rule
     *
     * @param Arrakis_404EverGone_Model_Rewriterule $object
     * @param string $request
     */
    public function loadByRequest($object, $request)
    {
        $adapter = $this->_getReadAdapter();

        $select = $adapter->select()
                    ->from($this->getMainTable())
                    ->where(':request_path REGEXP request_path_regex')
                    ->where('store_id IN(?)', array(Mage_Core_Model_App::ADMIN_STORE_ID, $object->getStoreId()))
                    ->order('store_id ' . Varien_Db_Select::SQL_DESC)
                    ->limit(1);

        $binds = array(
            'request_path' => $request,
        );

        $result = $adapter->fetchRow($select, $binds);
        
        if (isset($result['rule_id'])) {
            return $this->load($object, $result['rule_id']);
        }
        return $this;
    }

    /**
     * Note: doesn't seem to be really useful as MySQL doesn't check for the validity of the given regexp
     *       (it would just not match anything)
     *
     * @param string $regex
     * @return bool
     */
    public function validateRequestPathRegex($regex)
    {
        try {
            $adapter = $this->_getReadAdapter();
            $adapter->query('SELECT "dummy string" REGEXP ' . $adapter->quote($regex));
        }
        catch (Exception $e) {
            Mage::throwException(
                Mage::helper('arrakis_404evergone')->__('Invalid regular expression (MySQL returned: "%s")',
                $e->getMessage())
            );
        }
        return true;
    }

    /**
     *
     * @param Arrakis_404EverGone_Model_Rewriterule $object
     * @param string $path
     * @return bool
     */
    public function matchPath($object, $path)
    {
        $adapter = $this->_getReadAdapter();
        $stmt = $adapter->query('SELECT ' . $adapter->quote($path) . ' REGEXP ' . $adapter->quote($object->getRequestPathRegex()) . ' AS result');
        $result = $stmt->fetch();
        return isset($result['result']) && $result['result'] == 1;
    }

    /**
     *
     * @param Arrakis_404EverGone_Model_Rewriterule $object
     */
    public function incrementUseCount($object)
    {
        if ($object->getId()) {
            $adapter = $this->_getReadAdapter();
            $adapter->update(
               $this->getMainTable(),
                array('use_count' => new Zend_Db_Expr('use_count + 1')),
                'rule_id = ' . $adapter->quote($object->getId())
            );
        }
        return $this;
    }
}
