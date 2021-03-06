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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_dms_categories']['name']                          = array('Name', 'Geben Sie den Namen der Kategorie an.');
$GLOBALS['TL_LANG']['tl_dms_categories']['description']                   = array('Beschreibung', 'Geben Sie eine Beschreibung für diese Kategorie an.');
$GLOBALS['TL_LANG']['tl_dms_categories']['general_read_permission']       = array('Grundsätzliches Leserecht', 'Geben Sie das grundsätzliche Leserecht für Dokumente dieser Kategorie an.');
$GLOBALS['TL_LANG']['tl_dms_categories']['general_manage_permission']     = array('Grundsätzliche Verwaltungsrechte', 'Geben Sie die grundsätzlichen Verwaltungsrechte für Dokumente dieser Kategorie an.');
$GLOBALS['TL_LANG']['tl_dms_categories']['file_types']                    = array('Erlaubte Dateitypen', 'Geben Sie durch Komma getrennt die Dateitypen an, für die ein Upload gestattet ist. Die Liste wird beim Speichern automatisch sortiert und alle Dateitypen werden in Kleinbuchstaben konvertiert.');
$GLOBALS['TL_LANG']['tl_dms_categories']['file_types_inherit']            = array('Vererbung der erlaubten Dateitypen', 'Geben Sie an, ob die erlaubten Dateitypen <u>auch</u> von den Oberkategorie(n) geerbt werden sollen.');
$GLOBALS['TL_LANG']['tl_dms_categories']['publish_documents_per_default'] = array('Standardmäßige Veröffentlichung', 'Geben Sie an, ob die in diese Kategorie hochgeladenen Dokumente standardmäßig veröffentlicht werden sollen. Ist die standardmäßige Veröffentlichung für diese Kategorie aktiviert wird die Checkbox zum Veröffentlichen im Verwaltungsmodul (Frontend) immer angekreuzt.');
$GLOBALS['TL_LANG']['tl_dms_categories']['cssID']                         = array('CSS-ID/Klasse', 'Geben Sie eine ID und beliebig viele Klassen ein.');
$GLOBALS['TL_LANG']['tl_dms_categories']['published']                     = array('Kategorie veröffentlichen', 'Geben Sie an ob die Kategorie veröffentlicht sein soll.');
$GLOBALS['TL_LANG']['tl_dms_categories']['start']                         = array('Anzeigen ab', 'Geben Sie den Tag an ab dem die Kategorie veröffentlicht sein soll.');
$GLOBALS['TL_LANG']['tl_dms_categories']['stop']                          = array('Anzeigen bis', 'Geben Sie den Tag an bis zu dem die Kategorie veröffentlicht sein soll.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_dms_categories']['category_legend']  = 'Kategorie';
$GLOBALS['TL_LANG']['tl_dms_categories']['documents_legend'] = 'Dokumente';
$GLOBALS['TL_LANG']['tl_dms_categories']['rights_legend']    = 'Rechte';
$GLOBALS['TL_LANG']['tl_dms_categories']['expert_legend']    = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_dms_categories']['publish_legend']   = 'Veröffentlichung';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_dms_categories']['general_read_permission_option'][\Category::GENERAL_READ_PERMISSION_ALL]                 = array('Leserecht für alle Mitglieder', 'Alle Mitglieder haben uneingeschränktes Leserecht in dieser Kategorie. Sie müssen dazu <u>nicht</u> angemeldet sein.');
$GLOBALS['TL_LANG']['tl_dms_categories']['general_read_permission_option'][\Category::GENERAL_READ_PERMISSION_LOGGED_USER]         = array('Leserecht für angemeldete Mitglieder', 'Nur <u>angemeldete</u> Mitglieder haben uneingeschränktes Leserecht in dieser Kategorie.');
$GLOBALS['TL_LANG']['tl_dms_categories']['general_read_permission_option'][\Category::GENERAL_READ_PERMISSION_CUSTOM]              = array('Spezielle Leserechte für einzelne Mitgliedergruppen', 'Es werden für diese Kategorie spezielle Leserechte für einzelne Mitgliedergruppen vergeben (im Bereich Zugriffsrechte).');
$GLOBALS['TL_LANG']['tl_dms_categories']['general_read_permission_option'][\Category::GENERAL_READ_PERMISSION_INHERIT]             = array('Vererbung der Leserechte durch Oberkategorie(n)', 'Es werden für diese Kategorie die Leserechte der Oberkategorie(n) verwendet.');
$GLOBALS['TL_LANG']['tl_dms_categories']['general_manage_permission_option'][\Category::GENERAL_MANAGE_PERMISSION_LOGGED_USER]     = array('Alle Verwaltungsrechte für angemeldete Mitglieder', 'Alle <u>angemeldeten</u> Mitglieder haben uneingeschränktes Verwaltungsrechte in dieser Kategorie.');
$GLOBALS['TL_LANG']['tl_dms_categories']['general_manage_permission_option'][\Category::GENERAL_MANAGE_PERMISSION_CUSTOM]          = array('Spezielle Verwaltungsrechte für einzelne Mitgliedergruppen', 'Es werden für diese Kategorie spezielle Verwaltungsrechte für einzelne Mitgliedergruppen vergeben (im Bereich Zugriffsrechte).');
$GLOBALS['TL_LANG']['tl_dms_categories']['general_manage_permission_option'][\Category::GENERAL_MANAGE_PERMISSION_INHERIT]         = array('Vererbung der Verwaltungsrechte durch Oberkategorie(n)', 'Es werden für diese Kategorie die Verwaltungsrechte der Oberkategorie(n) verwendet.');
$GLOBALS['TL_LANG']['tl_dms_categories']['publish_documents_per_default_option'][\Category::PUBLISH_DOCUMENTS_PER_DEFAULT_DISABLE] = array('Keine standardmäßige Veröffentlichung', 'Die in diese Kategorie hochgeladenen Dokumente werden standardmäßig <u>nicht</u> veröffentlicht. Die Checkbox zum Veröffentlichen im Verwaltungsmodul (Frontend) ist <u>nicht</u> angekreuzt (ggf. abhängig von Definition in System Einstellungen, Modulen und Mitgliedergruppen).');
$GLOBALS['TL_LANG']['tl_dms_categories']['publish_documents_per_default_option'][\Category::PUBLISH_DOCUMENTS_PER_DEFAULT_ENABLE]  = array('Dokumente standardmäßig veröffentlichen', 'Die in diese Kategorie hochgeladenen Dokumente <u>werden standardmäßig veröffentlicht</u>. Die Checkbox zum Veröffentlichen im Verwaltungsmodul (Frontend) ist <u>immer</u> angekreuzt.');
$GLOBALS['TL_LANG']['tl_dms_categories']['publish_documents_per_default_option'][\Category::PUBLISH_DOCUMENTS_PER_DEFAULT_INHERIT] = array('Vererbung der standardmäßigen Veröffentlichung durch Oberkategorie(n)', 'Es wird für diese Kategorie die standardmäßige Veröffentlichung der Oberkategorie(n) verwendet.');
$GLOBALS['TL_LANG']['tl_dms_categories']['file_types_inherit_option']['ACTIVE']                                                    = array('Vererbung der erlaubten Dateitypen auch von den Oberkategorie(n)');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_dms_categories']['new']        = array('Neue Kategorie', 'Eine neue Kategorie anlegen');
$GLOBALS['TL_LANG']['tl_dms_categories']['show']       = array('Kategoriedetails', 'Details der Kategorie ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_dms_categories']['edit']       = array('Kategorie bearbeiten', 'Kategorie ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_dms_categories']['cut']        = array('Kategorie verschieben', 'Kategorie ID %s verschieben');
$GLOBALS['TL_LANG']['tl_dms_categories']['copy']       = array('Kategorie duplizieren', 'Kategorie ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_dms_categories']['copyChilds'] = array('Kategorie mit Unterkategorien duplizieren', 'Kategorie ID %s inklusive Unterkategorien duplizieren');
$GLOBALS['TL_LANG']['tl_dms_categories']['delete']     = array('Kategorie löschen', 'Kategorie ID %s löschen');
$GLOBALS['TL_LANG']['tl_dms_categories']['delete_']    = array('Kategorie nicht löschbar', 'Die Kategorie ID %s ist nicht löschbar, weil sie oder eine ihrer Unterkategorien Dokumente enthält.');
$GLOBALS['TL_LANG']['tl_dms_categories']['pasteafter'] = array('Einfügen nach', 'Nach der Kategorie ID %s einfügen');
$GLOBALS['TL_LANG']['tl_dms_categories']['pasteinto']  = array('Einfügen in', 'In die Kategorie ID %s einfügen');
$GLOBALS['TL_LANG']['tl_dms_categories']['toggle']     = array('Kategorie veröffentlichen/unveröffentlichen', 'Kategorie ID %s veröffentlichen/unveröffentlichen');

?>