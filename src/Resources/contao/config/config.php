<?php

/**
 * SttgsRedirect Modules Set
 *
 * Copyright (c) 2017 Die Schittigs
 *
 * @license LGPL-3.0+
 */

 $GLOBALS['TL_MODELS']['tl_redirect'] = 'DieSchittigs\\SttgsRedirect\\Models\\RedirectModel';


/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['sttgs-redirects'] = array
(
    'tables'      => array('tl_redirect'),
);



/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('DieSchittigs\\SttgsRedirect\\redirectClass', 'checkRedirect');
