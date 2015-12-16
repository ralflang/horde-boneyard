<?php
/**
 * Boneyard wrapper for the base AJAX framework handler.
 *
 * Copyright 2015-2016 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author   Michael J Rubinsky <mrubinsk@horde.org>
 * @category Horde
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package  Boneyard
 */
class Boneyard_Ajax
{
    /**
     */
    public function init()
    {
        global $page_output;
        /* Load javascript tools here */
        $page_output->addScriptFile('redbox.js', 'horde');
        $page_output->addScriptFile('tooltips.js', 'horde');
        $page_output->addScriptFile('boneyard.js');

        $page_output->addInlineJsVars(array(
            'var Boneyard' => $this->_addBaseVars()
        ), array('top' => true));

        $page_output->header(array(
            'body_class' => 'horde-ajax',
            'growler_log' => true
        ));
    }

    /**
     * Add all kinds of variables and captions dependent on prefs, locale, state...
     * TODO: This could be stripped further and still serve demonstrational purposes
     */
    protected function _addBaseVars()
    {
        global $prefs, $injector, $conf, $registry;

        $app_urls = $js_vars = array();

        if (isset($conf['menu']['apps']) &&
            is_array($conf['menu']['apps'])) {
            foreach ($conf['menu']['apps'] as $app) {
                $app_urls[$app] = strval(Horde::url($registry->getInitialPage($app), true));
            }
        }
        $identity = $injector->getInstance('Horde_Core_Factory_Identity')->create();
        $format_date = str_replace(array('%x', '%X'), array(Horde_Nls::getLangInfo(D_FMT, Horde_Nls::getLangInfo(D_T_FMT))), $prefs->getValue('date_format_mini'));

        /* Variables used in core javascript files. */
        $js_vars['conf'] = array(
            'images' => array(
                'timerlog' => (string)Horde_Themes::img('log.png'),
            ),
            'user' => $registry->convertUsername($registry->getAuth(), false),
            'prefs_url' => strval($registry->getServiceLink('prefs', 'hermes')->setRaw(true)),
            'app_urls' => $app_urls,
            'name' => $identity->getName(),
            'login_view' => 'example1', // the default view when starting the ajax app
            'date_format' => str_replace(
                array('%e', '%d', '%a', '%A', '%m', '%h', '%b', '%B', '%y', '%Y'),
                array('d', 'dd', 'ddd', 'dddd', 'MM', 'MMM', 'MMM', 'MMMM', 'yy', 'yyyy'),
                $format_date),
        );

        /* Gettext strings used in core javascript files. */
        $js_vars['text'] = array(
            'noalerts' => _("No Notifications"),
            'alerts' => sprintf(_("%s notifications"), '#{count}'),
            'hidelog' => _("Hide Notifications"),
            'more' => _("more..."),
            'prefs' => _("Preferences"),
        );

        return $js_vars;
    }

}
