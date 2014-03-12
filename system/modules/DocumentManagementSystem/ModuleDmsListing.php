<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
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
 * @copyright  Cliff Parnitzky 2014
 * @author     Cliff Parnitzky
 * @package    DocumentManagementSystem
 * @license    LGPL
 * @filesource [dokmansystem] by Thomas Krueger
 */

/**
 * Class ModuleDmsListing
 *
 * @copyright  Cliff Parnitzky 2014
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class ModuleDmsListing extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_dms_listing';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### DOCUMENT MANAGEMENT SYSTEM - LISTING ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		/* set custom template if defined */
		if ($this->dmsTemplate != $strTemplate)
		{
			$this->strTemplate = $this->dmsTemplate;
			$this->Template = new \FrontendTemplate($this->strTemplate);
			$this->Template->setData($this->arrData);
		}
		
		$dmsLoader = DmsLoader::getInstance();
		
		$formId = "dms_listing_" . $this->id;
		 
		$arrErrors = array();
		$arrExpandedCategories = array();
		$strSearchText = "";
		
		if ($this->Input->post('FORM_SUBMIT') == $formId)
		{
			/* remember expanded catagories */
			if (is_array($this->Input->post('expandedCatagories')))
			{
				$arrExpandedCategories = $this->Input->post('expandedCatagories');
			}
			
			$strSearchText = $this->Input->post('searchText');
			
			/* handle download */
			$docId = $this->Input->post('docId');
			if ($docId != '')
			{
				if (is_numeric($docId))
				{
					$document = $dmsLoader->loadDocument($docId); // with path to Root Category .... hier sowieso gesetzt
					if ($document != null)
					{
						// TODO (#9) : check read permissions
						
						// Send the file to the browser
						$file = DmsConfig::getDocumentFilePath($document->getFileNameVersioned());
						if (file_exists(TL_ROOT . '/' . $file))
						{
							$this->sendFileToBrowser($file);
						}
						else
						{
							$arrErrors[] = $GLOBALS['TL_LANG']['DMS']['ERR']['download_document_not_found'];
						}
					}
					else
					{
						$arrErrors[] = $GLOBALS['TL_LANG']['DMS']['ERR']['download_document_not_found'];
					}
				}
				else
				{
					$arrErrors[] = $GLOBALS['TL_LANG']['DMS']['ERR']['download_document_illegal_parameter'];
				}
			}
		}
		
		// Prepare paramters for loader
		$params = new DmsLoaderParams();
		// TODO (#9) set a custom ROOT node id here --> module config
		$params->rootCategoryId = 0;
		$params->loadRootCategory = false;
		$params->loadAccessRights = true;
		$params->loadDocuments = true;
		$params->documentSearchText = $strSearchText;
		// TODO (#9) get the search type from form here (via Drop Down)
		$params->documentSearchType = DmsLoaderParams::DOCUMENT_SEARCH_LIKE;
		
		$arrCategories = $dmsLoader->loadCategories($params);
		// apply the read permissions, to only show valid categories
		$arrCategories = $this->applyReadPermissionsToCategories($arrCategories);
		// flatten the tree structure (easier to use in template)
		$arrCategories = DmsLoader::flattenCategories($arrCategories);
		
		// add all needed values to template
		$this->Template->categories = $arrCategories;
		$this->Template->hideEmptyCategories = $this->dmsHideEmptyCategories;
		$this->Template->hideLockedCategories = $this->dmsHideLockedCategories;
		$this->Template->formId = $formId;
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->errors = $arrErrors;
		
		// add collected post data
		$this->Template->expandedCategories = $arrExpandedCategories;
		$this->Template->searchText = $strSearchText;
	}
	
	/**
	 * Apply the read permissions to the categories.
	 *
	 * @param	arr	$arrCategories	The structured array of categories.
	 * @return	array	Returns a reduced array of categories (depends on the read permissions).
	 */
	private function applyReadPermissionsToCategories(Array $arrCategories)
	{
		$arrSecureCategories = $arrCategories;
		foreach ($arrSecureCategories as $category)
		{
			if (!$category->isPublished() || ($this->dmsHideEmptyCategories && !$category->hasDocuments()) || ($this->dmsHideLockedCategories && !$category->isReadableForCurrentMember()))
			{
				unset($arrSecureCategories[$category->id]);
			}
			else if ($category->hasSubCategories())
			{
				$arrSecureSubCategories = $this->applyReadPermissionsToCategories($category->subCategories);
				$category->subCategories = $arrSecureSubCategories;
			}
		}
		return $arrSecureCategories;
	}
}

?>