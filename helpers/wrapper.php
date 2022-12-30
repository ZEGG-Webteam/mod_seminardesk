<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_seminardesk
 *
 * @author      Benno Flory
 * @copyright   Copyright (C) 2022 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Language\LanguageHelper;

defined('_JEXEC') or die;

//require_once(JPATH_ROOT . 'components/com_seminardesk/controllers/events.php');
// include the component needed files
require_once JPATH_ROOT.'/components/com_seminardesk/helpers/data.php';
require_once JPATH_ROOT.'/components/com_seminardesk/views/events/view.html.php';

/**
 * Wrapper for com_seminardesk
 *
 * @since  3.0
 */
class ModSeminardeskWrapper
{
  
  /**
   * 
   * @return string - short language code in UC letters, e.g. 'DE', 'EN' ...
   */
  public static function getCurrentLanguageKey()
  {
    $currentLanguage = JFactory::getLanguage()->getTag();
    $languages = LanguageHelper::getLanguages('lang_code');
    return strtoupper($languages[$currentLanguage]->sef);
  }
  
  /**
   * 
   * @param array $filter
   * @return array - list of event dates, filtered by
   */
  public static function loadEventDates($filter = []) {
    JLoader::register('SeminardeskHelperData', JPATH_ROOT . '/components/com_seminardesk/helpers/data.php');
    return SeminardeskHelperData::loadEventDates($filter);
  }
  
//  public static function showEvents() {
//    $app 	= JFactory::getApplication();
////		$ctrl 		= JControllerLegacy::getInstance('SeminardeskModelEvents');
//    JLoader::register('SeminardeskModelEvents', JPATH_ROOT . '/components/com_seminardesk/models/events.php');
//    $ctrl = new SeminardeskModelEvents();
////		$model 	= JModelLegacy::getInstance('Myview', 'SeminardeskModelEvents', array('ignore_request' => true));
//    JLoader::register('SeminardeskViewEvents', JPATH_ROOT . '/components/com_seminardesk/views/events/view.html.php');
//    $model = new SeminardeskViewEvents();
//
//    $document = JFactory::getDocument();
//		$vFormat = $document->getType();/*html*/
//
//		$view = $ctrl->getView('SeminardeskViewEvents', $vFormat);
//		
//		// Push the model into the view (as default).
//		$view->setModel($model, true);
//		$view->setLayout("html");
//		$view->document = $document;
//		$view->display();
//  }
}
