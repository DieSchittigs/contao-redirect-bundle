<?php

//$GLOBALS['TL_DCA']['tl_page']['config']['onsubmit_callback'][] = ['tl_page_redirect', 'notifyAliasChange'];

$GLOBALS['TL_DCA']['tl_page']['fields']['alias']['save_callback'][] = ['tl_page_redirect', 'notifyAliasChange'];


class tl_page_redirect extends \contao\Backend
{
    // Check if the alias is not in the alias index or if it has changed and insert it
    public function notifyAliasChange($varValue, DataContainer $dc)
    {
        
        // Check if the URL is already in the index
        $strUrl = \PageModel::findById($dc->id)->getAbsoluteUrl();
        $objAlias = \AliasindexModel::findByUrl($strUrl,['order' => 'tstamp DESC']);

        if($objAlias !== NULL) return $varValue;

        // get the REAL old element by ID
        $objAlias = \AliasindexModel::findByCurrentId($dc->id,['order' => 'tstamp DESC']);
        
        // Insert new Record
        $alias = New \AliasindexModel();

        $alias->alias = $varValue;
        $alias->currentId = $dc->id;
        $alias->ptable = $dc->table;
        $alias->tstamp = time();
        $alias->url = $strUrl;
        $alias->save();

        // Check for existing readers for this page
        // only when there is an entry
        
        if ($objAlias === NULL OR $objAlias->alias == $varValue) return $varValue;

        $arrReader = $this->checkReader($dc->id);

        

        if(is_array($arrReader)){

            foreach($arrReader as $reader){
                \Message::addError(
                    sprintf($GLOBALS['TL_LANG']['MOD']['readerMessageInfo'],$reader['title'],$reader['table'],$reader['id'])
                );

                // Insert Redirect
                $redirect['newUrl'] = \PageModel::findById($dc->id)->getFrontendUrl();

                $redirect['regexSearch'] = '/^'.
                    str_replace(
                        [$varValue.'.html', '/'], 
                        [$objAlias->alias . '/(.+?)\.html', '\/'],
                        $redirect['newUrl']
                    ).'$/';
                $redirect['regexReplace'] = str_replace('.html', '/$1.html', $redirect['newUrl']);

                $objRedirect = New \RedirectModel();
                $objRedirect->type = 'rgxp';
                $objRedirect->rgxp_search = $redirect['regexSearch'];
                $objRedirect->rgxp_replace = $redirect['regexReplace'];
                $objRedirect->status = '301';
                $objRedirect->save();
            }
        }
        return $varValue;
    }

    public function checkReader($intId)
    {
    
        // News Archives
        $objNewsArchives = \NewsArchiveModel::findByJumpTo($intId);
        $arrReader = $this->returnReaderInfo($objNewsArchives, 'tl_news');
        
        // Calendar
        $objCalendar = \CalendarModel::findByJumpTo($intId);
        $arrReader = array_merge($arrReader, $this->returnReaderInfo($objCalendar, 'tl_calendar_events'));

        // FAQ Category
        $objFaqCategory = \FaqCategoryModel::findByJumpTo($intId);
        $arrReader = array_merge($arrReader, $this->returnReaderInfo($objFaqCategory, 'tl_faq'));

        // Newsletter Channel
        $objNewsletterChannel = \NewsletterChannelModel::findByJumpTo($intId);
        $arrReader = array_merge($arrReader, $this->returnReaderInfo($objNewsletterChannel, 'tl_newsletter'));

        if(empty($arrReader)) return false;

        return $arrReader;
    }

    // Read archive objects and return info as array
    public function returnReaderInfo($objArchive, $strTable = '')
    {
        $arrInfo = [];
        if($objArchive !== NULL) {
            while($objArchive->next()){
                $arrInfo[] = [
                    'id' => $objArchive->id,
                    'title' => $objArchive->title, 
                    'table' => $strTable
                ];
            }
        }
        return $arrInfo;
    }
}