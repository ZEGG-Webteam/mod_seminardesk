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

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_seminardesk.styles', 'modules/mod_seminardesk/assets/css/styles.css');
$wa->registerAndUseScript('mod_seminardesk.scripts', 'modules/mod_seminardesk/assets/js/scripts.js', [], ['defer' => true]);

//-- Load configs
$show_months = $params->get('show_months');
$display_teaser_image = $params->get('display_teaser_image');
// event_link_action: 0 = detail page, 1 = URL to special event website, 2 = direct booking
$event_link_action = $params->get('event_link_action');

if ($event_link_action == '1') {
  $wa->registerAndUseStyle('com_seminardesk.styles', 'media/com_seminardesk/css/styles.css');
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

// $eventDates is now passed from Dispatcher via getLayoutData()
$anyEventMatching = false;
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
            <h3><?= Text::sprintf(HTMLHelper::_('date', $eventDate->beginDate, 'F Y')) ?></h3>
          </div>
          <?php
          $previous_event_month = $current_month;
        }
      }
      
      //-- Teaser image?
      $teaserImg = ($display_teaser_image && $eventDate->teaserPictureUrl)?$eventDate->teaserPictureUrl:'';
      if ($teaserImg) {
        $eventDate->cssClasses .= ' show-teaser-image';
      }
      
      //-- Link to event website, details or booking?
      if ($event_link_action == '1' && $eventDate->website) {
        $link = $eventDate->website;
      } else if ($event_link_action == '2') {
        $link = $eventDate->bookingUrl;
      } else {
        $link = $eventDate->detailsUrl;
      }
      $classSuffix = ($event_link_action == '2')?" modal":"";
      
      //-- Matching search term filter?
//      $matchingFilters = SeminardeskHelperData::matchingFilters($eventDate, $filters);
      $matchingFilters = !(bool)$filters['term']; // If term is given, hide all events on load and enable by JS (do not filter by php, because this is beeing cached and renders old filter terms!)
      $anyEventMatching = $anyEventMatching || $matchingFilters;
      ?>
      <div class="sd-event<?= (!$matchingFilters)?' hidden':'' ?>" 
           itemscope="itemscope" itemtype="https://schema.org/Event"
           data-categories='<?= $eventDate->categoriesList ?>'>
        <a 
          class="<?= $eventDate->cssClasses . $classSuffix ?> no-icon" 
          href="<?= $link ?>" 
          itemprop="url"
          target="<?= ($eventDate->isFeatured || ($link_to_event_website && $this->eventDate->website))?'_blank':'_parent'; ?>"
          <?= ($event_link_action == '2')?' rel="{handler: \'iframe\'}"':''; ?>
        >
          <?php if ($teaserImg) : ?>
            <img src="<?= $eventDate->teaserPictureUrl ?>" title="<?= strip_tags($eventDate->dateFormatted) . ': ' .  $eventDate->title; ?>" alt="<?= $eventDate->title; ?>" width="700">
          <?php else : ?>
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
              <?php if ($eventDate->endDate > time()) : ?>
                <?= $eventDate->statusLabel; ?> <!-- show status for future events only -->
              <?php endif; ?>
            </div>
            <div class="sd-event-external">
              <?= ($eventDate->isExternal) ? Text::_("COM_SEMINARDESK_EVENTS_LABEL_EXTERNAL") : ''; ?>
            </div>
            <div class="sd-event-location hidden" itemprop="location" itemscope itemtype="https://schema.org/Place">
              <span itemprop="name">ZEGG Bildungszentrum gGmbH</span>
              <div class="address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                <span itemprop="streetAddress">Rosa-Luxemburg-Strasse 89</span><br>
                <span itemprop="postalCode">14806</span> <span itemprop="addressLocality">Bad Belzig</span>, <span itemprop="addressCountry">DE</span>
              </div>
            </div>
          <?php endif; ?>
        </a>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  <p class="no-events-found<?= ($eventDates && $anyEventMatching) ? ' hidden' : ''; ?>"><?php echo Text::_("MOD_SEMINARDESK_NO_EVENTS_FOUND"); ?></p>
</div>
