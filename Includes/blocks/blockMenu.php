<?php
/**
*   Block Menu
*   Display navigation into website
*
*   @version 1.8
*   @link http://www.nuked-klan.org Clan Management System 4 Gamers NK CMS
*   @license http://opensource.org/licenses/gpl-license.php GNU Public License
*   @copyright 2001-2013 Nuked Klan 
*/
if (!defined('INDEX_CHECK')){ exit('<div style="text-align: center;">'.CANTOPENPAGE.'</div>'); }

function affichBlockMenu($blok){
    $blok['content'] = blockLink($blok['content']);
    return $blok;
}

function blockLink($content){
    global $user, $visiteur;

    $content = html_entity_decode($content, ENT_NOQUOTES, 'UTF-8');
    $link = explode('NEWLINE', $content);
    $screen = '<ul style="list-style: none; padding: 0">';
    $size = count($link);
    
    for($i=0; $i<$size; $i++){
        list($url, $title, $comment, $nivo, $blank) = explode('|', $link[$i]);
        $url = preg_replace("/\[(.*?)\]/si", "index.php?file=\\1", $url);
        $nivuser = $user;
        $title = preg_replace("`&amp;lt;`i", "<", $title);
        $title = preg_replace("`&amp;gt;`i", ">", $title);
        $comment = htmlentities($comment);
        $url = htmlentities($url);

        if (!$nivuser)$nivuser = 0;
        
        if ($nivuser >= $nivo){
            if ($url <> '' && $title <> '' && $blank == 0)
                $screen .= '<li><a href="' . $url . '" title="' . $comment . '" style="padding-left: 10px" class="menu">' . $title . '</a></li>';

            if ($url <> '' && $title <> '' && $blank == 1)
                $screen .= '<li><a href="' . $url . '" title="' . $comment . '" class="menu" style="padding-left: 10px" onclick="window.open(this.href); return false;">' . $title . '</a></li>';

            if ($url == '' && $title <> '' && $comment == '')
                $screen .= '<li style="padding-left: 20px" class="titlemenu">' . $title . '</li>';
        }
    }
    $screen .= '</ul>';
    return $screen;
}

function edit_block_menu($bid){
    global $nuked, $language;

    $sql = mysql_query('SELECT active, position, titre, module, content, type, nivo, page FROM ' . BLOCK_TABLE . ' WHERE bid = \'' . $bid . '\' ');
    list($active, $position, $titre, $modul, $content, $type, $nivo, $pages) = mysql_fetch_array($sql);

    $content = htmlentities($content);

    if ($active == 1) $checked1 = 'selected="selected"';
    else if ($active == 2) $checked2 = 'selected="selected"';
    else $checked0 = 'selected="selected"';

    echo '<div class="content-box">',"\n" //<!-- Start Content Box -->
            , '<div class="content-box-header"><h3>' , _BLOCKADMIN , '</h3>',"\n"
            , '<a href="help/' , $language , '/block.html" rel="modal">',"\n"
            , '<img style="border: 0;" src="help/help.gif" alt="" title="' , _HELP , '" /></a>',"\n"
            , '</div>',"\n"
            , '<div class="tab-content" id="tab2"><form method="post" action="index.php?file=Admin&amp;page=block&amp;op=modif_block">',"\n"
            , '<table style="margin-left: auto;margin-right: auto;text-align: left;" cellspacing="0" cellpadding="2" border="0">',"\n"
            , '<tr><td><b>' , _TITLE , '</b></td><td><b>' , _BLOCK , '</b></td><td><b>' , _POSITION , '</b></td><td><b>' , _LEVEL , '</b></td></tr>',"\n"
            , '<tr><td align="center"><input type="text" name="titre" size="40" value="' , $titre , '" /></td>',"\n"
            , '<td align="center"><select name="active">',"\n"
            , '<option value="1" ' , $checked1 , '>' , _LEFT , '</option>',"\n"
            , '<option value="2" ' , $checked2 , '>' , _RIGHT , '</option>',"\n"
            , '<option value="0" ' , $checked0 , '>' , _OFF , '</option></select></td>',"\n"
            , '<td align="center"><input type="text" name="position" size="2" value="' , $position , '" /></td>',"\n"
            , '<td align="center"><select name="nivo"><option>' , $nivo , '</option>',"\n"
            , '<option>0</option>',"\n"
            , '<option>1</option>',"\n"
            , '<option>2</option>',"\n"
            , '<option>3</option>',"\n"
            , '<option>4</option>',"\n"
            , '<option>5</option>',"\n"
            , '<option>6</option>',"\n"
            , '<option>7</option>',"\n"
            , '<option>8</option>',"\n"
            , '<option>9</option></select></td></tr>',"\n"
            , '<tr><td colspan="4" align="center"><br /><input type="button" value="' , _EDITMENU , '" onclick="javascript:window.location=\'index.php?file=Admin&amp;page=menu&amp;op=edit_menu&amp;bid=' , $bid , '\'" /></td></tr>',"\n"
            , '<tr><td colspan="4">&nbsp;</td></tr><tr><td colspan="4" align="center"><b>' , _PAGESELECT , ' :</b></td></tr><tr><td colspan="4">&nbsp;</td></tr>',"\n"
            , '<tr><td colspan="4" align="center"><select name="pages[]" size="8" multiple="multiple">',"\n";

    select_mod2($pages);

    echo '</select></td></tr><tr><td colspan="4" align="center"><br />',"\n"
        , '<input type="hidden" name="type" value="' , $type , '" />',"\n"
        , '<input type="hidden" name="bid" value="' , $bid , '" />',"\n"
        , '<input type="hidden" name="content" value="' , $content , '" />',"\n"
        , '<input type="submit" name="send" value="' , _MODIFBLOCK , '" />',"\n"
        , '</td></tr></table>',"\n"
        , '<div style="text-align: center;"><br />[ <a href="index.php?file=Admin&amp;page=block"><b>' , _BACK , '</b></a> ]</div></form><br /></div></div>',"\n";

}
?>