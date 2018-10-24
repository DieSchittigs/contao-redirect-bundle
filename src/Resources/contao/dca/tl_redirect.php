<?php

/**
 * redirect Modules Set
 *
 * Copyright (c) 2017 Die Schittigs
 *
 * @license LGPL-3.0+
 */
System::loadLanguageFile('tl_content');

/**
 * Table tl_redirect
 */
$GLOBALS['TL_DCA']['tl_redirect'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'switchToEdit'                => true,
        'enableVersioning'            => true,

        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'sorting' => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('type'),
            'flag'                    => 11,
            'panelLayout'             => 'filter;sort,search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('url','status','redirect'),
            'label_callback'          => array('tl_redirect', 'generateLabel')
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_redirect']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif',
            ),
            'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_redirect']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_redirect']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_redirect']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_redirect']['toggle'],
                'icon'                => 'visible.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('tl_redirect', 'toggleIcon')
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_redirect']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        '__selector__'                => array('type'),
        'default'                     => '{type_legend},type;',
        'landingpage'                 => '{type_legend},type;{title_legend},url,redirect,hash,status;{publish_legend},published,start,stop;',
        'rgxp'                        => '{type_legend},type;{title_legend},sorting,rgxp_search,rgxp_replace,status;{publish_legend},published,start,stop;',
        '404'                         => '{type_legend},type;{title_legend},url;{publish_legend},published,start,stop;'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting' => array
		(
            'label'                   => &$GLOBALS['TL_LANG']['MSC']['sorting'],
            'exclude'                 => true,
            'sorting'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
        'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['type'],
            'default'                 => 'landingpage',
            'exclude'                 => true,
            'sorting'                 => true,
			'filter'                  => true,
            'inputType'               => 'select',
            'options'                 => array('landingpage', 'rgxp', '404'),
            'reference'               => &$GLOBALS['TL_LANG']['tl_redirect']['types'],
			'eval'                    => array('chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''" 
        ),
        'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory' => true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'redirect' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['redirect'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'dcaPicker'=>true, 'addWizardClass'=>false),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'hash' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['hash'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array(),
			'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'rgxp_search' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['rgxp_search'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory' => true, 'tl_class' => 'clr'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'rgxp_replace' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['rgxp_replace'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory' => true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
        'status' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['status'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'default'                 => '301',
            'options'                 => array('301' => '301 - Moved Permanently', '302' => '302 - Found', '303' => '303 - See Other'),
            'sql'                     => "varchar(32) NOT NULL default ''"
        ),
        'published' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['published'],
            'exclude'                 => true,
            'filter'                  => true,
            'flag'                    => 2,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_redirect']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
    )
);


class tl_redirect extends \Contao\Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    public function generateLabel($row, $label)
    {
        if($row['type'] == 'landingpage') {
            $strPage = \Controller::replaceInsertTags($row['redirect']) .(($row['hash']) ? '#'.$row['hash'] : '');
            $strPage = ($strPage) ? $strPage : 'index.html';
            return sprintf('%s <span style="color: #aaa;">[%s] => </span>%s' , $row['url'], $row['status'], $strPage);
        }
        elseif($row['type'] == '404') {
            return sprintf('%s <span style="color: #aaa;"> => </span><strong>404</strong>' , $row['url']);
        }
        else {
            return sprintf('%s <span style="color: #aaa;">[%s] => </span>%s' , $row['rgxp_search'], $row['status'], $row['rgxp_replace']);
        }
    }

    /**
     * Disable/enable a travel diary
     *
     * @param integer       $intId
     * @param boolean       $blnVisible
     * @param DataContainer $dc
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc)
        {
            $dc->id = $intId; // see #8043
        }

        $objVersions = new Versions('tl_redirect', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_redirect.php']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_redirect']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, ($dc ?: $this));
                }
            }
        }
        // Update the database
        $this->Database->prepare("UPDATE tl_redirect SET tstamp=". time() .", published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")->execute($intId);

        $objVersions->create();
        $this->log('A new version of record "tl_redirect.id='.$intId.'" has been created'.$this->getParentEntries('tl_redirect', $intId), __METHOD__, TL_GENERAL);
    }

    /**
     * Return the "toggle visibility" button
     *
     * @param array  $row
     * @param string $href
     * @param string $label
     * @param string $title
     * @param string $icon
     * @param string $attributes
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"').'</a> ';
    }

}
