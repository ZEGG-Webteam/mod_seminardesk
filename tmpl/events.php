<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_seminardesk
 *
 * @author      Benno Flory
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
JHTML::_('behavior.modal');

//-- Load configs
$show_months = $params->get('show_months');
$text_before = $params->get('text_before');
$text_after = $params->get('text_after');
$direct_booking = $params->get('direct_booking');

//-- Load CSS / JS
$document  = JFactory::getDocument();
$document->addStyleSheet('/modules/mod_seminardesk/assets/css/styles.css');
$document->addScript('/modules/mod_seminardesk/assets/js/scripts.js');
if ($direct_booking) {
  $document->addStyleSheet('/media/com_seminardesk/css/styles.css');
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

//-- Load filtered events
$filters = [
  'labels' => $params->get('labels'),
  'limit' => $params->get('limit'),
  'term' => $app->input->get('q')?:$app->input->get('term')?:'',
];
$eventDates = ModSeminardeskWrapper::loadEventDates($filters, $params->get('events_page'));
$anyEventMatching = false;
$previous_event_month = '';
?>
<?php if ($text_before) : ?>
<div class="sd-events-text-before">
  <?= $text_before; ?>
</div>
<?php endif; ?>
<div class="sd-module sd-events<?php echo ($moduleclass_sfx)?' sd-events'.$moduleclass_sfx:''; ?>">
  <?php if ($eventDates) : ?>
    <?php foreach($eventDates as $eventDate) : ?>
      <?php
      //-- Month headings?
      if ($show_months) {
        $current_month = (int)date('m', $eventDate->beginDate);
        if ($current_month !== $previous_event_month) { ?>
          <div class="sd-month-row">
            <h3><?= JText::sprintf( JHTML::_('date', $eventDate->beginDate, 'F Y')) ?></h3>
          </div>
          <?php
          $previous_event_month = $current_month;
        }
      }
      //-- Link to details or booking?
      $link = ($direct_booking)?$eventDate->bookingUrl:$eventDate->detailsUrl;
      $classSuffix = ($direct_booking)?" modal":"";
      
      //-- Matching search term filter?
//      $matchingFilters = SeminardeskHelperData::matchingFilters($eventDate, $filters);
      $matchingFilters = !(bool)$filters['term']; // If term is given, hide all events on load and enable by JS (do not filter by php, because this is beeing cached and renders old filter terms!)
      $anyEventMatching = $anyEventMatching || $matchingFilters;
      ?>
      <div class="sd-event<?= (!$matchingFilters)?' hidden':'' ?>" 
           itemscope="itemscope" itemtype="https://schema.org/Event"
           data-categories='<?= $eventDate->categoriesList ?>'>
        <a class="<?= $eventDate->cssClasses . $classSuffix ?>" href="<?= $link ?>" itemprop="url" <?= ($direct_booking)?'rel="{handler: \'iframe\'}"':''; ?>>
          <?php $sameYear = date('Y', $eventDate->beginDate) === date('Y', $eventDate->endDate); ?>
          <div class="sd-event-date <?= (!$sameYear)?' not-same-year':'' ?>">
            <time itemprop="startDate" 
                  datetime="<?= date('c', $eventDate->beginDate) ?>" 
                  content="<?= date('c', $eventDate->beginDate) ?>">
              <?= $eventDate->dateFormatted; ?>
            </time>
            <time itemprop="endDate" datetime="<?= date('c', $eventDate->endDate) ?>"></time>
          </div>
          <div class="sd-event-title" itemprop="name">
            <h4><?= $eventDate->title; ?></h4>
            <?= ($eventDate->showDateTitle)?('<p>' . $eventDate->eventDateTitle . '</p>'):'' ?>
          </div>
          <div class="sd-event-facilitators" itemprop="organizer">
            <?= $eventDate->facilitatorsList; ?>
          </div>
          <div class="sd-event-registration">
            <?= $eventDate->statusLabel; ?>
          </div>
          <div class="sd-event-external">
            <?= ($eventDate->isExternal)?JText::_("COM_SEMINARDESK_EVENTS_LABEL_EXTERNAL"):''; ?>
          </div>
          <div class="sd-event-location hidden" itemprop="location" itemscope itemtype="https://schema.org/Place">
            <span itemprop="name">ZEGG Bildungszentrum gGmbH</span>
            <div class="address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
              <span itemprop="streetAddress">Rosa-Luxemburg-Strasse 89</span><br>
              <span itemprop="postalCode">14806</span> <span itemprop="addressLocality">Bad Belzig</span>, <span itemprop="addressCountry">DE</span>
            </div>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  <p class="no-events-found<?= ($eventDates && $anyEventMatching)?' hidden':''; ?>"><?php echo JText::_("MOD_SEMINARDESK_NO_EVENTS_FOUND");?></p>
</div>
<?php if ($text_after) : ?>
<div class="sd-events-text-after">
  <?= $text_after; ?>
</div>
<?php endif; ?>
