<?php
/**
*   Block Rss
*   See flux on block
*
*   @version 1.8
*   @link http://www.nuked-klan.org Clan Management System 4 Gamers NK CMS
*   @license http://opensource.org/licenses/gpl-license.php GNU Public License
*   @copyright 2001-2013 Nuked Klan 
*/
defined('INDEX_CHECK') or die ('<div style="text-align: center;">'.CANTOPENPAGE.'</div>');

if (defined('TESTLANGUE')) { 

    function affichBlockRss($blok){
        list($rssHost, $titreactu, $puce, $nbr) = explode('|', $blok['content']);
    	
        $blok['content'] = '<nav>';
        if ($handle = @fopen($rssHost, "r")) {
            fclose($handle);
            $xml = simplexml_load_file($rssHost);    	
            $title = (string) $xml->channel->title;
            $title = htmlentities($title, ENT_QUOTES, 'UTF-8');    		
    		if($titreactu == 'Oui') {
                $blok['content'] .= '<h3 class="nkAlignCenter nkPaddingTop">'.$title.'</h3>';
            }
            $blok['content'] .= '   <ul class="nkMarginLeft">';
            $i = 0;
            foreach ($xml->channel->item as $actu) {    			
                $href = htmlentities((string) $actu->link, ENT_QUOTES, 'UTF-8');
                $titleActu = htmlentities((string) $actu->title, ENT_QUOTES, 'UTF-8');
                $pubDate = (string) $actu->pubDate;
                $description = (strlen($actu->description) > 255) ? substr($actu->description,0,255).'...' : $actu->description;
                $mylink = $titleActu.'&nbsp;( '.nkDate($pubDate, true, true).' )';
                $blok['content'] .= '   <li style="list-style-image: url(images/puces/'.$puce.')">'.$GLOBALS['nkFunctions']->nkTooltip($description, $href, $mylink, null, null, 'top', null, 500).'</li>';
                $i++;
                if ($i == $nbr) {
                    break;
                }
        	}    		
    		$blok['content'] .= '</ul>';    		
    	} else {
    		$blok['content'] .= $GLOBALS['nkTpl']->nkDisplayError(ERREUROPENRSS.'&nbsp;'.$rssHost , 'nkAlignCenter');
    	}    	
        $blok['content'] .= '</nav>';
        return $blok;    }
} else {
    echo $GLOBALS['nkTpl']->nkDisplayError(LANGNOTFOUND , 'nkAlignCenter');
}

    function list_puce($spuce){
    	
    	echo '<option value="none.gif">-- ' . _NONE . ' --</option>';
    	
    	$path = "images/puces/";
    	$handle = opendir($path);
    	while (false !== ($puce = readdir($handle))) {
    		
    		if ($puce != "." && $puce != ".." && $puce != "Thumbs.db" && $puce != "index.html" && $puce != "none.gif") {
    			
    			if (is_file($path . $puce)) {
    				
    				$selected = ($puce == $spuce) ? 'selected="selected"' : '';
    				echo '<option value="' . $puce . '" ' . $selected . '>' . $puce . '</option>';
                }
            }
        }
    }

    function edit_blockRss($bid){
        global $nuked, $language;

        $sql = mysql_query('SELECT active, position, titre, module, content, type, nivo, page FROM ' . BLOCK_TABLE . ' WHERE bid = \'' . $bid . '\' ');
        list($active, $position, $titre, $modul, $content, $type, $nivo, $pages) = mysql_fetch_array($sql);
        $titre = printSecuTags($titre);
    	
    	list($url, $titreactu, $puce, $nbr) = explode('|', $content);

        if ($active == 1) $checked1 = 'selected="selected"';
        else if ($active == 2) $checked2 = 'selected="selected"';
        else if ($active == 3) $checked3 = 'selected="selected"';
        else if ($active == 4) $checked4 = 'selected="selected"';
        else $checked0 = 'selected="selected"';
    	
    	echo '<script type="text/javascript">
    	      <!--
    		  function update_img(newimage){
    			  document.getElementById(\'img_puce\').src = \'images/puces/\' + newimage;
    		  }
    		  // -->
    		  </script>';

        echo '<div class="content-box">
    	          <div class="content-box-header"><h3>' . _BLOCKADMIN . '</h3>
    			      <a href="help/' . $language . '/block.html" rel="modal"><img style="border: 0;" src="help/help.gif" alt="" title="' . _HELP . '" /></a>
    			  </div>
    			  <div class="tab-content" id="tab2">
    			      <form method="post" action="index.php?file=Admin&amp;page=block&amp;op=modif_block">
    			      <table style="margin-left: auto;margin-right: auto;text-align: left;" cellspacing="0" cellpadding="2" border="0">
    				      <tr>
    					      <td><b>' . _TITLE . '</b></td>
    						  <td><b>' . _BLOCK . '</b></td>
    						  <td><b>' . _POSITION . '</b></td>
    						  <td><b>' . _LEVEL . '</b></td>
                          </tr>
    					  <tr>
    					      <td align="center"><input type="text" name="titre" size="40" value="' . $titre . '" /></td>
    						  <td align="center">
    						      <select name="active">
    							      <option value="1" ' . $checked1 . '>' . _LEFT . '</option>
    								  <option value="2" ' . $checked2 . '>' . _RIGHT . '</option>
    								  <option value="0" ' . $checked0 . '>' . _OFF . '</option>
                                  </select>
                              </td>
    						  <td align="center"><input type="text" name="position" size="2" value="' , $position , '" /></td>
    						  <td align="center">
    						      <select name="nivo">
    							      <option>' . $nivo . '</option>
    								  <option disabled="disabled"> -- </option>';
    								  
    								  for ($i = 0; $i <= 9; $i++) {
    									  echo '<option>' . $i . '</option>';
    								  }
    								  
    						echo '</select>
    						  </td>
                          </tr>
    					  <tr>
    					      <td colspan="4"><b>' . _URL . ' : </b><input type="text" name="content" size="50" value="' . $url . '" /></td>
    					  </tr>
    					  <tr>
    					      <td colspan="4"><b>' . _TITREACTU . ' : </b>
    						      <select name="titreactu">';
    							  
    							  if (!empty($titreactu)) {
    							      echo '<option>' . $titreactu . '</option>
    								        <option disabled="disabled"> --- </option>';
    							  }
    							  
    							echo '<option>Oui</option>
    								  <option>Non</option>
    							  </select>
    					      </td>
    					  </tr>
    					  <tr>
    					      <td colspan="4"><b>' . _NBRRSS . ' : </b>
    						      <select name="nbr">';
    							      
    							  if (!empty($nbr)) {
    							      echo '<option>' . $nbr . '</option>
    								        <option disabled="disabled"> --- </option>';
    							  }
    							      
    							echo '<option>5</option>
    								  <option>10</option>
    								  <option>15</option>
    								  <option>20</option>
    							  </select>
    					      </td>
    					  </tr>
    					  <tr>
    					      <td colspan="3"><b>' . _PUCE . ' : </b>
    						      <select name="puce" onchange="update_img(this.options[selectedIndex].value);">';
    							  
    							  list_puce($puce);
    							  if (empty($puce)) $puce = "none.gif";
    							  
    					echo '    </select>
    					          <img id="img_puce" src="images/puces/' . $puce . '" alt="" />
    					      </td>
    					  </tr>
    					  <tr><td colspan="4">&nbsp;</td></tr>
    					  <tr><td colspan="4" align="center"><b>' . _PAGESELECT . ' :</b></td></tr>
    					  <tr><td colspan="4">&nbsp;</td></tr>
    					  <tr>
    					      <td colspan="4" align="center">
    					          <select name="pages[]" size="8" multiple="multiple">';

                                  select_mod2($pages);

                            echo '</select>
    						  </td>
    					  </tr>
    					  <tr>
    					      <td colspan="4" align="center"><br />
    						      <input type="hidden" name="type" value="' . $type . '" />
    							  <input type="hidden" name="bid" value="' . $bid . '" />
    							  <input type="submit" name="send" value="' . _MODIFBLOCK . '" />
    						  </td>
    					  </tr>
                      </table>
    				  </form>
    				  <div style="text-align: center;"><br />[ <a href="index.php?file=Admin&amp;page=block"><b>' . _BACK . '</b></a> ]</div>
    				  <br />
                  </div>
              </div>';

    }

    function modif_advanced_rss($data){
    	$data['content'] = $data['content'] . '|' . $data['titreactu'] . '|' . $data['puce'] . '|' .$data['nbr'];
    	return $data;
    }
?>