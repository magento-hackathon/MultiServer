<?php

/**
 * Class Hackathon_MultiServer_Model_Trigger_Listener_Abstract
 *
 * Abstract class for trigger listeners.
 * The work done in this class must be as light as possible, because it is used even for listeners which are disabled.
 */
abstract class Hackathon_MultiServer_Model_Trigger_Listener_Abstract
{
    protected $localMageRoot = false;

    public function __construct() {
        if ( ! isset($this->listenerType) ) {
            Mage::throwException( 'listenerType is not set for class ' . get_class($this) );
        }
        if ( $this->isActive() ) {
            $this->_construct();
        }
        $this->localMageRoot = Mage::getBaseDir('base'); // no tailing slash
    }

    /**
     * Listen to Magento event 'multiserver_file_change', and process it via Rsync to another server.
     *
     * @param Varien_Event_Observer $observer
     * @return Hackathon_MultiServer_Model_Trigger_Listener_Abstract
     */
    public function trigger( $observer ) {
        if ( $this->isActive() ) {
            $filePath = $observer->getFilePath();
            if ( 0 === strpos( $filePath, $this->localMageRoot ) ) {
                // File is inside Magento root

                $relativeFile = substr( $filePath, strlen($this->localMageRoot) ); // starts with a slash
                $observer->setRelativeFilePath( $relativeFile );

                if ( !$observer->hasData('change_time') ) {
                    $observer->setChangeTime( microtime(true) );
                }

                if ( !$observer->hasData('action') ) {
                    $observer->setAction( 'update' );  // optional, create/update/delete, defaults to 'update'
                }

                $this->_trigger( $observer );
            }
        }
        return $this;
    }

    /**
     * @return bool|Mage_Core_Model_Config
     */
    public function getConfig() {
        if (isset($this->listenerType)) {
            $config = Mage::getConfig()->getNode('global/multiserver/' . $this->listenerType);
            return $config;
        }
        return false;
    }

    /**
     * Check if this listener is active
     *
     * @return bool
     */
    public function isActive() {
        $config = $this->getConfig();
        return (bool) intval( $config->active );
    }

}
