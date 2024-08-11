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
   * @param array $filters - containing keys 'date', 'cat', 'org', 'term'and/or 'show_canceled'
   * @param integer $events_page - page listing all events and base for event detail pages
   * @return array - list of event dates, filtered by
   */
  public static function loadEventDates($filters = [], $events_page = 0) {
    JLoader::register('SeminardeskApiController', JPATH_ROOT . '/components/com_seminardesk/controllers/api.php');
    JLoader::register('SeminardeskDataHelper', JPATH_ROOT . '/components/com_seminardesk/helpers/data.php');
    
    // Load event dates from API or cache
    $eventDates = SeminardeskDataHelper::loadEventDates($filters);
    
    // Map detailsUrl to $events_page from module configuration
    foreach ($eventDates as $key => &$eventDate) {
      $eventDate->detailsUrl = self::getEventUrl($eventDate, $events_page);
    }
    return $eventDates;
  }
  
}
