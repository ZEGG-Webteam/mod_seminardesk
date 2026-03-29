<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_seminardesk
 *
 * @author      Benno Flory
 * @copyright   Copyright (C) 2022 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Module\Seminardesk\Site\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

/**
 * Dispatcher class for mod_seminardesk
 *
 * @since  2.0.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data.
     *
     * @return  array
     */
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();
        
        $helper = $this->getHelperFactory()->getHelper('SeminardeskHelper');
        
        // Get the configured view (events, facilitators, search)
        $view = $data['params']->get('view', 'events');
        
        // Load data based on view type
        if ($view === 'events') {
            $data['eventDates'] = $helper->getEventDates($data['params'], $data['app']);
        } elseif ($view === 'facilitators') {
            $data['facilitators'] = $helper->getFacilitators($data['params']);
        }
        
        $data['helper'] = $helper;
        
        return $data;
    }
}
