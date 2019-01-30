<?php

$GLOBALS['TL_DCA']['tl_aliasindex'] = [
    'config' => [
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'onload_callback'             => ['\DieSchittigs\SttgsRedirect\redirectClass', 'checkStatus'],
        'closed'                      => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'alias' => 'index',
                'status' => 'index',
                'url' => 'index'
            ]
        ]
    ],

    'list' => [
        'sorting' => [
            'mode' => 2,
            'flag' => 11,
            'fields' => ['ptable','status'],
            'panelLayout' => 'filter;sort,search,limit'
        ],
        'label' => [
            'fields' => ['alias', 'ptable', 'currentId', 'url', 'status'],
            'label_callback'   => ['tl_aliasindex', 'listLayout']
        ],
        'global_operations' => [
            'all' => [
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ],
            'check' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_aliasindex']['check'],
				'href'                => 'key=checkStatus',
                'class'               => 'header_check_status',
				'button_callback'     => ['tl_aliasindex', 'checkStatus'],
            ]
        ],
        'operations' => [
            'edit' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_aliasindex']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.svg',
            ],
            'delete' => [
                'label'               => &$GLOBALS['TL_LANG']['tl_aliasindex']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.svg',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ]
        ]
    ],
    'palettes' => [
        'default' => '{aliasindex_legend},alias,url,ptable,currentId,status',
    ],
    'fields' => [
        'id' => [
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sort'                 => true,
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'alias' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_aliasindex']['alias'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50 clr', 'disabled' => true),
			'sql'                     => "varchar(128) NOT NULL default ''"
        ],
        'url' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_aliasindex']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50 clr', 'disabled' => true),
			'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'currentId' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_aliasindex']['currentId'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50 clr', 'disabled' => true),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ],
        'ptable' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_aliasindex']['ptable'],
			'exclude'                 => true,
            'search'                  => true,
            'sort'                    => true,
            'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50 clr', 'disabled' => true),
			'sql'                     => "varchar(128) NOT NULL default ''"
        ],
        'status' => [
            'label'                   => &$GLOBALS['TL_LANG']['tl_aliasindex']['status'],
			'exclude'                 => true,
            'search'                  => true,
            'sort'                    => true,
            'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50 clr'),
			'sql'                     => "int(3) unsigned NOT NULL default '0'"
        ],
    ],
];


class tl_aliasindex extends Backend
{
    public function checkStatus($href, $label, $title, $class, $attributes)
    {
        return '<a href="'.$this->addToUrl($href).'" class="'.$class.'" title="'.StringUtil::specialchars($title).'"'.$attributes.'>'.$label.'</a> ';
    }

    public function listLayout($row,$label)
    {
        switch(substr($row['status'],0,1)) {
            case '1': $color = '#6ba7a8'; break;
            case '2': $color = '#619166'; break;
            case '3': $color = '#616f91'; break;
            case '4': $color = '#bf4a4f'; break;
            case '5': $color = '#d68b21'; break;
        }

        return sprintf('%s <span style="color: #aaa">Table : %s | ID : %s</span> <span style="color: '.$color.'"</span>%s (%s)',
            $row['url'], $row['ptable'], $row['currentId'], $GLOBALS['TL_LANG']['MOD']['err'][$row['status']], $row['status']
        );
    }
}