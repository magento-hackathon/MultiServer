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

        switch ($storage) {
            case self::STORAGE_MEDIA_FILE_SYSTEM:
                $model = Mage::getModel('core/file_storage_file');
                break;
            case self::STORAGE_MEDIA_DATABASE:
                $connection = (isset($params['connection'])) ? $params['connection'] : null;
                $model = Mage::getModel('core/file_storage_database', array('connection' => $connection));
                break;
            case self::STORAGE_MEDIA_SYNC_SYSTEM:
                $model = Mage::getModel('hackathon_multiserver/file_storage_sync');
                break;
            default:
                return false;
        }

        if (isset($params['init']) && $params['init']) {
            $model->init();
        }

        return $model;
    }
}