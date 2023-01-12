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
   * Get url for SeminarDesk detail page - see com_seminardesk: SeminardeskHelperData::getEventUrl()
   * 
   * @param stdClass $event - must contain id, titleSlug and eventId
   * @param integer $events_page - page listing all events and base for event detail pages
   * @return string URL to event detail page
   */
  public static function getEventUrl($event, $events_page)
  {
    $itemid = $events_page?"Itemid=" . $events_page . "&":"";
    return JRoute::_("index.php?" . $itemid . "option=com_seminardesk&view=event&eventId=" . $event->eventId . '&slug=' . $event->titleSlug);
  }
  
  /**
   * 
   * @param array $filter
   * @param integer $events_page - page listing all events and base for event detail pages
   * @return array - list of event dates, filtered by
   */
  public static function loadEventDates($filter = [], $events_page = 0) {
    JLoader::register('SeminardeskHelperData', JPATH_ROOT . '/components/com_seminardesk/helpers/data.php');
    $eventDates = SeminardeskHelperData::loadEventDates($filter, $events_page);
    //-- Map detailsUrl to $events_page from module configuration
    foreach ($eventDates as $key => &$eventDate) {
      $eventDate->detailsUrl = self::getEventUrl($eventDate, $events_page);
    }    
    return $eventDates;
  }
  
//  public static function showEvents() {
//    $app 	= JFactory::getApplication();
////		$ctrl 		= JControllerLegacy::getInstance('SeminardeskModelEvents');
////    JLoader::register('SeminardeskModelEvents', JPATH_ROOT . '/components/com_seminardesk/models/events.php');
////    $ctrl = new SeminardeskModelEvents();
//    JLoader::register('SeminardeskController', JPATH_ROOT . '/components/com_seminardesk/controller.php');
//    $ctrl = new SeminardeskController();
////		$model 	= JModelLegacy::getInstance('Myview', 'SeminardeskModelEvents', array('ignore_request' => true));
//    JLoader::register('SeminardeskViewEvents', JPATH_ROOT . '/components/com_seminardesk/views/events/view.html.php');
//    $view = new SeminardeskViewEvents();
//
//    $document = JFactory::getDocument();
//		$vFormat = $document->getType();/*html*/
//
////		$view = $ctrl->getView('SeminardeskViewEvents', $vFormat);
//		
//		// Push the model into the view (as default).
//		$view->setModel($model, true);
//		$view->setLayout("html");
//		$view->document = $document;
//		$view->display();
////    die(json_encode($view));
//  }
}
