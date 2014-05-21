<?php

/**
 * Class Hackathon_MultiServer_Model_Trigger_Listener_Abstract
 *
 * Abstract class for trigger listeners.
 * The work done in this class must be as light as possible, because it is used even for listeners which are disabled.
 */
abstract class Hackathon_MultiServer_Model_Trigger_Listener_Abstract
{

    public function __construct() {
        if ( $this->isActive() ) {
            $this->_construct();
        }
    }

    /**
     * @return bool
     */
    public function isActive() {
        // @TODO
        return true;
    }

}
