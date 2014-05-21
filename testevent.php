<?php

// php -d xdebug.remote_autostart=1 testevent.php

require_once dirname(__FILE__).'/app/Mage.php';
Mage::app('admin');

Mage::dispatchEvent( 'multiserver_file_change',
                     array( 'file_path' => '/Users/jeroen/Code/MultiServer/media/dhl/logo.jpg',
                            'change_time' => microtime(true), // optional
                            'action' => 'update' ) );