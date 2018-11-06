<?php

/**
 * SttgsRedirect Modules Set
 *
 * Copyright (c) 2017 Die Schittigs
 *
 * @license LGPL-3.0+
 */


if (TL_MODE=="BE") {
    $GLOBALS['TL_CSS'][] = 'bundles/contaoredirect/backend.css';
}

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['redirects'] = [
    'sttgs-redirects' => [
        'tables' => ['tl_redirect'],
    ],
    'aliasindex' => [
        'tables' => ['tl_aliasindex'],
        'checkStatus' => ['\DieSchittigs\SttgsRedirect\redirectClass' , 'checkStatus']
    ] 
];



/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('DieSchittigs\\SttgsRedirect\\redirectClass', 'checkRedirect');
