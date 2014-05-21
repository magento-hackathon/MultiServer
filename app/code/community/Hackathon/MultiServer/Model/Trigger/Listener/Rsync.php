<?php

class Hackathon_MultiServer_Model_Trigger_Listener_Rsync
{
    /** @var array - List of servers to sync to. */
    protected $serverList = array();

    /**
     * Constructor
     */
    public function __construct() {
        /**
         * Proof-of-Concept (POC): hard coded server list
         *
         * You need to have public-key authentication set up for this POC.
         */
        $this->serverList['local']       = array( 'mage_root' => Mage::getBaseDir('base') );  // no tailing slash
        $this->serverList['amazon_test'] = array( 'host' => '54.76.55.50',
                                                  'port' => 22,
                                                  'user' => 'magento',
                                                  'mage_root' => '/home/magento' ); // no tailing slash
    }

    /**
     * Listen to Magento event 'multiserver_file_change', and process it via Rsync to another server.
     *
     * @param Varien_Event_Observer $observer
     */
    function trigger( $observer ) {
        $filePath   = $observer->getFilePath();    // Required, full server path
        $changeTime = $observer->getChangeTime();  // optional
        $action     = $observer->getAction();      // optional, create/update/delete, defaults to 'update'

        if ( empty($changeTime) ) {
            $changeTime = microtime( true );
        }
        if ( empty($action) ) {
            $action = 'update';
        }

        $localRoot = $this->serverList['local']['mage_root'];
        if ( 0 === strpos( $filePath, $localRoot ) ) {
            // File is inside Magento root
            $relativeFile = substr( $filePath, strlen($localRoot) ); // starts with a slash
            foreach ( $this->serverList as $key => $serverInfo ) {
                if ( 'local' != $key ) {
                    $sshTarget = $serverInfo['user'].'@'.$serverInfo['host'];
                    $targetFile = $serverInfo['mage_root'] . $relativeFile;
                    $remoteDir = dirname( $targetFile );

                    // Make sure the dir exists via SSH.
                    $mkdirCmd = sprintf( 'ssh -p%d %s mkdir -p %s',
                                         $serverInfo['port'],
                                         $sshTarget,
                                         $remoteDir );
                    shell_exec( $mkdirCmd );

                    // Rsync the file
                    $destination = $sshTarget.':'.$targetFile;
                    $rsyncCmd = sprintf( 'rsync -azp --rsh="/usr/bin/ssh -p%d" %s %s',
                                         $serverInfo['port'],
                                         $filePath,
                                         $destination );
                    shell_exec( $rsyncCmd );
                }
            }
        }
    }

}