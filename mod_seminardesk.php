<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_seminardesk
 *
 * @author      Benno Flory
 * @copyright   Copyright (C) 2022 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the seminardesk events functions only once
JLoader::register('ModSeminardeskWrapper', __DIR__ . '/helpers/wrapper.php');

require JModuleHelper::getLayoutPath('mod_seminardesk', $params->get('layout', 'default'));
