<?php
/**
 * This is a view of boneyard's sidebar.
 *
 * This is used to override conventional Boneyard_Application->sidebar()
 *
 * Copyright 2012-2015 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author   Michael J Rubinsky <mrubinsk@horde.org>
 * @category Horde
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package  Hermes
 */
class Boneyard_View_Sidebar extends Horde_View_Sidebar
{
    /**
     * Constructor.
     *
     * @param array $config  Configuration key-value pairs.
     */
    public function __construct($config = array())
    {
        global $prefs, $registry;

        parent::__construct($config);
        $blank = new Horde_Url();
        $this->addNewButton(
            _("_New Job"),
            $blank,
            array('id' => 'boneyardNewJob')
        );
        $sidebar = $GLOBALS['injector']->createInstance('Horde_View');
        $this->content = $sidebar->render('dynamic/sidebar');
    }
}
