<?php
/**
 * Copyright 2010-2015 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package  Boneyard
 */

/* Determine the base directories. */
if (!defined('BONEYARD_BASE')) {
    define('BONEYARD_BASE', realpath(__DIR__ . '/..'));
}

if (!defined('HORDE_BASE')) {
    /* If Horde does not live directly under the app directory, the HORDE_BASE
     * constant should be defined in config/horde.local.php. */
    if (file_exists(BONEYARD_BASE . '/config/horde.local.php')) {
        include BONEYARD_BASE . '/config/horde.local.php';
    } else {
        define('HORDE_BASE', realpath(BONEYARD_BASE . '/..'));
    }
}

/* Load the Horde Framework core (needed to autoload
 * Horde_Registry_Application::). */
require_once HORDE_BASE . '/lib/core.php';

/**
 * Boneyard application API.
 *
 * This class defines Horde's core API interface. Other core Horde libraries
 * can interact with Boneyard through this API.
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package  Boneyard
 */
class Boneyard_Application extends Horde_Registry_Application
{
    /**
     */
    public $version = 'H5 (0.1-git)';

    /**
     *  Advertise the dynamicView capability to the framework
     */
    public $features = array(
        'dynamicView' => true
    );


    /**
     */
    protected function _bootstrap()
    {
        $GLOBALS['injector']->bindFactory('Boneyard_Driver', 'Boneyard_Factory_Driver', 'create');
    }

    /**
     * Adds items to the sidebar menu.
     *
     * Simple sidebar menu entries go here. More complex entries are added in
     * the sidebar() method.
     *
     * @param $menu Horde_Menu  The sidebar menu.
     */
    public function menu($menu)
    {
        /* If index.php == lists.php, jump some extra loops to highlight the
         * menu entry. */
        $menu->add(
            Horde::url('list.php'),
            _("List"),
            'boneyard-list',
            null,
            null,
            null,
            basename($_SERVER['PHP_SELF']) == 'index.php' ? 'current' : null);

        /* A regular entry. */
        $menu->add(Horde::url('data.php'), _("Import/Export"), 'horde-data');
    }

    /**
     * Adds additional items to the sidebar.
     * This is only for traditional/static view.
     * For dynamic view, see Boneyard_View_Sidebar.php and templates/dynamic/sidebar.html.php
     * @param Horde_View_Sidebar $sidebar  The sidebar object.
     */
    public function sidebar($sidebar)
    {
        $sidebar->addNewButton(
            _("_Add Item"),
            Horde::url('new.php')
        );

        /* Checkbox lists are for resources that can be incrementally added to
         * the current content. */
        $sidebar->containers['foo'] = array(
            'header' => array(
                'id' => 'boneyard-toggle-foo',
                'label' => _("Foo"),
                'collapsed' => false,
                'add' => array(
                    'url' => Horde::url('foo.php'),
                    'label' => _("Create a new Foo"),
                ),
            ),
        );
        $sidebar->addRow(
            array(
                'selected' => true,
                'url' => Horde::url('foo.php')->add('foo', 1),
                'label' => _("One"),
                'color' => '#113355',
                'edit' => Horde::url('edit.php')->add('foo', 1),
                'type' => 'checkbox',
            ),
            'foo'
        );
        $sidebar->addRow(
            array(
                'selected' => false,
                'url' => Horde::url('foo.php')->add('foo', 2),
                'label' => _("Two"),
                'color' => '#557799',
                'type' => 'checkbox',
            ),
            'foo'
        );

        /* Radiobox lists are for resources that can be displayed mutually
         * exclusive in the current content. */
        $sidebar->containers['bar'] = array(
            'header' => array(
                'id' => 'boneyard-toggle-bar',
                'label' => _("Bar"),
                'collapsed' => true,
            ),
        );
        $sidebar->addRow(
            array(
                'selected' => true,
                'url' => Horde::url('bar.php')->add('bar', 1),
                'label' => _("One"),
                'color' => '#553311',
                'edit' => Horde::url('edit.php')->add('bar', 1),
                'type' => 'radiobox',
            ),
            'bar'
        );
        $sidebar->addRow(
            array(
                'selected' => false,
                'url' => Horde::url('bar.php')->add('bar', 2),
                'label' => _("Two"),
                'color' => '#997755',
                'type' => 'radiobox',
            ),
            'bar'
        );
    }
}
