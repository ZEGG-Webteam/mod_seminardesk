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

//-- Get key for translations from SeminarDesk (e.g. 'DE', 'EN')
$langKey = ModSeminardeskWrapper::getCurrentLanguageKey();
$previousEventMonth = '';

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

$eventDates = ModSeminardeskWrapper::loadEventDates(['labels' => $params->get('cat_id')]);
?>

<div class="sd-events<?php echo ($moduleclass_sfx)?' sd-events'.$moduleclass_sfx:''; ?>">
  <?php if ($eventDates) : ?>
    <?php foreach($eventDates as $eventDate) : ?>
      <?php
      //-- Month headings?
      if ($params->get('show_months')) {
        $currentMonth = (int)date('m', $eventDate->beginDate);
        if ($currentMonth !== $previousEventMonth) { ?>
          <div class="sd-month-row">
            <h3><?= JText::sprintf( JHTML::_('date', $eventDate->beginDate, 'F Y')) ?></h3>
          </div>
          <?php
          $previousEventMonth = $currentMonth;
        }
      }
      ?>
  
      <div class="sd-event">
        
        <a class="registration-available" href="<?= $eventDate->booking_url ?>" target="seminardesk">
          
          <?php $sameYear = date('Y', $eventDate->beginDate) === date('Y', $eventDate->endDate); ?>
          <div class="sd-event-date <?= (!$sameYear)?' not-same-year':'' ?>">
            <?= $eventDate->dateFormatted; ?>
          </div>
          <div class="sd-event-title">
            <?= $eventDate->title; ?>
          </div>
          <div class="sd-event-facilitators">
            <?= implode(', ', $eventDate->facilitators); ?>
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