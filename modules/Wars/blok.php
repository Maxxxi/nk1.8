<?php
/**
*   Block of Downloads module
*   Display the last/top 10 files
*
*   @version 1.8
*   @link http://www.nuked-klan.org Clan Management System 4 Gamers NK CMS
*   @license http://opensource.org/licenses/gpl-license.php GNU Public License
*   @copyright 2001-2013 Nuked Klan 
*/
defined('INDEX_CHECK') or die ('<div class="nkAlignCenter">'.CANTOPENPAGE.'</div>');
global $language, $user, $visiteur, $blockSide;
$modName = basename(dirname(__FILE__));

// Veridication du chargement du fichier langue
$langTest = strtoupper($modName);
$langTest = constant('TESTLANGUEFILE'.$langTest);
if($langTest == true) { 

    if ($blockSide[$modName] == 3 || $blockSide[$modName] == 4) {
        echo '<table style="margin-left: auto;margin-right: auto;text-align: left;" width="90%">
                <tr><td style="width: 45%;vertical-align:top;"><a href="index.php?file=Wars"><b><big>' . _LATESTWAR . '</big></b></a>
                <br /><br /><table width="180" cellspacing="1" cellpadding="0">';

        $sql = mysql_query('SELECT id, paysAdversary, adversary, tscoreTeam, tscoreAdversary FROM ' . WARS_TABLE . ' WHERE status = 1 ORDER BY createdYear DESC, createdMonth DESC, createdDay DESC LIMIT 0, 10');
        $nbwar = mysql_num_rows($sql);

        while (list($war_id, $pays_adv, $adv_name, $score_team, $score_adv) = mysql_fetch_array($sql)){
            $adv_name = printSecuTags($adv_name);
            list ($pays, $ext) = explode ('.', $pays_adv);

            if ($score_team > $score_adv){
                $color = '#009900';
            }
            else if ($score_team < $score_adv){
                $color = '#990000';
            }
            else{
                $color = '#3333FF';
            }

            echo '<tr><td style="width: 60%;"><img src="images/flags/' . $pays_adv . '" alt="" title="' . $pays . '" />&nbsp;&nbsp;<a href="index.php?file=Wars&amp;op=detail&amp;war_id=' . $war_id . '"><b>' . $adv_name . '</b></a></td>
                    <td style="width: 40%;background: $color;color: #FFFFFF;text-align:center;"><b>' . $score_team . '/' . $score_adv . '</b></td></tr>';
        }

        if (mysql_num_rows($sql) == NULL) echo '<tr><td colspan="2" style="text-align:center;">' . _NOMATCH . '</td></tr>';

        echo '</table></td><td style="width: 10%;">&nbsp;</td><td style="width: 45%;vertical-align:top;"><a href="index.php?file=Calendar"><b><big>' . _NEXTWAR . '</big></b></a>
                <br /><br /><table width="180" cellspacing="1" cellpadding="0">';

        $sql2 = mysql_query('SELECT id, paysAdversary, adversary, createdDay, createdMonth, createdYear FROM ' . WARS_TABLE . ' WHERE status = 0 ORDER BY createdYear, createdMonth, createdDay LIMIT 0, 10');
        $nbwar2 = mysql_num_rows($sql2);

        $d = date('d');
        $m = date('m');
        $y = date('Y');

        while (list($war_id2, $pays_adv2, $adv_name2, $d2, $m2, $y2) = mysql_fetch_array($sql2)){
            $adv_name2 = printSecuTags($adv_name2);
            list ($pays2, $ext2) = explode ('.', $pays_adv2);

            if ($m2 < 10){
                $m2 = "0" . $m2;
            }

            if ($language == 'french'){
                $date = $d2 . '/' . $m2 . '/' . $y2;
            }
            else{
                $date = $m2 . '/' . $d2 . '/' . $y2;
            }

            echo '<tr><td style="width: 60%;"><img src="images/flags/' . $pays_adv2 . '" alt="" title="' . $pays2 . '" />&nbsp;&nbsp;<a href="index.php?file=Calendar&amp;m=' . $m2 . '&amp;y=' . $y2 . '"><b>' . $adv_name2 . '</b></a></td>
                    <td style="width: 40%;text-align:center;">' . $date . '</td></tr>';
        }

        if (mysql_num_rows($sql2) == NULL) echo '<tr><td colspan="2" style="text-align:center;">' . _NOMATCH . '</td></tr>';

        echo '</table></td></tr><tr><td style="width: 45%;text-align:right;"><a href="index.php?file=Wars"><small>+ ' . _GOWARS . '</small></a></td>
                <td style="width: 10%;"></td><td style="width: 45%;text-align:right;"><a href="index.php?file=Calendar"><small>+ ' . _GOCALENDAR . '</small></a></td></tr></table><br />';

    }
    else{

        echo '<table width="100%" border="0" cellspacing="1" cellpadding="0">
                <tr><td colspan="2"><span style="text-decoration: underline"><b>'._LATESTWAR.' :</b></span></td></tr><tr><td colspan="2">&nbsp;</td></tr>';

        $sql = mysql_query('SELECT id, paysAdversary, adversary, tscoreTeam, tscoreAdversary FROM ' . WARS_TABLE . ' WHERE status = 1 ORDER BY createdYear DESC, createdMonth DESC, createdDay DESC LIMIT 0, 5');
        while (list($war_id, $pays_adv, $adv_name, $score_team, $score_adv) = mysql_fetch_array($sql)){
            $adv_name = printSecuTags($adv_name);
            list ($pays, $ext) = explode ('.', $pays_adv);

            if ($score_team > $score_adv){
                $color = '#009900';
            }
            else if ($score_team < $score_adv){
                $color = '#990000';
            }
            else{
                $color = '#3333FF';
            }

            echo '<tr><td style="width: 60%"><img src="images/flags/' . $pays_adv . '" alt="" title="' . $pays. '" />&nbsp;&nbsp;<a href="index.php?file=Wars&amp;op=detail&amp;war_id=' . $war_id . '"><b>' . $adv_name . '</b></a></td>
                    <td style="width: 100px;background: ' . $color . ';text-align:center;"><b>' . $score_team . '/' . $score_adv . '</b></td></tr>';
        }

        if (mysql_num_rows($sql) == NULL) echo '<tr><td colspan="2" style="text-align:center;"><em>' . _NOMATCH . '</em></td></tr>';

        $sql2 = mysql_query('SELECT id, paysAdversary, adversary, createdDay, createdMonth, createdYear FROM ' . WARS_TABLE . ' WHERE status = 0 ORDER BY createdYear, createdMonth, createdDay LIMIT 0, 5');
        $do_affich_bl = mysql_num_rows($sql2);

        if ($do_affich_bl > 0){
            $d = date('d');
            $m = date('m');
            $y = date('Y');

            echo '<tr><td colspan="2">&nbsp;</td></tr><tr><td colspan="2"><span style="text-decoration: underline"><b>'._NEXTWAR.' :</b></span></td></tr><tr><td colspan="2">&nbsp;</td></tr>';

            while (list($war_id2, $pays_adv2, $adv_name2, $d2, $m2, $y2) = mysql_fetch_array($sql2)){
                $adv_name2 = printSecuTags($adv_name2);
                list ($pays2, $ext2) = explode ('.', $pays_adv2);

                if ($m2 < 10){
                    $m2 = '0' .$m2;
                }

                if ($language == 'french'){
                    $date = $d2 . '/' .$m2;
                }
                else{
                    $date = $m2. '/' .$d2;
                }

                echo '<tr><td style="width: 60%"><img src="images/flags/' . $pays_adv2 . '" alt="" title="' . $pays2 . '" />
                        &nbsp;&nbsp;<a href="index.php?file=Calendar&amp;m=' . $m2 . '&amp;y=' . $y2 . '"><b>' . $adv_name2 . '</b></a></td>
                        <td style="width: 40%text-align:center;">' . $date . '</td></tr>';
            }
        }
        
        echo '</table>';
    }
} else {
    echo $GLOBALS['nkTpl']->nkDisplayError(LANGNOTFOUND , 'nkAlignCenter');
}
?>