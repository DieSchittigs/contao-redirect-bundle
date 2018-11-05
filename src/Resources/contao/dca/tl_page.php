<?php

$GLOBALS['TL_DCA']['tl_page']['config']['onsubmit_callback'][] = ['tl_page_redirect_bundle', 'notifyAliasChange'];

class tl_page_redirect_bundle extends \contao\Backend
{
    public function notifyAliasChange(DataContainer $dc)
    {
        $objAlias = AliasindexModel::findByAlias($dc->activeRecord->alias);

        if($objAlias !== NULL) return;

        $alias = New AliasindexModel();

        $alias->alias = $dc->activeRecord->alias;
        $alias->currentId = $dc->activeRecord->id;
        $alias->ptable = $dc->table;
        $alias->tstamp = time();
        $alias->url = \PageModel::findById($dc->activeRecord->id)->getAbsoluteUrl();
        $alias->save();

        return;
    }
}