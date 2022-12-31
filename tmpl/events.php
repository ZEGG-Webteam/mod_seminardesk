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

//-- Load CSS / JS
$document  = JFactory::getDocument();
$document->addStyleSheet('/modules/mod_seminardesk/assets/css/styles.css');
$document->addScript('/modules/mod_seminardesk/assets/js/scripts.js');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

//-- Load filtered events
$filter = [
  'labels' => $params->get('labels'),
  'limit' => $params->get('limit'),
];
$eventDates = ModSeminardeskWrapper::loadEventDates($filter, $params->get('events_page'));

$show_months = $params->get('show_months');
$previous_event_month = '';
?>

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
      ?>
  
      <div class="sd-event" itemscope="itemscope" itemtype="https://schema.org/Event">
        
        <a class="registration-available" href="<?= $eventDate->details_url ?>" itemprop="url">
          
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
            <?= $eventDate->title; ?>
          </div>
          <div class="sd-event-facilitators" itemprop="organizer">
            <?= $eventDate->facilitatorsList; ?>
          </div>
          <div class="sd-event-registration">
            <?= $eventDate->statusLabel; ?>
          </div>
          
        </a>
          
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <p><?php echo JText::_("MOD_SEMINARDESK_NO_EVENTS_FOUND");?></p>
  <?php endif; ?>
</div>
