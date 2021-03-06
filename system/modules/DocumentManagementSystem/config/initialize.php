<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2018 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cliff Parnitzky 2014-2018
 * @author     Cliff Parnitzky
 * @package    DocumentManagementSystem
 * @license    LGPL
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace ContaoDMS;
 
/**
 * Class DocumentManagementSystemInitializer
 * A utility class to initialize the system settings of the dms after installation
 */
class DocumentManagementSystemInitializer extends \Controller
{
  const DMS_BASE_DIRECTORY_KEY = "dmsBaseDirectory";
  const DMS_BASE_DIRECTORY_VALUE = "dms";
  const DMS_MAX_UPLOAD_FILE_SIZE_KEY = "dmsMaxUploadFileSize";
  const DMS_MAX_UPLOAD_FILE_SIZE_VALUE = array('unit' => 'MB', 'value' => '5');
  
  /**
   * Initialize the object
   */
  public function __construct()
  {
    parent::__construct();
  }
  
  /**
   * Run the controller
   */
  public function run()
  {
      $this->initDirectoryStructure();
      $this->initSystemSettings();
  }
  
    /**
   * Init the directory structure
   * Create structure if not exists
   */
  private function initDirectoryStructure()
  {
    if (!file_exists(TL_ROOT . '/' . $this->getDmsBaseDirectory()))
    {
      mkdir(TL_ROOT . '/' . $this->getDmsBaseDirectory(), 0775, true);
      $objDir = \Dbafs::addResource($this->getDmsBaseDirectory());
      
      $objFolder = new \Folder($this->getDmsBaseDirectory());
			$objFolder->unprotect(); 
      
      mkdir(TL_ROOT . '/' . $this->getDmsBaseDirectory() . "/temp", 0775, true);
      $objDir = \Dbafs::addResource($this->getDmsBaseDirectory() . "/temp");
    }
  }
  
  /**
   * Init the system setting
   * Set base directoy, if not set
   */
  private function initSystemSettings()
  {
    if (\Config::get(self::DMS_BASE_DIRECTORY_KEY) && \Config::get(self::DMS_MAX_UPLOAD_FILE_SIZE_KEY))
    {
      return;
    }
    
    \System::log('Running init script for setting default DMS settings, if missing.', __METHOD__, TL_CONFIGURATION);
    
    if (!\Config::get(self::DMS_BASE_DIRECTORY_KEY))
    {
      \System::log('Setting default DMS base directory to "' . $this->getDmsBaseDirectory() . '".', __METHOD__, TL_CONFIGURATION);
      
      $uuid = null;
      
      $objDatabase = \Database::getInstance();
      $objDir = $objDatabase->prepare("SELECT * FROM tl_files WHERE path=?")
              ->limit(1)
              ->execute($this->getDmsBaseDirectory());
      
      if ($objDir->next())
      {
        $uuid = \StringUtil::binToUuid($objDir->uuid);
      }
      
      if ($uuid == null)
      {
        if (file_exists(TL_ROOT . '/' . $this->getDmsBaseDirectory()))
        {
          $objDir = \Dbafs::addResource($this->getDmsBaseDirectory());
          $uuid = \StringUtil::binToUuid($objDir->uuid);
        }
        else
        {
          \System::log('Initialization of system setting for DMS failed, because default base directory does not exists.', __METHOD__, TL_ERROR);
          return;
        }
      }
      
      if ($uuid != null)
      {
        \Config::persist(self::DMS_BASE_DIRECTORY_KEY, $uuid);
      }
    }
    
    if (!\Config::get(self::DMS_MAX_UPLOAD_FILE_SIZE_KEY))
    {
      \System::log('Setting default DMS max. upload file size to "5 MB".', __METHOD__, TL_CONFIGURATION);
      \Config::persist(self::DMS_MAX_UPLOAD_FILE_SIZE_KEY, serialize(self::DMS_MAX_UPLOAD_FILE_SIZE_VALUE));
    }
  }
  
  private function getDmsBaseDirectory()
  {
    return \Config::get('uploadPath') . "/" . self::DMS_BASE_DIRECTORY_VALUE;
  }
}

/**
 * Instantiate controller
 */
$objDocumentManagementSystemInitializer = new \ContaoDMS\DocumentManagementSystemInitializer();
$objDocumentManagementSystemInitializer->run();

?>