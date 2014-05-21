<?php

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
