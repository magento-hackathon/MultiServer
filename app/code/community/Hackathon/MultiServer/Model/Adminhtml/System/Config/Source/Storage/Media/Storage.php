<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 21-5-14
 * Time: 11:47
 */ 
class Hackathon_MultiServer_Model_Adminhtml_System_Config_Source_Storage_Media_Storage extends Mage_Adminhtml_Model_System_Config_Source_Storage_Media_Storage
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        $options[] = array(
            'value' => Hackathon_MultiServer_Model_Core_File_Storage::STORAGE_MEDIA_SYNC_SYSTEM,
            'label' => Mage::helper('hackathon_multiserver')->__('Sync files to servers')
        );

        return $options;
    }
}