<?php

class Hackathon_MultiServer_Model_Trigger_Listener_Rsync
{
    /**
     * Listen to Magento event 'multiserver_file_change', and process it via Rsync to another server.
     *
     * @param Varien_Event_Observer $observer
     */
    function trigger( $observer ) {
        $filePath = $observer->getFilePath();
        $changeTime = $observer->getChangeTime();
        // @TODO Finish
    }

}