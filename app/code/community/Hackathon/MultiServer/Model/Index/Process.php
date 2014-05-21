<?php
/**
 * Created by PhpStorm.
 * User: alexanderhuyghebaert
 * Date: 21/05/14
 * Time: 15:44
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
            if (is_file($file)) {
                $this->_lockFile = fopen($file, 'w');
            } else {
                $this->_lockFile = fopen($file, 'x');
            }
            fwrite($this->_lockFile, date('r'));
            Mage::dispatchEvent('multiserver_file_change',array('file_path' => $file, 'change_time' => time()));
        }
        return $this->_lockFile;
    }
}