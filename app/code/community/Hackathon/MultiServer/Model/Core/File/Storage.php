<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 21-5-14
 * Time: 11:28
 */ 
class Hackathon_MultiServer_Model_Core_File_Storage extends Mage_Core_Model_File_Storage
{
    const STORAGE_MEDIA_SYNC_SYSTEM         = 2;

    public function getStorageModel($storage = null, $params = array())
    {
        if (is_null($storage)) {
            $storage = Mage::helper('core/file_storage')->getCurrentStorageCode();
        }

        // we will init from this method, make sure the parent never inits
        $params_init = (isset($params['init']) && $params['init']) ? true : false ;
        unset($params['init']);

        // use the parent to get the model following core rules
        $model = parent::getStorageModel($storage, $params);

        switch ($storage) // but overwrite if our awesome storage engine should be used!
        {
            case self::STORAGE_MEDIA_SYNC_SYSTEM:
                $model = Mage::getModel('hackathon_multiserver/file_storage_sync');
                break;
        }

        if ($params_init) // init if requested via param
        {
            $model->init();
        }

        return $model;
    }
}