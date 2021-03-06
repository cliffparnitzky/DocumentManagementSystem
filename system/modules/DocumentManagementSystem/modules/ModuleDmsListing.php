<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2015 Leo Feyer
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
 * @copyright  Cliff Parnitzky 2014-2015
 * @author     Cliff Parnitzky
 * @package    DocumentManagementSystem
 * @license    LGPL
 * @filesource [dokmansystem] by Thomas Krueger
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace ContaoDMS;

/**
 * Class ModuleDmsListing
 *
 * @copyright  Cliff Parnitzky 2014-2015
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class ModuleDmsListing extends \Module
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
			$objTemplate = new \BackendTemplate('be_wildcard');

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
		
		$dmsLoader = \DmsLoader::getInstance();
		$params = new \DmsLoaderParams();
		// Prepare paramters for loader
		$params->rootCategoryId = $this->dmsStartCategory;
		$params->includeRootCategory = $this->dmsStartCategoryIncluded;
		$params->loadRootCategory = true; // get complete path to root, for checking inherited access rights;
		$params->loadAccessRights = true;
		$params->loadDocuments = true;
		
		$formId = "dms_listing_" . $this->id;
		 
		$arrMessages = array('errors' => array(), 'warnings' => array(), 'successes' => array(), 'infos' => array());
		$arrExpandedCategories = array();
		$strLastExpandedCollapsedCategory = "";
		$strLastExpandedCollapsedCategoryPosition = "";
		$strSearchText = "";
		$strSearchType = $this->dmsDefaultSearchType;
		
		if ($this->Input->post('FORM_SUBMIT') == $formId)
		{
			/* remember expanded catagories */
			if (is_array($this->Input->post('expandedCatagories')))
			{
				$arrExpandedCategories = $this->Input->post('expandedCatagories');
			}
			$strLastExpandedCollapsedCategory = $this->Input->post('lastExpandedCollapsedCategory');
			$strLastExpandedCollapsedCategoryPosition = $this->Input->post('lastExpandedCollapsedCategoryPosition');
			
			$strSearchText = $this->Input->post('searchText');
			$strSearchType = $this->Input->post('searchType');
			
			/* handle download */
			$docId = $this->Input->post('docId');
			if ($docId != '')
			{
				if (is_numeric($docId))
				{
					$params->loadCategory = true; // need the category of the document to check access rights
					$document = $dmsLoader->loadDocument($docId, $params); // with path to Root Category .... hier sowieso gesetzt
					$params->loadCategory = false;
					
					if ($document != null)
					{
						if (!$document->isPublished())
						{
							$arrMessages['errors'][] = $GLOBALS['TL_LANG']['DMS']['ERR']['download_document_not_published'];
						}
						else if (!$document->category->isPublished() || !$document->category->isReadableForCurrentMember())
						{
							$arrMessages['errors'][] = $GLOBALS['TL_LANG']['DMS']['ERR']['download_document_not_allowed'];
						}
						else
						{
							// Send the file to the browser
							if (!\DmsUtils::sendDocumentFileToBrowser($document))
							{
								$arrMessages['errors'][] = $GLOBALS['TL_LANG']['DMS']['ERR']['download_file_not_found'];
							}
						}
					}
					else
					{
						$arrMessages['errors'][] = $GLOBALS['TL_LANG']['DMS']['ERR']['download_document_not_found'];
					}
				}
				else
				{
					$arrMessages['errors'][] = $GLOBALS['TL_LANG']['DMS']['ERR']['download_document_illegal_parameter'];
				}
			}
		}
		
		$params->documentSearchText = $strSearchText;
		$params->documentSearchType = $strSearchType;
		
		$arrCategories = $dmsLoader->loadCategories($params);
		// apply the read permissions, to only show valid categories
		$arrCategories = $this->applyReadPermissionsToCategories($arrCategories);
		// flatten the tree structure (easier to use in template)
		$arrCategories = \DmsLoader::flattenCategories($arrCategories);
		
		// add all needed values to template
		$this->Template->categories = $arrCategories;
		$this->Template->hideEmptyCategories = $this->dmsHideEmptyCategories;
		$this->Template->hideLockedCategories = $this->dmsHideLockedCategories;
		$this->Template->formId = $formId;
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->messages = $arrMessages;
		
		// add collected post data
		$this->Template->expandedCategories = $arrExpandedCategories;
		$this->Template->lastExpandedCollapsedCategory = $strLastExpandedCollapsedCategory;
		$this->Template->lastExpandedCollapsedCategoryPosition = $strLastExpandedCollapsedCategoryPosition;
		$this->Template->searchText = $strSearchText;
		$this->Template->searchType = $strSearchType;
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
			if (!$category->isPublished() || ($this->dmsHideEmptyCategories && !$category->hasPublishedDocuments()) || ($this->dmsHideLockedCategories && !$category->isReadableForCurrentMember()))
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
