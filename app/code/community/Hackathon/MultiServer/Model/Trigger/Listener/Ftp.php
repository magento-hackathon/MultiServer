<?php

class Hackathon_MultiServer_Model_Trigger_Listener_Ftp extends Hackathon_MultiServer_Model_Trigger_Listener_Abstract
{
    /** @var array - List of servers to sync to. */
    protected $serverList = array();
    protected $localMageRoot = false;

    /**
     * Constructor
     */
    public function _construct() {
        /**
         * Proof-of-Concept (POC): hard coded server list
         */
        $this->serverList['amazon_test'] = array( 'host' => '54.76.55.50',
                                                  'user' => 'magento',
                                                  'password' => 'foobar',
                                                  'mage_root' => '/home/magento' ); // no tailing slash

        $this->localMageRoot = Mage::getBaseDir('base'); // no tailing slash
    }

    /**
     * Listen to Magento event 'multiserver_file_change', and send it via ftp
     *
     * @param Varien_Event_Observer $observer
     */
    public function _trigger( $observer ) {
        $filePath   = $observer->getFilePath();    // Required, full server path

        if ( 0 === strpos( $filePath, $this->localMageRoot ) ) {
            foreach ( $this->serverList as $key => $serverInfo ) {
                if ( 'local' != $key ) {
                    $remoteFile = $serverInfo['mage_root'] . DS . basename($filePath);

                    if ($conn_id = ftp_connect($serverInfo['host']))
                    {
                        ftp_login($conn_id, $serverInfo['user'], $serverInfo['password']);

                        ftp_put($conn_id, $remoteFile, $filePath, FTP_ASCII);

                        ftp_close($conn_id);
                    }
                }
            }
        }

        return $this;
    }

}