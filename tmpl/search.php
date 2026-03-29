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

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_seminardesk.styles', 'modules/mod_seminardesk/assets/css/styles.css');
$wa->registerAndUseScript('mod_seminardesk.scripts', 'modules/mod_seminardesk/assets/js/scripts.js', [], ['defer' => true]);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx') ?? '', ENT_COMPAT, 'UTF-8');
?>

<div class="sd-module sd-filters<?php echo $moduleclass_sfx; ?>">
  <p>To do: Display search form...</p>
</div>
