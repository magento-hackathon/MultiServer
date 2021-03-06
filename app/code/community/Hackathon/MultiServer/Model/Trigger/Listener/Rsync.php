<?php

/**
 * Class Hackathon_MultiServer_Model_Trigger_Listener_Rsync
 *
 * Listener which updates the file on remote servers using rsync.
 * It is in proof-of-concept phase.
 */
class Hackathon_MultiServer_Model_Trigger_Listener_Rsync extends Hackathon_MultiServer_Model_Trigger_Listener_Abstract
{
    /** @var array - List of servers to sync to. */
    protected $serverList = array();
    protected $listenerType = 'rsync';

    /**
     * Constructor
     */
    public function _construct() {
        /**
         * Proof-of-Concept (POC): hard coded server list
         *
         * You need to have public-key authentication set up for this POC.
         */

        foreach ( (array) $this->getConfig()->servers as $key => $server ) {
            if ( intval( $server->active ) ) {
                $this->serverList[(string)$key] = array(
                                            'host' => (string)$server->host,
                                            'port' => (string)$server->port,
                                            'user' => (string)$server->user,
                                            'mage_root' => (string)$server->path);
            }
        }
    }

    /**
     * Listen to Magento event 'multiserver_file_change', and process it via Rsync to another server.
     *
     * @param Varien_Event_Observer $observer
     */
    public function _trigger( $observer ) {

        $filePath     = $observer->getFilePath();
        $relativeFile = $observer->getRelativeFilePath();

        foreach ( $this->serverList as $key => $serverInfo ) {
            if ( 'local' != $key ) {
                $sshTarget = $serverInfo['user'].'@'.$serverInfo['host'];
                $targetFile = $serverInfo['mage_root'] . $relativeFile;
                $remoteDir = dirname( $targetFile );

                // Make sure the dir exists via SSH.
                // There may be a way to have this done by the rsync command, didn't have the time to find it for
                // this proof-of-concept.
                $mkdirCmd = sprintf( 'ssh -p%d %s mkdir -p %s',
                                     $serverInfo['port'],
                                     $sshTarget,
                                     $remoteDir );
                shell_exec( $mkdirCmd );

                // Rsync the file
                $destination = $sshTarget.':'.$targetFile;
                $rsyncCmd = sprintf( 'rsync -azp --rsh="ssh -p%d" %s %s',
                                     $serverInfo['port'],
                                     $filePath,
                                     $destination );
                shell_exec( $rsyncCmd );
            }
        }
    }

}