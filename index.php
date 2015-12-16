<?php
/**
 * Boneyard index script.
 *
 * Copyright 2007-2015 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author Ralf Lang <lang@b1-systems.de>
 */

/* Determine View */
/* For the purpose of demonstration, we will just force dynamic view instead
   Remove the commented code if you only have dynamic view.
   Activate the commented code if you need to support traditional view */

//if ($GLOBALS['registry']->getView() == Horde_Registry::VIEW_DYNAMIC) {
    require_once __DIR__ . '/lib/Application.php';
    Horde_Registry::appInit('boneyard');
    $injector->getInstance('Boneyard_Ajax')->init();
    require BONEYARD_TEMPLATES . '/dynamic/index.inc';
    echo $injector->getInstance('Boneyard_View_Sidebar');
    $page_output->footer();
    exit;
//}

// traditional view
require __DIR__ . '/list.php';
