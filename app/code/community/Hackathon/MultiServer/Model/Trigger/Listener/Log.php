<?php

class Hackathon_MultiServer_Model_Trigger_Listener_Log
{

    protected $logFile = false;

    public function __construct() {
        $this->logFile = Mage::getBaseDir('log') . '/file_change.log';
    }

    /**
     * Listen to Magento event 'multiserver_file_change', and process it via Rsync to another server.
     *
     * @param Varien_Event_Observer $observer
     */
    public function trigger( $observer ) {
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