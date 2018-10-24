<?php

namespace DieSchittigs\SttgsRedirect;
use Contao\CoreBundle\Exception\PageNotFoundException;

class redirectClass extends \Frontend
{
    public function checkRedirect()
    {
        ## Nur im Frontend ausführen
        if(TL_MODE != 'FE') return;

        ## Nicht auf der Index-Seite ausführen
        if(!\Environment::get('request')) return;
        
        $request = urldecode (\Environment::get('request'));

        $this->import('Database');
        if ($this->Database->tableExists('tl_redirect')) {

            $this->import('Database');

            ## Statische Weiterleitungen probieren
            $objIndex = $this->Database->query("SELECT * FROM tl_search WHERE url = '". \Environment::get('uri') . "'");

            if(!$objIndex->numRows){
                $objRedirect = \DieSchittigs\SttgsRedirect\Models\RedirectModel::findPublishedByUrl($request);

                if($objRedirect->type == "404"){
                    throw new PageNotFoundException('Page not found: ' . \Environment::get('uri'));
                }
                elseif($objRedirect->type == "landingpage") {

                    $strRedirect = \Controller::replaceInsertTags($objRedirect->redirect) . (($objRedirect->hash) ? '#'.$objRedirect->hash : '');
                    \Controller::redirect($strRedirect, $objRedirect->status);
                    die;
                } 
            }
            
            ## Regex Weiterleitungen probieren
            $objRedirect = \DieSchittigs\SttgsRedirect\Models\RedirectModel::findPublishedByType('rgxp', array('order' => 'sorting'));
            if($objRedirect === null) return;

            while($objRedirect->next()) {
                $search = html_entity_decode($objRedirect->rgxp_search);
                $replace = html_entity_decode($objRedirect->rgxp_replace);

                if(!$search OR !$replace) continue;
                
                if(preg_match($search, $request)) {
                    $strRedirect = preg_replace($search, $replace, $request);
                    \Controller::redirect($strRedirect, $objRedirect->status);
                    die;
                }
            }
            return;
        }
    }
}
