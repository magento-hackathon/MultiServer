<?php

/**
 * Class Hackathon_MultiServer_Model_Index_Process
 *
 * Rewrite for class 'Mage_Index_Model_Process', to catch lock file updates.
 * @TODO This is not complete, the actual locking is not done cross-server.
 */
class Hackathon_MultiServer_Model_Index_Process extends Mage_Index_Model_Process {

    /**
     * Get lock file resource
     *
     * @return resource
     */
    protected function _getLockFile()
    {
        if ($this->_lockFile === null) {
            $varDir = Mage::getConfig()->getVarDir('locks');
            $file = $varDir . DS . 'index_process_'.$this->getId().'.lock';
            parent::_getLockFile();
            Mage::dispatchEvent( 'multiserver_file_change', array( 'file_path' => $file,
                                                                   'change_time' => microtime(true) ) );
        }
        return $this->_lockFile;
    }
}