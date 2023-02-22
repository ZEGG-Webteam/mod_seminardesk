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
//JHTML::_('behavior.modal');

//-- Load CSS / JS
$document  = JFactory::getDocument();
$document->addStyleSheet('/modules/mod_seminardesk/assets/css/styles.css');
$document->addScript('/modules/mod_seminardesk/assets/js/scripts.js');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

////-- Load filtered events
//$filter = [
//  'labels' => $params->get('labels'),
//  'limit' => $params->get('limit'),
//];
//$events = ModSeminardeskWrapper::loadEventDates($filter);
//
//$show_months = $params->get('show_months');
//$previous_event_month = '';
?>

<div class="sd-module sd-filters<?php echo ($moduleclass_sfx)?>">
  <p>To do: Display search form...</p>
</div>
