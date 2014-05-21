MultiServer
===========

Purpose: synchronise file changes among multiple servers.


Testing
===========
* Works for upload files in WYSIWYG editor
* Works for favicon upload
* Works for Import/Export -> Dataflow Profiles -> Export files

Compatibility
===========
This extension relies heavily on rewritten lib/Varien classes (yes it's ugly, we know). No code was changed but event dispatchers were added.
For < 1.9.x Magento version please add the dispatchers to the classes/methods that write out to files.

**Dispatch code**

` Mage::dispatchEvent('multiserver_file_change', array('file_path' => [full file path], 'change_time' => time()));`

**Magento classes::method writing out in 1.9.x**

- `Varien_File_Csv::saveData`
- `Varien_File_Uploader::save`
- `Varien_Image_Adapter_Gd2::save`
- `Varien_Io_File::streamClose`
- `Varien_Io_File::write`
- `Varien_Io_File::mv`
- `Varien_Io_File::cp`
