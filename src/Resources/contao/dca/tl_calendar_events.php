<?php

$GLOBALS['TL_DCA']['tl_calendar_events']['config']['onsubmit_callback'][] = ['tl_calendar_events_redirect', 'notifyAliasChange'];

class tl_calendar_events_redirect extends \contao\Backend
{
    // Check if the alias is not in the alias index or if it has changed and insert it
    public function notifyAliasChange(DataContainer $dc)
    {

        $strUrl = \PageModel::findById(\CalendarModel::findById($dc->activeRecord->pid)->jumpTo)->getAbsoluteUrl('/'.$dc->activeRecord->alias);

        $objAlias = \AliasindexModel::findByUrl($strUrl,['order' => 'tstamp DESC']);

        if($objAlias !== NULL) return;

        // Insert new Record
        $alias = New \AliasindexModel();

        $alias->alias = $dc->activeRecord->alias;
        $alias->currentId = $dc->activeRecord->id;
        $alias->ptable = $dc->table;
        $alias->tstamp = time();
        $alias->url = $strUrl;
        $alias->save();

        return;
    }
}