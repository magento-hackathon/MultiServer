<?php

/**
 * Class Hackathon_MultiServer_Model_Trigger_Listener_Log
 *
 * Proof of concept trigger listener which just logs the change to a file.
 * Also very handy for development and debugging.
 */
class Hackathon_MultiServer_Model_Trigger_Listener_Log extends Hackathon_MultiServer_Model_Trigger_Listener_Abstract
{
    protected $listenerType = 'log';
    protected $logFile = false;

    public function _construct() {
        $this->logFile = Mage::getBaseDir('log') . DS . $this->getConfig()->filename;
    }

    /**
     * Listen to Magento event 'multiserver_file_change', and process it via Rsync to another server.
     *
     * @param Varien_Event_Observer $observer
     */
    public function _trigger( $observer ) {
        $filePath   = $observer->getFilePath();    // Required, full server path
        $changeTime = $observer->getChangeTime();  // optional
        $action     = $observer->getAction();      // optional, create/update/delete, defaults to 'update'

        if ( empty($changeTime) ) {
            $changeTime = microtime( true );
        }
        if ( empty($action) ) {
            $action = 'update';
        }

        $line = sprintf( "[%s] %s: %s\n", date('r',$changeTime), $action, $filePath );

        // We don't call Mage::log here to keep it low level and prevent endless change loop.
        file_put_contents( $this->logFile, $line, FILE_APPEND );
    }
}