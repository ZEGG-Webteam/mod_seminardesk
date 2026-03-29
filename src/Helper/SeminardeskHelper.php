<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_seminardesk
 *
 * @author      Benno Flory
 * @copyright   Copyright (C) 2022 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Module\Seminardesk\Site\Helper;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Seminardesk\Site\Helper\DataHelper;
use Joomla\Registry\Registry;

/**
 * Helper for mod_seminardesk
 *
 * @since  2.0.0
 */
class SeminardeskHelper
{
    /**
     * Get current language key
     *
     * @return  string  Short language code in UC letters, e.g. 'DE', 'EN'
     *
     * @since   2.0.0
     */
    public function getCurrentLanguageKey(): string
    {
        $app = Factory::getApplication();
        $currentLanguage = $app->getLanguage()->getTag();
        $languages = LanguageHelper::getLanguages('lang_code');
        
        return strtoupper($languages[$currentLanguage]->sef ?? 'EN');
    }

    /**
     * Get URL for SeminarDesk detail page
     *
     * @param   object   $event       Event object with id, titleSlug and eventId
     * @param   integer  $eventsPage  Page listing all events and base for event detail pages
     *
     * @return  string   URL to event detail page
     *
     * @since   2.0.0
     */
    public function getEventUrl(object $event, int $eventsPage): string
    {
        $itemid = $eventsPage ? "Itemid=" . $eventsPage . "&" : "";
        
        return Route::_(
            "index.php?" . $itemid . "option=com_seminardesk&view=event&eventId=" 
            . $event->eventId . '&slug=' . $event->titleSlug
        ) ?: ""; // Fallback if Route returns null
    }

    /**
     * Load event dates from SeminarDesk API
     *
     * @param   Registry                 $params  Module parameters
     * @param   CMSApplicationInterface  $app     Application instance
     *
     * @return  array  List of event dates
     *
     * @since   2.0.0
     */
    public function getEventDates(Registry $params, CMSApplicationInterface $app): array
    {
        // Determine language filter
        $langConfig = $params->get('lang', 'module');
        if ($langConfig === 'module') {
            $langFilter = strtolower(substr($app->getLanguage()->getTag(), 0, 2));
        } elseif ($langConfig === 'all') {
            $langFilter = '';
        } else {
            $langFilter = $langConfig;
        }

        $filters = [
            'labels' => $params->get('labels'),
            'label_exceptions' => $params->get('label_exceptions'),
            'limit' => $params->get('limit'),
            'term' => $app->getInput()->get('q', '', 'string') 
                ?: $app->getInput()->get('term', '', 'string') 
                ?: ($params->get('term') ?: ''),
            'show_canceled' => $params->get('show_canceled', false),
            'hide_ongoing' => $params->get('hide_ongoing', false),
            'lang' => $langFilter,
        ];

        $eventsPage = (int) $params->get('events_page', 0);

        // Load event dates from component's DataHelper
        $eventDates = DataHelper::loadEventDates($filters);
        
        // Map detailsUrl to events_page from module configuration
        foreach ($eventDates as &$eventDate) {
            $eventDate->detailsUrl = $this->getEventUrl($eventDate, $eventsPage);
        }
        
        return $eventDates;
    }

    /**
     * Load facilitators from SeminarDesk API
     *
     * @param   Registry  $params  Module parameters
     *
     * @return  array  List of facilitators
     *
     * @since   2.0.0
     */
    public function getFacilitators(Registry $params): array
    {
        // TODO: Implement facilitators loading when component is migrated
        return [];
    }
}
