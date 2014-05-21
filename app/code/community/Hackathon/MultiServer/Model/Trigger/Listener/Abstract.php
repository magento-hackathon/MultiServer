<?php

abstract class Hackathon_MultiServer_Model_Trigger_Listener_Abstract
{

    public function __construct() {

        if (! isset($this->listenerType)) {
            return false;
        }

        if ( $this->isActive() ) {
            $this->_construct();
        }
    }

    /**
     * @return bool|Mage_Core_Model_Config
     */
    public function getConfig()
    {

        if (isset($this->listenerType)) {

            $config = Mage::getConfig()->getNode('global/multiserver/' . $this->listenerType);
            return $config;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isActive() {

        $config = $this->getConfig();
        return (bool) $config->active;
    }

}
