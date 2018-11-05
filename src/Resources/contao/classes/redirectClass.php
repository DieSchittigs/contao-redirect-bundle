<?php

namespace DieSchittigs\SttgsRedirect;
use Contao\CoreBundle\Exception\PageNotFoundException;

class redirectClass extends \Frontend
{

    public function checkStatus()
    {
        $objAlias = \AliasindexModel::findAll();

        $strInfo = '';
        while($objAlias->next()) {
            
            $handle = curl_init($objAlias->url);
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            curl_close($handle);

            $objAlias->status = $httpCode;
            $objAlias->save();

            $strInfo.= '<tr class="click2edit toggle_select hover-row"><td class="tl_file_list">' . $objAlias->url . ' -> Statuscode ' . $httpCode . '</td></tr>';
        }
        return \Message::generate() . '
            <div id="tl_buttons">
                <a href="'.ampersand(str_replace('&key=checkStatus', '', \Environment::get('request'))).'" class="header_back" title="'.\StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
            </div>
            <div class="tl_listing_container status">
                <table class="tl_listing">
                <tbody>
                <tr>
                    <td colspan="2" class="tl_folder_tlist">Page Status</td>
                </tr>
                '.$strInfo.'
                </tbody>
                </table>
            </div>
        ';
    }

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
