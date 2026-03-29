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
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_seminardesk.styles', 'modules/mod_seminardesk/assets/css/styles.css');
$wa->registerAndUseScript('mod_seminardesk.scripts', 'modules/mod_seminardesk/assets/js/scripts.js', [], ['defer' => true]);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx') ?? '', ENT_COMPAT, 'UTF-8');

// $facilitators is now passed from Dispatcher via getLayoutData()
?>

<div class="sd-module sd-facilitators<?php echo ($moduleclass_sfx) ? ' sd-events' . $moduleclass_sfx : ''; ?>">
  <p>To do: Display Facilitators...</p>
  <?php if (!empty($facilitators)) : ?>
    <?php foreach($facilitators as $facilitator) : ?>
      <div class="sd-facilitator">
         
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <p><?php echo Text::_("MOD_SEMINARDESK_NO_FACILITATORS_FOUND"); ?></p>
  <?php endif; ?>
</div>