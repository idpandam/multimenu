<?php
/********************************************************/
/* multi menu                                         */
/* By: perpaola                                          */
/********************************************************/

$module_name = "multi_menu";


/*********************************************************/
/* Index                                                 */
/*********************************************************/
//$pgtitle = "Multi menu";
//$catpath = 'modules/multi_menu/immagini/site'.$siteid.''; //directory old img per lato pubblico
$catpath = 'blocks/block-Multi_Menu/theme/img/site'.$siteid.''; //directory img per lato pubblico

function wlx_get_lang($module) {
    global $currentlang, $language, $module_name;
    if ($module == 'admin') {
		include_once("admin/language/lang-$currentlang.php");
		if (file_exists("$module/modules/$module_name/language/lang-$currentlang.php")) {
			include_once("$module/modules/$module_name/language/lang-$currentlang.php");
		} 
    }
}
wlx_get_lang('admin');

function multiadminmenu($op){
    global  $verifica, $prefix, $db, $currentlang, $multilingual, $admin_file, $siteid, $multiext;
	$linksite=(!$verifica) ? "$siteid" : "1";
	$sql = $db->sql_query("SELECT * FROM ".$prefix."_multimenu_cat");
		echo "<div class=\"adminmod-header clearfix\">";
	echo "<div class=\"ad-iconmod\"><img src='images/admin/apmenu.png' title='"._GESTAPMENU."' alt='"._GESTAPMENU."' /></div>";
	echo "<div class=\"ad-titlemod\">"._GESTAPMENU."</div>";
	echo "<div class=\"ad-utilmod\">"
		."<ul>";
	if ($verifica) {
	if(!$sql) {
    echo "<li><a href='".$admin_file.".php?op=multimenu_install&amp;siteid=$linksite&amp;ok=0' title='"._INSTALLTAB."'><img src='images/icons/installa.png' title='"._INSTALLTAB."' alt='"._INSTALLTAB."' /><br />"._INSTALLTAB."</a></li>\n";
	} else {
	echo "<li><a href='".$admin_file.".php?op=multimenu_destall&amp;siteid=$linksite&amp;ok=0' title='"._DESTALLTAB."'><img src='images/icons/rimuovi.png' title='"._DESTALLTAB."' alt='"._DESTALLTAB."' /><br />"._DESTALLTAB."</a></li>\n";
	}
	}
	echo "<li><a href='".$admin_file.".php?siteid=$linksite' title='"._ADMINMENU."'><img src='images/icons/nhome.png' title='"._ADMINMENUNEW."' alt='"._ADMINMENUNEW."' /><br />"._ADMINMENUNEW."</a></li>";
	if($sql) {
	switch($op) {
		case "multimenuindex":
		echo "<li><a href='".$admin_file.".php?op=multimenunuovacat&amp;siteid=$siteid' title='"._AGGCAT."'><img src='images/icons/ncartadd.png' title='"._AGGCAT."' alt='"._AGGCAT."' /><br />"._AGGCAT."</a></li>";
		break;
		case "multimenuseel":
		if (isset($_GET['catid'])) {
			$catid=intval($_GET['catid']);
		echo "<li><a href='".$admin_file.".php?op=multimenunuovoel&amp;catid=$catid&amp;siteid=$siteid' title='"._AGGELEMENTO."'><img src='images/icons/nadd.png' title='"._AGGELEMENTO."' alt='"._AGGELEMENTO."' /><br />"._AGGELEMENTO."</a></li>";
		}
		break;
		
	}
	echo "<li><a href='".$admin_file.".php?op=multimenuindex$multiext' title='"._CLOSE."'><img src='images/icons/nclose.png' title='"._CLOSE."' alt='"._CLOSE."' /><br />"._CLOSE."</a></li>";
	}
	echo "</ul>"
		."</div>";
	echo "</div>";

}

function multimenuindex() {
    global $admin, $verifica, $prefix, $db, $sitename, $admin_file, $siteid, $multiext;
    include ("header.php");
	multiadminmenu('multimenuindex');
	$where = (!$verifica) ? "WHERE site_id='$siteid'" : "";
	$sqlcat = "SELECT *"
		. " FROM ".$prefix."_multimenu_cat "
		. $where
		. " ORDER BY site_id, catid ASC"
		;
	
	$siteq = "SELECT sitename, slogan, site_id"
		. " FROM ".$prefix."_config "
		. $where
		. " ORDER BY site_id ASC"
		;

	$sqlsite = $db->sql_query($siteq) or die(mysql_error());
	$site=array();
	while ($siterow = $db->sql_fetchrow($sqlsite)) {
		$site[ $siterow['site_id'] ]=array("sitename"=>$siterow['sitename'],"slogan"=>$siterow['slogan']);
	}
	
if($db->sql_query($sqlcat)) {
    OpenTable();
    $resultcat = $db->sql_query($sqlcat) or die(mysql_error());
    $numcat = $db->sql_numrows($resultcat);
    if ($numcat > 0) {
    echo "<table class=\"tabellaStruttura\">\n"
	  ."<tr>
	  <th class=\"header\">"._STATO."</th>
	  <th class=\"header\">"._INTOSITE."</th>
	  <th class=\"header\">"._CATELEM."</th>
	  <th class=\"header\">"._AZIONE."</th>
	  </tr>";
    while ($row = $db->sql_fetchrow($resultcat)) {
	  $catid = intval($row['catid']);
      $catname = filter_string($row['catname']);
	  $catattiva = intval($row['catattiva']);
	  $siteid1 = intval($row['site_id']);
	  
	  echo "<tr>";
	  if ($catattiva == 1) {
	  echo "<td class=\"center\"><img src=\"images/icons/active.png\" title=\""._ATTIVO."\" alt=\""._ATTIVO."\" /></td>";
	  } else {
	  echo "<td class=\"center\"><img src=\"images/icons/noactive.png\" title=\""._NONATTIVO."\" alt=\""._NONATTIVO."\" /></td>";
	  }
	  echo "<td>";
	  if ($siteid1!=0) {
	  foreach ($site as $ksite=>$vsite) {
			if ($ksite==$siteid1) {
				 echo $vsite['sitename']." - ".$vsite['slogan'];
			}
	  }
	  } else {
			echo _ALLS;
	  }
	  echo "</td>";
	  echo "<td class=\"bold\"><a href='".$admin_file.".php?op=multimenucatedit&amp;catid=$catid&amp;siteid=$siteid1' title=\""._EDITA."\">$catname</a></td>";
      echo "<td class=\"center\">
	  <a href=\"".$admin_file.".php?op=multimenuseel&amp;catid=$catid&amp;siteid=$siteid1\" title=\""._ELEM."\"><img src=\"images/icons/ncart.png\" title=\""._ELEM."\" alt=\""._ELEM."\" style=\"width:20px;\" /></a> 
	  <a href=\"".$admin_file.".php?op=multimenucatdel&amp;catid=$catid&amp;ok=0&amp;siteid=$siteid1\" title=\""._ELIMINA."\"><img src=\"images/icons/ndel.png\" alt=\""._ELIMINA."\" style=\"width:20px;\" /></a>
	  </td></tr>";
   }
    echo "</table>";
  } else {
    echo "<p class=\"center bold\">"._NOCAT."</p>\n";
  }
    CloseTable();
} //fine if sql
    include("footer.php");
}


//category
function multimenunuovacat() {
global $admin, $verifica, $bgcolor2, $prefix, $db, $textcolor1, $admin_file, $siteid, $multiext, $catpath;
include("header.php");
multiadminmenu('multimenunuovacat');
OpenTable();
	echo "<h2>"._AGGCAT."</h2>";
  echo "<form action=\"".$admin_file.".php?siteid=$siteid\" method=\"post\">"
	."<table class=\"tabellaStruttura\">\n"
	."<tr>
	<td><label for=\"catname\" title=\""._NOMECAT."\">"._NOMECAT."</label>:</td>
	<td><input type=\"text\" class=\"text\" name=\"catname\" style=\"width:100%;\" maxlength=\"60\"/></td>
	</tr>"
	."<tr>
	<td><label for=\"siteid\" title=\""._INTOSITE."\">"._INTOSITE."</label>:</td>
	<td><select id=\"siteid1\" name=\"siteid1\">";
	
	$where = (!$verifica) ? "WHERE site_id='$siteid'" : "";
	$siteq = "SELECT sitename, slogan, site_id"
		. " FROM ".$prefix."_config "
		. $where
		. " ORDER BY site_id ASC"
		;
		
	$sqlsite = $db->sql_query($siteq);
	$numsite=$db->sql_numrows($sqlsite);
	$site=array();
	if ($numsite>0) {
		while ($siterow = $db->sql_fetchrow($sqlsite)) {
		$site[ $siterow['site_id'] ]=array("sitename"=>$siterow['sitename'],"slogan"=>$siterow['slogan']);
		}
	}
	if ($verifica) {
		$site[0]=array("sitename"=>_ALLS, "slogan"=>"");
	}
	
	foreach ($site as $ksite=>$vsite) {
		($siteid==$ksite) ? $sel="selected=\"selected\"" : $sel="";
		echo "<option value=\"$ksite\" $sel>".$vsite['sitename']." ".$vsite['slogan']."</option>";
	}
	
	echo "</select></td>
	</tr>"
	."<tr>
	<td><label for=\"catattiva\" title=\""._CATATTIVA."\">"._CATATTIVA."</label>:</td>
	<td><select id=\"catattiva\" name=\"catattiva\"><option value=\"1\" selected=\"selected\">"._SI."</option><option value=\"0\">"._NO."</option></select></td>
	</tr>"
	."<tr>
	<td><label for=\"catcss\" title=\""._CATCSS."\">"._CATCSS."</label>:</td>
	<td><input type=\"text\" class=\"text\" name=\"catcss\" style=\"width:100%;\" maxlength=\"50\"/></td>
	</tr>"
      ."</table>
	  <input type=\"hidden\" name=\"op\" value=\"multimenucatadd\" readonly/>
	  <div class=\"save\"><input class=\"submit\" type=\"submit\" value=\""._SAVE."\" title=\""._SAVE."\"/></div>
	  </form>";
    CloseTable();
    include("footer.php");
}

function multimenucatadd ($catname, $catcss, $catattiva, $siteid1) {
global $verifica, $prefix, $db, $admin_file, $siteid;
	$siteid=(!$verifica) ? "$siteid" : "1";
	$catname = check_string($catname);
	$siteid1 = intval(check_string($siteid1));
	$catattiva = intval(check_string($catattiva));
	$catcss = check_string($catcss);
    $db->sql_query("INSERT INTO ".$prefix."_multimenu_cat values (NULL, '$catname', '$catattiva', '$catcss', '$siteid1')");
    Header("Location: ".$admin_file.".php?op=multimenuindex&siteid=$siteid");
}

function multimenucatdel ($catid, $ok=0) {
global $verifica, $prefix, $db, $admin_file, $siteid, $multiext;
	$siteid=(!$verifica) ? "$siteid" : "1";
    $catid = intval($catid);
    if($ok==1) {
		$db->sql_query("DELETE from ".$prefix."_multimenu_el WHERE elcatid='$catid'");
		$db->sql_query("DELETE from ".$prefix."_multimenu_cat WHERE catid='$catid'");
		Header("Location: ".$admin_file.".php?op=multimenuindex&siteid=$siteid");
    } else {
		include("header.php");
		multiadminmenu('multimenucatdel');
		OpenTable();
			echo "<h2>"._DELCATEGORIA."</h2>";
			$nome = $db->sql_query("select catname from ".$prefix."_multimenu_cat where catid='$catid'");
			list($catname) = $db->sql_fetchrow($nome);
			echo "<div class='title' style='text-align:center;'><strong>"._ELIMENU." $catname</strong></div>"
				."<div class='content' style='text-align:center;'>"._AVVISOELICAT."<br /><br /></div>";
			echo "<div class=\"center bold\"><p>"._CONTINUA."</p>";
			echo "<p><a class=\"link\" href=\"".$admin_file.".php?op=multimenucatdel&amp;catid=$catid&amp;ok=1&amp;siteid=$siteid\" title=\""._YES."\">"._YES."</a> <a class=\"link\" href=\"".$admin_file.".php?op=multimenuindex&amp;siteid=$siteid\" title=\""._NO."\">"._NO."</a></p></div>";
		CloseTable(); 
		include("footer.php");
    }
}

function multimenucatedit ($catid) {
global $verifica, $admin, $prefix, $db, $admin_file, $siteid, $multiext, $catpath;
	$catid = intval($catid);
	$category=array();
	
if(!empty($catid) and isset($catid)) {
	$result = $db->sql_query("SELECT * FROM ".$prefix."_multimenu_cat WHERE catid='$catid' LIMIT 1");
	while ($row = $db->sql_fetchrow($result)) {
	$catname = filter_string($row['catname']);
	$catattiva = intval($row['catattiva']);
	$catcss=filter_string($row['catcss']);
	$siteid1 = intval($row['site_id']);
	if($catattiva == "0"){
		$catat1 = "";
		$catat2 = "selected=\"selected\"";
	}
	else if($catattiva == "1"){
		$catat1 = "selected=\"selected\"";
		$catat2 = "";
	}
	
	$category[$catid]=array(
	"catname"=>$catname,
	"catattiva"=>$catattiva,
	"catcss"=>$catcss,
	"siteid1"=>$siteid1,
	"catat1"=>$catat1,
	"catat2"=>$catat2,
	);
	}
	
	$where = (!$verifica) ? "WHERE site_id='$siteid'" : "";
	$siteq = "SELECT sitename, slogan, site_id"
		. " FROM ".$prefix."_config "
		. $where
		. " ORDER BY site_id ASC"
		;
		
	$sqlsite = $db->sql_query($siteq);
	$numsite=$db->sql_numrows($sqlsite);
	$site=array();
	if ($numsite>0) {
		while ($siterow = $db->sql_fetchrow($sqlsite)) {
		$site[ $siterow['site_id'] ]=array("sitename"=>$siterow['sitename'],"slogan"=>$siterow['slogan']);
		}
	}
	if ($verifica) {
		$site[0]=array("sitename"=>_ALLS, "slogan"=>"");
	}
}

if(!empty($category)) {
	include("header.php");
	multiadminmenu('multimenucatedit');
	OpenTable();
	echo "<h2>"._EDITCATEGORIA."</h2>";	
	echo "<form action=\"".$admin_file.".php?siteid=$siteid\" method=\"post\">";
	foreach($category as $kcat=>$vcat) {
	echo "<table class=\"tabellaStruttura\">\n"
	."<tr>
	<td><label for=\"catname\" title=\""._NOMECAT."\">"._NOMECAT."</label>:</td>
	<td><input type=\"text\" class=\"text\" name=\"catname\" style=\"width:100%;\" maxlength=\"60\" value=\"".$vcat['catname']."\"/></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"siteid\" title=\""._INTOSITE."\">"._INTOSITE."</label>:</td>
	<td><select id=\"siteid1\" name=\"siteid1\">";
	foreach ($site as $ksite=>$vsite) {
		($vcat['siteid1']==$ksite) ? $sel="selected=\"selected\"" : $sel="";
		echo "<option value=\"$ksite\" $sel>".$vsite['sitename']." ".$vsite['slogan']."</option>";
	}
	echo "</select></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"catattiva\" title=\""._CATATTIVA."\">"._CATATTIVA."</label>:</td>
	<td><select id=\"catattiva\" name=\"catattiva\">
		<option value=\"1\" ".$vcat['catat1'].">"._SI."</option>
		<option value=\"0\" ".$vcat['catat2'].">"._NO."</option>
	</select>
	</td></tr>"
	."<tr>
	<td><label for=\"catcss\" title=\""._CATCSS."\">"._CATCSS."</label>:</td>
	<td><input type=\"text\" class=\"text\" name=\"catcss\" style=\"width:100%;\" maxlength=\"50\" value=\"".$vcat['catcss']."\"/></td>
	</tr>"
    ."</table>\n";
	  }
	  echo "<input type=\"hidden\" name=\"op\" value=\"multimenucatsave\" readonly />
	  <input type=\"hidden\" name=\"catid\" value=\"$catid\" readonly />
	  <div class=\"save\"><input class=\"submit\" type=\"submit\" value=\""._SAVE."\" title=\""._SAVE."\"/></div>
	  </form>";   
    CloseTable();
    include("footer.php");
} else {
	Header("Location: ".$admin_file.".php?op=multimenuindex&siteid=$siteid");
	}
}

function multimenucatsave($catid, $catname, $catattiva, $catcss, $siteid1) {
    global $prefix, $db, $admin_file, $siteid;
	$siteid=(!$verifica) ? "$siteid" : "1";
	$catid = intval(check_string($catid));
	$catname = check_string($catname);
	$catcss = check_string($catcss);
	$siteid1 = intval(check_string($siteid1));
    $db->sql_query("UPDATE ".$prefix."_multimenu_cat set catname='$catname', catattiva='$catattiva', catcss='$catcss', site_id='$siteid1' WHERE catid='$catid'") or die (mysql_error());
    Header("Location: ".$admin_file.".php?op=multimenuindex&siteid=$siteid");
}


//elementi menu
function multimenuseel($catid) {
global $admin, $prefix, $db, $sitename, $admin_file, $siteid, $multiext, $catpath;
$catid=intval($catid);
$sqlcat = $db->sql_query("SELECT catid, catname FROM ".$prefix."_multimenu_cat WHERE catid='$catid'");
    while ($row = $db->sql_fetchrow($sqlcat)) {
		$catid = intval($row['catid']);
		$catname = filter_string($row['catname']);
	}	
include("header.php");
multiadminmenu('multimenuseel');
	
    OpenTable();
	echo "<h2>"._ELENCOELEMENTS.": $catname</h2>";
	$sqlsee = $db->sql_query("SELECT * FROM ".$prefix."_multimenu_el WHERE elcatid='$catid' and site_id='$siteid' ORDER BY elpeso");
    $numcat = $db->sql_numrows($sqlsee);
	
    if ($numcat > 0) {
		$refs = array();
		$list = array();
		while($data = $db->sql_fetchrow($sqlsee)) {
			$thisref = &$refs[ intval($data['elid']) ];
			$thisref['elcatid'] = intval($data['elcatid']);
			$thisref['elparent'] = intval($data['elparent']);
			$thisref['elnome'] = filter_string($data['elnome']);
			$thisref['elpeso'] = intval($data['elpeso']);
			$thisref['elevel'] = intval($data['elevel']);
			$thisref['elurl'] = filter_string($data['elurl']);
			$thisref['elattivo'] = intval($data['elattivo']);
			if ($data['elparent'] == 0) {
				$list[ intval($data['elid']) ] = &$thisref;
			} else {
				$refs[ intval($data['elparent']) ]['children'][ intval($data['elid']) ] = &$thisref;
			}
	}
	
	echo "<table class=\"tabellaStruttura\"\">\n"
	  ."<tr>
	  <th class=\"header\">"._PESO."</th>
	  <th class=\"header\">"._STATO."</th>
	  <th class=\"header\">"._ELEM."</th>
	  <th class=\"header\">"._URL."</th>
	  <th class=\"header\">"._AZIONE."</th>
	  </tr>";

	function create_list($arr) {
		global $admin, $prefix, $db, $sitename, $admin_file, $siteid, $multiext, $catpath;
        $html="<tr>";
		
        foreach ($arr as $key=>$v) {
			$pesoel1 = $v['elpeso'] - 1;
			$pesoel2 = $v['elpeso'] + 1;
			
			$row_res = $db->sql_fetchrow($db->sql_query("select elid from ".$prefix."_multimenu_el where elpeso='$pesoel1' AND elparent='".$v['elparent']."' AND elcatid='".$v['elcatid']."' AND site_id='$siteid'"));
			$elid1 = intval($row_res['elid']);
			$pesel1 = "$elid1";
			
			$row_res2 = $db->sql_fetchrow($db->sql_query("select elid from ".$prefix."_multimenu_el where elpeso='$pesoel2' AND elparent='".$v['elparent']."' AND elcatid='".$v['elcatid']."' AND site_id='$siteid'"));
			$elid2 = intval($row_res2['elid']);
			$pesel2 = "$elid2";
			
				$html .="<td class=\"center\">";
				if ($pesel1) {
					$html .="<a title=\""._ELSU."\" href=\"".$admin_file.".php?op=multimenupesoel&amp;elpesorig=".$v['elpeso']."&amp;elidorig=".$key."&amp;elpesomod=$pesoel1&amp;elparent=".$v['elparent']."&amp;catid=".$v['elcatid']."$multiext\"><img src=\"images/icons/nup.png\" style=\"width:17%;\" alt=\""._CATSU."\" /></a>";
				}
				if ($pesel2) {
					$html .="<a title=\""._ELGIU."\" href=\"".$admin_file.".php?op=multimenupesoel&amp;elpesorig=".$v['elpeso']."&amp;elidorig=".$key."&amp;elpesomod=$pesoel2&amp;elparent=".$v['elparent']."&amp;catid=".$v['elcatid']."$multiext\"><img src=\"images/icons/ndown.png\" style=\"width:17%;\" alt=\""._CATGIU."\" /></a>";
				}
				$html .="</td>";
				
				if ($v['elattivo'] == 1) {
					$html .="<td class=\"center\"><img src=\"images/icons/active.png\" title=\""._ATTIVO."\" alt=\""._ATTIVO."\" /></td>";
				} else {
					$html .="<td class=\"center\"><img src=\"images/icons/noactive.png\" title=\""._NONATTIVO."\" alt=\""._NONATTIVO."\" /></td>";
				}
				$html .= "<td>";
				
				for ($a=0; $a<$v['elevel']; $a++){
					$html .= "<span style=\"color:#999999;\">|&mdash; </span>";
				}
				$html .= "<a href=\"".$admin_file.".php?op=multimenueledit&amp;elid=$key$multiext\" title=\""._EDITA."\">".$v['elnome']."</a></td>";
				$html .="<td><a href=\"".stripslashes(html_entity_decode($v['elurl']))."\" target=\"_blank\"><img src=\"images/icons/url.gif\" title=\""._VURL."\" alt=\""._VURL."\" /></a> ".stripslashes(html_entity_decode($v['elurl']))."</td>";
				$html .="<td class=\"center\"><a href=\"".$admin_file.".php?op=multimenueldel&amp;elid=$key&amp;ok=0$multiext\" title=\""._ELIMINA."\"><img src=\"images/icons/ndel.png\" style=\"width:30%;\" alt=\""._ELIMINA."\" /></a></td>
				</tr>";
				$html .="</tr>\n";
				
				if (array_key_exists('children', $v)) {
					$html .= create_list($v['children']);
				} else { 
					$html .="<tr>\n";
				}
        }
        return $html;
    }

echo create_list($list);
	echo "</table>";

/*/////////////////////////////////////////da usare lato user
function create_list($arr) {
        $html = "<ul>\n";
        foreach ($arr as $key=>$v) {
            $html .= '<li>'.$v['elnome']."\n";
            if (array_key_exists('children', $v)) {
                $html .= create_list($v['children']);
                $html .= "</li>\n";
            } else { 
				$html .="</li>\n";
				}
        }
        $html .= "</ul>\n";
        return $html;
    }

echo create_list($list);
*/	
	
/*
///////////////////////////////////
	foreach($menu_array as $key => $value) {
		if ($value['elparent'] == 0) {
		echo $value['elnome'] ."<br />";

			foreach($menu_array as $k => $v) {
				if ($v['elparent'] == $key) {
					echo "|_". $v['elnome']."<br />";
					$valore=$k;
				} elseif ($v['elparent'] == $valore) {
					echo "__|_". $v['elnome']."<br />";
				}
			}
		}
				
	}
 /////////////////////////////
*/

} else {
    echo "<p class=\"center bold\">"._NOELEMENTS."</p>\n";
}
	CloseTable();
    include("footer.php");
}

function multimenunuovoel($catid) {
global $admin, $prefix, $db, $sitename, $admin_file, $siteid, $multiext, $catpath;
$catid=intval($catid);
$sqlcat = $db->sql_query("SELECT catid, catname FROM ".$prefix."_multimenu_cat WHERE catid='$catid'");
    while ($row = $db->sql_fetchrow($sqlcat)) {
		$catid = intval($row['catid']);
		$catname = $row['catname'];
	}
	
//select category	
$riscat = $db->sql_query("SELECT elid, elnome, elparent, elevel FROM " .$prefix. "_multimenu_el WHERE elcatid='$catid' AND site_id='$siteid' ORDER BY elpeso");
$numrows=$db->sql_numrows($riscat);

if ($numrows > 0) {	
	$refs = array();
	$list = array();
	while ($row = $db->sql_fetchrow($riscat)) {
		$thisref = &$refs[ $row['elid'] ];
		$thisref['elnome'] = $row['elnome'];
		$thisref['elevel'] = $row['elevel'];
		if ($row['elparent'] == 0) {
			$list[ $row['elid'] ] = &$thisref;
		} else {
			$refs[ $row['elparent'] ]['children'][ $row['elid'] ] = &$thisref;
		}
	}

	function create_select($arr) {
        $html="";
        foreach ($arr as $key=>$v) {
			$html .="<option value=\"".$key."\">";
			for ($a=0; $a<$v['elevel']; $a++){
			$html .= "|&mdash;";
			}
			$html .= $v['elnome']."</option>";
            if (array_key_exists('children', $v)) {
                $html .= create_select($v['children']);
            } else { 
				$html .="";
				}
        }
        return $html;
    }
}

include("header.php");
multiadminmenu('multimenunuovoel');

OpenTable();
	echo "<h2>"._AGGELEMENTO.": $catname</h2>";
  echo "<script type=\"text/javascript\">
$(document).ready(function(){
$('#immagineel').bind('change', function() {
         var update_pic = $(this).val();
         if (update_pic) {
             $('#multimenuimgNewPic').attr('src', '$catpath/' + update_pic +
'' );
         }
     });

});
</script>";

echo <<< data1
<script language="javascript" type="text/javascript">
<!--
$(document).ready(function(){
$('.urlselect').click( function() {
	var urlblock=$('#nblocks').val();
	var urlmodules=$('#nmodules').val();
	
	if (urlblock != '0') {
		$('#nmodules').attr('disabled', true);
		$('#ntext').attr('disabled', true);
	}
	if (urlmodules != '0') {
		$('#nblocks').attr('disabled', true);
		$('#ntext').attr('disabled', true);
	}
	
	if ((urlblock == '0') && (urlmodules=='0')) {
		$('#nblocks').attr('disabled', false);
		$('#nmodules').attr('disabled', false);
		$('#ntext').attr('disabled', false);
	}
});
});
//-->
</script>
data1;

	echo "<form action=\"".$admin_file.".php?siteid=$siteid\" method=\"post\">"
	."<table class=\"tabellaStruttura\">\n";
	
	echo "<tr>
	<td><label for=\"elnome\" title=\""._NOMEEL."\">"._NOMEEL."</label>:</td>
	<td><input type=\"text\" class=\"text\" style=\"width:100%;\" name=\"elnome\" value=\"\" maxlength=\"60\" /></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"elid\" title=\""._CATEGORIA."\">"._CATEGORIA."</label>:</td>
	<td>";

	echo "<select id=\"elparent\" name=\"elparent\">";
	if (!empty($list)) {
	echo "<option value=\"0\">"._NOCATEGORY."</option>";
		echo create_select($list);	
	} else {
		echo "<option value=\"0\">"._NOELEMENTS."</option>";
	}
	echo "</select>";
	echo "</td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"immagineel\" title=\""._IMMAGINE."\">"._IMMAGINE."</label>:</td>
	<td><select id=\"immagineel\" name=\"elimg\">";
    $handle = @opendir("blocks/block-Multi_Menu/theme/img/site".$siteid."");
	echo "<option value=\"../0.gif\">Seleziona una immagine</option>";
	$tlist = '';
	while ($file = @readdir($handle)) if (($file != "0.gif") && (preg_match("/.*\.(png|gif|jpg|jpeg)$/", $file))) $tlist .= "$file|";
	@closedir($handle);
	$tlist = explode("|", $tlist);
	sort($tlist);
	for ($i = 0; $i < sizeof($tlist); $i++) if ($tlist[$i] != "") echo "<option value=\"$tlist[$i]\">$tlist[$i]</option>";
	echo "
	</select>
	</td></tr>
	<tr>
	<td colspan=\"2\"><p class=\"center\"><img id=\"multimenuimgNewPic\" src=\"blocks/block-Multi_Menu/theme/img/0.gif\" alt=\"Seleziona Immagine\" /></p></td>
	</tr>";
			
	echo "<tr>
	<td><label for=\"elurl\" title=\""._NBLOCKS."\">"._NBLOCKS."</label>:</td>
	<td><select id=\"nblocks\" name=\"nblocks\" class=\"urlselect\">";
	$nblock = $db->sql_query("SELECT blockfile from " . $prefix . "_apblocks WHERE blockfile!='' AND active='1' AND site_id='$siteid' order by title ASC");
	$numblocks=$db->sql_numrows($nblock);
	if ($numblocks > 0) {
	echo "<option value=\"0\">"._SELECTB."</option>";
	while ($rowb=$db->sql_fetchrow($nblock)) {
		$name_blocks=$rowb['blockfile'];
		if(substr($name_blocks, 0, 6) == "block-") {
				$name_blocks2 = substr($name_blocks, 6);
				$name_blocks2 = preg_replace("/.php/","",$name_blocks2);
				$name_blocks2 = preg_replace("/_/"," ",$name_blocks2);
			} 
		echo "<option value=\"$name_blocks\">$name_blocks2</option>";
		}
	} else {
		echo "<option value=\"0\">"._NOELEMENTS."</option>";
		}
	echo "</select>
		</td>
		</tr>";
		
	echo "<tr>
	<td><label for=\"elurl\" title=\""._NMODULES."\">"._NMODULES."</label>:</td>
	<td><select id=\"nmodules\" name=\"nmodules\" class=\"urlselect\">";
	$nmodules = $db->sql_query("SELECT title from " . $prefix . "_modules WHERE active='1' AND site_id='$siteid' order by title ASC");
	$nummodules=$db->sql_numrows($nmodules);
	if ($nummodules > 0) {
	echo "<option value=\"0\">"._SELECTM."</option>";
	while ($rowm=$db->sql_fetchrow($nmodules)) {
		$name_modules=$rowm['title'];
		$url_modules="modules.php?name=".$name_modules;
		echo "<option value=\"$url_modules\">$name_modules</option>";
		}
	} else {
		echo "<option value=\"0\">"._NOELEMENTS."</option>";
		}
		
	echo "</select>
		</td>
	</tr>";
		
	echo "<tr>
	<td><label for=\"elurl\" title=\""._URLM."\">"._URLM."</label>:</td>
	<td><input type=\"text\" class=\"text\" id=\"ntext\" style=\"width:100%;\" name=\"ntext\" size=\"40\" value=\"\" maxlength=\"200\" /></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"eltarget\" title=\""._TARGET."\">"._TARGET."</label>:</td>
	<td><select id=\"eltarget\" name=\"eltarget\">
		<option value=\"\" selected=\"selected\">"._STESSAP."</option>
		<option value=\"_blank\">"._NUOVAP."</option>
	</select></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"elattivo\" title=\""._ELATTIVO."\">"._ELATTIVO."</label>:</td>
	<td><select id=\"elattivo\" name=\"elattivo\">
		<option value=\"1\" selected=\"selected\">"._SI."</option>
		<option value=\"0\">"._NO."</option>
	</select></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"elcustom\" title=\""._ELCUSTOM."\">"._ELCUSTOM."</label>:</td>
	<td><input type=\"text\" class=\"text\" style=\"width:100%;\" name=\"elcustom\" size=\"40\" maxlength=\"50\" value=\"\" /></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"elcss\" title=\""._ELCSS."\">"._ELCSS."</label>:</td>
	<td><input type=\"text\" class=\"text\" style=\"width:100%;\" name=\"elcss\" size=\"40\" maxlength=\"50\" value=\"\" /></td>
	</tr>";
	
	echo "</table>
	<input type=\"hidden\" name=\"elcatid\" value=\"$catid\" />
	<input type=\"hidden\" name=\"op\" value=\"multimenueladd\" />
	<div class=\"save\"><input class=\"submit\" type=\"submit\" value=\""._SAVE."\" title=\""._SAVE."\"/></div>
	</form>";
    CloseTable();
    include("footer.php");
}

function multimenueladd($elcatid, $elnome, $elimg, $nblocks, $nmodules, $ntext, $elparent, $eltarget, $elattivo, $elcustom, $elcss) {
global $prefix, $db, $admin_file, $siteid;
	$elcatid = intval($elcatid);
	$elnome = check_string($elnome);
	$elimg = check_string($elimg);
	$elparent = intval($elparent);
	$eltarget = check_string($eltarget);
	$elattivo = intval($elattivo);
	$elcustom=check_string($elcustom);
	$elcss=check_string($elcss);
	//calcolo peso
	$rowpeso = $db->sql_query("SELECT elid, elparent, elpeso, elevel FROM ".$prefix."_multimenu_el WHERE site_id='$siteid' AND elcatid='$elcatid' ORDER BY elpeso DESC");
	$peso=array('0');
	while ($row = $db->sql_fetchrow($rowpeso)) {
	if ($row['elid']==$elparent) {
		$elevel=$row['elevel'];
		}
	if ($row['elparent']==$elparent) {
		$peso[]=$row['elpeso'];
		}
	}
	$elpeso = max($peso);
	$elpeso++;
	if ($elparent!=0) {
		$elevel++;
	} else {
		$elevel=0;
	}
	//fine peso
	if(!empty($nmodules)) {
		$elurl=check_string($nmodules);
	} else if (!empty($nblocks)) {
		$elurl=check_string($nblocks);
	} else {
		$elurl = check_string($ntext);
	}
	$elurl = preg_replace("/&amp;/", "&", $elurl);
	$elurl = preg_replace("/&/", "&amp;", $elurl);
	
   $db->sql_query("INSERT INTO ".$prefix."_multimenu_el values (NULL, '$elcatid', '$elnome', '$elimg', '$elurl', '$elparent', '$eltarget', '$elattivo', '$elpeso', '$elevel', '$elcustom', '$elcss', '$siteid')");
	Header("Location: ".$admin_file.".php?op=multimenuseel&catid=$elcatid&siteid=$siteid");
}

function multimenueldel ($elid, $ok=0) {
global $prefix, $db, $admin_file, $siteid, $multiext;
    $elid = intval($elid);
    if($ok==1) {
	$row = $db->sql_fetchrow($db->sql_query("SELECT elcatid, elpeso, elparent FROM ".$prefix."_multimenu_el WHERE elid='$elid' AND site_id='$siteid'"));
	$elcatid = intval($row['elcatid']);
	$elpeso = intval($row['elpeso']);
	$elparent = intval($row['elparent']);
	
	$resultpeso = $db->sql_query("SELECT elid FROM ".$prefix."_multimenu_el WHERE elcatid='$elcatid' AND elpeso>'$elpeso' AND elparent='$elparent' AND site_id='$siteid' ORDER BY elpeso");
	
	while ($row2 = $db->sql_fetchrow($resultpeso)) {
	$nmenid = intval($row2['elid']);
	    $db->sql_query("UPDATE ".$prefix."_multimenu_el SET elpeso='$elpeso' WHERE elcatid='$elcatid' AND elid='$nmenid' AND site_id='$siteid'");
	    $elpeso++;
	}
	
	$sql_del = $db->sql_query("SELECT * FROM ".$prefix."_multimenu_el WHERE elcatid='$elcatid' AND site_id='$siteid' ORDER BY elpeso");
	$refs = array();
	$list = array();
	while ($data=$db->sql_fetchrow($sql_del)) {
		$thisref = &$refs[ $data['elid'] ];
		$thisref['elid'] = $data['elid'];
		$thisref['elnome'] = $data['elnome'];
    if (($data['elparent'] == $elparent) && ($data['elid']==$elid))  {
        $list[ $data['elid'] ] = &$thisref;
    } else {
        $refs[ $data['elparent'] ][ $data['elid'] ] = &$thisref;
    }
	}
	
	function del_list($arr) {
	global $prefix, $db, $admin_file, $siteid, $multiext;
		foreach ($arr as $k1=>$v1) {
			if (is_array($v1)) {
			$delid=$v1['elid'];
			del_list($v1);
			}	
		$db->sql_query("DELETE FROM ".$prefix."_multimenu_el WHERE elid='$delid'");
		}
	}
	del_list($list);
    Header("Location: ".$admin_file.".php?op=multimenuseel&catid=$elcatid&siteid=$siteid");
} else {
	include("header.php");
	multiadminmenu('multimenueldel');
	OpenTable();
	echo "<h2>"._ELICAT."</h2>";
	$nome = $db->sql_query("select elnome, elcatid from ".$prefix."_multimenu_el where elid='$elid' AND site_id='$siteid'");
	list($elnome, $elcatid) = $db->sql_fetchrow($nome);
	echo "<div class='title' style='text-align:center;'><strong>"._ELIMEN." $elnome</strong></div>"
	."<div class='content' style='text-align:center;'>"._AVVISOELIEL."<br /><br /></div>";
	echo "<div class=\"center bold\"><p>"._CONTINUA."</p>";	
	echo "<p><a class=\"link\" href=\"".$admin_file.".php?op=multimenueldel&amp;elid=$elid&amp;ok=1$multiext\" title=\""._YES."\">"._YES."</a> <a class=\"link\" href=\"".$admin_file.".php?op=multimenuseel&catid=$elcatid$multiext\" title=\""._NO."\">"._NO."</a></p></div>";
	CloseTable(); 
	include("footer.php");
}
}

function modselectparent ($elcatid, $elparent, $elid) {
global $admin, $bgcolor2, $prefix, $db, $textcolor1, $admin_file, $siteid, $multiext, $catpath;
	$elcatid = intval($elcatid);
	$elparent = intval($elparent);
	$elid = intval($elid);
	
	$riscat = $db->sql_query("SELECT elid, elnome, elparent, elevel from " . $prefix . "_multimenu_el WHERE elcatid='$elcatid' AND elid!='$elid' AND site_id='$siteid' ORDER BY elpeso");
	echo "<select id=\"elparent\" name=\"elparent\">\n";
	$numrows=$db->sql_numrows($riscat);
	if ($numrows > 0) {
	if ($elparent=='0') {
		echo "<option selected=\"selected\" value=\"0\">"._NOCATEGORY."</option>\n";
	} else {
		echo "<option value=\"0\">"._NOCATEGORY."</option>\n";
	}
	$refs = array();
	$list = array();
	while ($row1 = $db->sql_fetchrow($riscat)) {
		$thisref = &$refs[ $row1['elid'] ];
		$thisref['elnome'] = $row1['elnome'];
		$thisref['elevel'] = $row1['elevel'];
		$thisref['elparent'] = $row1['elparent'];
		if ($row1['elparent'] == 0) {
			$list[ $row1['elid'] ] = &$thisref;
		} else {
			$refs[ $row1['elparent'] ]['children'][ $row1['elid'] ] = &$thisref;
		}
	}

	function create_select($arr, $n) {
	global $admin, $bgcolor2, $prefix, $db, $sitename, $textcolor1, $admin_file, $siteid, $multiext, $catpath;
        $html="";
        foreach ($arr as $key=>$v) {
			$html .="<option ";
			if ($n==$key) {
				$html .="selected=\"selected\" ";
			}
			$html .="value=\"".$key."\">";
			for ($a=0; $a<$v['elevel']; $a++){
			$html .= "|&mdash;";
			}
			$html .= $v['elnome']."</option>\n";
            if (array_key_exists('children', $v)) {
                $html .= create_select($v['children'], $n);
            } else { 
				$html .="";
				}
        }
        return $html;
    }
	echo create_select($list, $elparent);
	
	} else {
		echo "<option value=\"0\">"._NOCATEGORY."</option>\n";
		}
	echo "</select>\n";
}

function multimenueledit ($elid) {
global $admin, $prefix, $db, $admin_file, $siteid, $multiext, $catpath;
$elid = intval($elid);

include("header.php");
multiadminmenu('multimenueledit');
OpenTable();
echo "<h2>"._MODELEMENTO."</h2>";

	$result = $db->sql_query("SELECT * FROM ".$prefix."_multimenu_el where elid='$elid' AND site_id='$siteid'");
	while ($row = $db->sql_fetchrow($result)) {
	$elid = intval($row['elid']);
	$elcatid = intval($row['elcatid']);
	$elnome = filter_string($row['elnome']);
	$elimg = filter_string($row['elimg']);
	$elurl = filter_string($row['elurl']);
	$elparent = intval($row['elparent']);
	$eltarget = filter_string($row['eltarget']);
	$elattivo = intval($row['elattivo']);
	$elurl = preg_replace("/&amp;/", "&", $elurl);
	$elcustom=filter_string($row['elcustom']);
	$elcss=filter_string($row['elcss']);
	if($eltarget == ""){
		$selt1 = "selected=\"selected\"";
		$selt2 = "";
	}
	else {
		$selt1 = "";
		$selt2 = "selected=\"selected\"";
	}
	
	if($elattivo == "0"){
		$elat1 = "";
		$elat2 = "selected=\"selected\"";
	}
	else if($elattivo == "1"){
		$elat1 = "selected=\"selected\"";
		$elat2 = "";
	}
	
	$melurl=0;
	$belurl=0;	
	$telurl=0;
	//verifico url
	if((substr($elurl, 0, 7) == "modules") and (!strstr ($elurl, '&amp;'))) {
		$melurl=$elurl;
	} else if(substr($elurl, 0, 6) == "block-") {
		$belurl=$elurl;
	} else {
		$telurl=$elurl;
		}
	
echo "<script type=\"text/javascript\">
$(document).ready(function(){
$('#immagineel').bind('change', function() {
         var update_pic = $(this).val();
         if (update_pic) {
             $('#multimenuimgNewPic').attr('src', '$catpath/' + update_pic +
'' );
         }
     });
});

function setup_menu(){
    $('#elcatid').change(mod_parent);
  }
function mod_parent(){
    var elcatid=$('#elcatid').attr('value');
	var elparent='$elparent';
	var elid='$elid';
	$.get('admin.php?op=modselectparent&elcatid=' + elcatid +'&elparent=' + elparent +'&elid=' + elid, mostra_menu);
  }
function mostra_menu(risultato){
    $('#elparent').html(risultato);
  }
  
$(document).ready(setup_menu);
	
</script> ";

echo <<< data1
<script language="javascript" type="text/javascript">
$(document).ready(function(){
var ublock="$belurl";
var umodules="$melurl";
var utext="$telurl";

if (ublock != '0') {
	$('#nmodules').attr('disabled', true);
	$('#ntext').attr('disabled', true);
} 
if (umodules != '0') {
	$('#nblocks').attr('disabled', true);
	$('#ntext').attr('disabled', true);
} 

/*if(utext != '0') {
	$('#nblocks').attr('disabled', true);
	$('#nmodules').attr('disabled', true);
}*/

if ((ublock == '0') && (umodules=='0') && (utext=='0')) {
	$('#nblocks').attr('disabled', false);
	$('#nmodules').attr('disabled', false);
	$('#ntext').attr('disabled', false);
}

$('.urlselect').click( function() {
	var urlblock=$('#nblocks').val();
	var urlmodules=$('#nmodules').val();
	
	if (urlblock != '0') {
		$('#nmodules').attr('disabled', true);
		$('#ntext').attr('disabled', true);
	}
	if (urlmodules != '0') {
		$('#nblocks').attr('disabled', true);
		$('#ntext').attr('disabled', true);
	}
	
	if ((urlblock == '0') && (urlmodules=='0')) {
		$('#nblocks').attr('disabled', false);
		$('#nmodules').attr('disabled', false);
		$('#ntext').attr('disabled', false);
	}
});

});
</script>
data1;

	echo "<form name=\"model\" action=\"".$admin_file.".php?siteid=$siteid\" method=\"post\">"
	."<table class=\"tabellaStruttura\">\n"
	."<tr>
	<td><label for=\"elnome\" title=\""._NOMEEL."\">"._NOMEEL."</label>:</td>
	<td><input type=\"text\" class=\"text\" style=\"width:100%;\" name=\"elnome\" value=\"$elnome\" maxlength=\"60\" /></td>
	</tr>";

	echo "<tr>
	<td><label for=\"elparent\" title=\""._CAT."\">"._CAT."</label>:</td>
	<td><select id=\"elcatid\" name=\"elcatid\">";
	$sql_cat = $db->sql_query("SELECT catid, catname FROM " .$prefix. "_multimenu_cat WHERE site_id='$siteid' OR site_id='0'");
	$numrows=$db->sql_numrows($sql_cat);
	if ($numrows > 0) {
	while ($row_cat = $db->sql_fetchrow($sql_cat)) {
		$catid = intval($row_cat['catid']);
		$catname = $row_cat['catname'];
		if ($elcatid==$catid) { $menusel="selected=\"selected\" "; } else { $menusel=""; }
			echo "<option $menusel value=\"$catid\">$catname</option>";
		}
	} else {
		echo "<option value=\"0\">"._NOELEMENTS."</option>";
	}
	echo "</select></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"elparent\" title=\""._CATEGORIA."\">"._CATEGORIA."</label>:</td>
	<td>";
	modselectparent($elcatid, $elparent, $elid);
	echo "</td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"immagineel\" title=\""._IMMAGINE."\">"._IMMAGINE."</label>:</td>
	<td><select id=\"immagineel\" name=\"elimg\">";
    
	$handle = opendir("$catpath");
	$tlist = '';
	while ($file = readdir($handle)) if (($file != "0.gif") && (preg_match("/.*\.(png|gif|jpg|jpeg)$/", $file))) $tlist .= "$file|";
	closedir($handle);
	echo "<option value=\"../0.gif\">Default</option>";
	$tlist = explode("|", $tlist);
	sort($tlist);
	for ($i = 0; $i < sizeof($tlist); $i++) {
		if ($tlist[$i] != "") {
			if ($tlist[$i] == $elimg) $sel = "selected=\"selected\""; else $sel = '';
			echo "<option value=\"$tlist[$i]\" $sel>$tlist[$i]</option>";
		}
	}
	echo "</select></td></tr>"
	."<tr>
	<td colspan=\"2\"><div class=\"center\"><img id=\"multimenuimgNewPic\" src=\"$catpath/$elimg\" alt=\"Immagine Selezionata\" /></div></td></tr>";
	
	echo "<tr>
	<td><label for=\"elurl\" title=\""._NBLOCKS."\">"._NBLOCKS."</label>:</td>"
	."<td>";
	echo "<select id=\"nblocks\" class=\"urlselect\" name=\"nblocks\">";
	$nblock = $db->sql_query("SELECT blockfile from " . $prefix . "_apblocks WHERE blockfile!='' AND active='1' AND site_id='$siteid' order by title ASC");
	$numblocks=$db->sql_numrows($nblock);
	if ($numblocks > 0) {
	echo "<option value=\"0\">"._SELECTB."</option>";

	while ($rowb=$db->sql_fetchrow($nblock)) {
		$name_blocks=$rowb['blockfile'];
		if(substr($name_blocks, 0, 6) == "block-") {
				$name_blocks2 = substr($name_blocks, 6);
				$name_blocks2 = preg_replace("/.php/","",$name_blocks2);
				$name_blocks2 = preg_replace("/_/"," ",$name_blocks2);
			} 
		if ($name_blocks == $elurl) $selb = "selected=\"selected\""; else $selb = '';
		echo "<option value=\"$name_blocks\" $selb>$name_blocks2</option>";
		}
		
	} else {
		echo "<option value=\"0\">"._NOELEMENTS."</option>";
		}
	echo "</select>
		</td></tr>";
		
	echo "<tr>
	<td><label for=\"elurl\" title=\""._NMODULES."\">"._NMODULES."</label>:</td>
	<td>";
	echo "<select id=\"nmodules\" class=\"urlselect\" name=\"nmodules\">";
	$nmodules = $db->sql_query("SELECT title from " . $prefix . "_modules WHERE active='1' AND site_id='$siteid' order by title ASC");
	$nummodules=$db->sql_numrows($nmodules);
	if ($nummodules > 0) {
	echo "<option value=\"0\">"._SELECTM."</option>";
	while ($rowm=$db->sql_fetchrow($nmodules)) {
		$name_modules=$rowm['title'];
		$url_modules="modules.php?name=".$name_modules;
		$name_modules2 = preg_replace("/modules.php\?name=/","",$elurl);
		if ($name_modules == $name_modules2) $selm = "selected=\"selected\""; else $selm = '';
			echo "<option value=\"$url_modules\" $selm>$name_modules</option>";
		}
	} else {
		echo "<option value=\"0\">"._NOELEMENTS."</option>";
		}
	echo "</select>
		</td></tr>";
		
	echo "<tr>
	<td><label for=\"elurl\" title=\""._URLM."\">"._URLM."</label>:</td>
	<td>";
	if (($belurl=='') && ($melurl=='')) { $telurl=$elurl; } else { $telurl=""; }
		echo "<input type=\"text\" class=\"text\" style=\"width:100%;\" id=\"ntext\" name=\"ntext\" value=\"$telurl\" maxlength=\"200\" />";
	echo "</td></tr>";
	
	echo "<tr>
	<td><label for=\"eltarget\" title=\""._TARGET."\">"._TARGET."</label>:</td>
	<td><select id=\"eltarget\" name=\"eltarget\">
		<option value=\"\" $selt1>"._STESSAP."</option>
		<option value=\"_blank\" $selt2>"._NUOVAP."</option>
	</select></td></tr>";
	
	echo "<tr>
	<td><label for=\"elattivo\" title=\""._ELATTIVO."\">"._ELATTIVO."</label>:</td>
	<td><select id=\"elattivo\" name=\"elattivo\">
		<option value=\"1\" $elat1>"._SI."</option>
		<option value=\"0\" $elat2>"._NO."</option>
	</select></td></tr>";
	
	echo "<tr>
	<td><label for=\"elcustom\" title=\""._ELCUSTOM."\">"._ELCUSTOM."</label>:</td>
	<td><input type=\"text\" class=\"text\" style=\"width:100%;\" name=\"elcustom\" maxlength=\"50\" value=\"".$elcustom."\" /></td>
	</tr>";
	
	echo "<tr>
	<td><label for=\"elcss\" title=\""._ELCSS."\">"._ELCSS."</label>:</td>
	<td><input type=\"text\" class=\"text\" style=\"width:100%;\" name=\"elcss\" maxlength=\"50\" value=\"$elcss\" /></td>
	</tr>";
	
    echo "</table>
	<input type=\"hidden\" name=\"op\" value=\"multimenuelsave\" readonly />
	<input type=\"hidden\" name=\"elid\" value=\"$elid\" readonly />
	<div class=\"save\"><input class=\"submit\" type=\"submit\" value=\""._SAVE."\" title=\""._SAVE."\"/></div>
	</form>";
}
    CloseTable();
    include("footer.php");
}

function multimenuelsave($elid, $elcatid, $elnome, $elimg, $nblocks, $nmodules, $ntext, $elparent, $eltarget, $elattivo, $elcustom, $elcss) {
    global $prefix, $db, $admin_file, $siteid;
    $elid = intval($elid);
	$elcatid = intval($elcatid);
	$elnome = check_string($elnome);
	$elimg = check_string($elimg);
	$elparent = intval($elparent);
	$eltarget = check_string($eltarget);
	$elattivo = intval($elattivo);
	$elcustom=check_string($elcustom);
	$elcss=check_string($elcss);
	
	if(!empty($nmodules)) {
		$elurl=check_string($nmodules);
	} else if (!empty($nblocks)) {
		$elurl=check_string($nblocks);
	} else {
		$elurl = check_string($ntext);
	}
	$elurl = preg_replace("/&amp;/", "&", $elurl);
	$elurl = preg_replace("/&/", "&amp;", $elurl);

	
	$sql=$db->sql_query("SELECT elid, elcatid, elpeso, elevel, elparent FROM ". $prefix ."_multimenu_el WHERE site_id='$siteid'");
	
	$numrow=$db->sql_numrows($sql);
	if ($numrow > 0) {
	$peso=array(0);
		while ($row=$db->sql_fetchrow($sql)) {
			if (($elparent==$row['elparent']) && ($elcatid==$row['elcatid'])){
				$elevel1=intval($row['elevel']);
				$peso[$row['elid']]=$row['elpeso'];
			} else {
				if ($elparent==$row['elid']) {
					$elevel1=intval($row['elevel'])+1;
				}
			}
		$voci[$row['elid']]=array('elcatid'=>$elcatid,'elparent'=>$row['elparent'], 'elevel'=>$row['elevel'], 'elpeso'=>$row['elpeso']);
		}
	$refs = array();
	$list = array();
		foreach ($voci as $k=>$data) {
			$thisref = &$refs[ $k ];
			$thisref['elparent'] = $data['elparent'];
			$thisref['elevel'] = $data['elevel'];
			$thisref['elcatid'] = $data['elcatid'];
			
		if ($k==$elid)  {
			$dif=$elevel1-$data['elevel'];
			if (array_key_exists($elid, $peso)) {
				foreach ($peso as $kp=>$vp) {
					if ($elid==$kp) {
						$elpeso = $data['elpeso'];
					} 
				}
			} else {
				$elpeso=max($peso);
				$elpeso++;
			}
			$thisref['elpeso'] = $elpeso;
			$list[ $k ] = &$thisref;
		} else {
			$thisref['elpeso'] = $data['elpeso'];
			$refs[ $data['elparent'] ][ $k ] = &$thisref;
		}
		}
	
	function down_list($arr, $v) {
	global $prefix, $db, $admin_file, $siteid, $multiext;
		foreach ($arr as $k1=>$v1) {
			if (is_array($v1)) {
				$elevel=$v1['elevel']+$v;
				$db->sql_query("UPDATE ".$prefix."_multimenu_el set elcatid='".$v1['elcatid']."', elevel='$elevel' WHERE elid='".$k1."' AND site_id='$siteid'");
			down_list($v1, $v);
			}
		}
	}
	down_list($list, $dif);
	
	$db->sql_query("UPDATE ".$prefix."_multimenu_el set elcatid='$elcatid', elnome='$elnome', elimg='$elimg', elurl='$elurl', elparent='$elparent', eltarget='$eltarget', elattivo='$elattivo', elpeso='$elpeso', elcustom='$elcustom', elcss='$elcss' WHERE elid='$elid' AND site_id='$siteid'");
	Header("Location: ".$admin_file.".php?op=multimenuseel&catid=$elcatid&siteid=$siteid");
	} else {
	Header("Location: ".$admin_file.".php?op=multimenuindex&siteid=$siteid");
	}
}

function multimenupesoel($elpesomod, $elpesorig, $elidorig, $elparent, $catid) {
    global $prefix, $db, $admin_file, $siteid;
    $elidorig = intval($elidorig);
    $result = $db->sql_query("update ".$prefix."_multimenu_el set elpeso='".intval($elpesorig)."' where elparent='$elparent' AND elpeso='$elpesomod' AND site_id='$siteid'");
    $result2 = $db->sql_query("update ".$prefix."_multimenu_el set elpeso='".intval($elpesomod)."' where elparent='$elparent' AND elid='$elidorig' AND elpeso='$elpesorig' AND site_id='$siteid'");
    Header("Location: ".$admin_file.".php?op=multimenuseel&catid=$catid&siteid=$siteid");
}


// Gestione Immagini
function multimenuimmagini() {
 global $admin_file, $catpath, $siteid, $multiext;
 include('header.php');
 multimenu();
 if (!file_exists("".$catpath."")) {
OpenTable();
echo "<div class=\"content\" style=\"text-align:center\"><strong>[ <span style=\"color:#FF0000;\">"._ATTENZIONE."</span>: <a href=\"".$admin_file.".php?op=multimenuCreateDir&amp;siteid=$siteid\" title=\""._NO_IMAGE_UPLOAD_DIRECTORY."\">"._NO_IMAGE_UPLOAD_DIRECTORY."</a> ]</strong></div>";
CloseTable();
} else {
OpenTable();
    echo "<div class='title' style='float:left; width:400px; margin-top:20px;'><strong>"._GESTIMG."</strong></div>";
	multimenulink('multimenuimmagini');
CloseTable();	

 OpenTable();
	$uploaddir = 'blocks/block-Multi_Menu/theme/img/site'.$siteid.''; 
	echo "<div style=\"text-align:center\">
		<strong>"._UPIMG."</strong><br /><br /></div>
		<form enctype=\"multipart/form-data\" action=\"$admin_file.php?op=multimenuuploadimg$multiext\" method=\"post\">
			<div style=\"text-align:center\"><input type=\"hidden\" name=\"size\" value=\"size\" />
			<label for=\"userfile\" title=\""._INVIMG."\">"._INVIMG."</label>: <input id=\"userfile\" name=\"userfile\" type=\"file\" />
			<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"51200\"> 
			<input type=\"submit\" value=\"UPLOAD\" />
		<br /></div>
		</form>";
// Mostra la lista delle immagini	
	echo "<div style=\"text-align:center\">&nbsp;</div>";
	echo "<table width='80%' cellpadding='2' cellspacing='1' style=\"background:$bgcolor1;margin:0 auto; border:1px solid $textcolor1;\">\n";
	echo "<tr><td style=\"text-align:center\"><strong>"._IMGIN."</strong></td></tr>";
	if ($handle = opendir($uploaddir)) {
		while ($file = readdir($handle)) {
			if (($file != "0.jpg") && (preg_match("/.*\.(png|gif|jpg|jpeg)$/", $file))) {
			echo "<tr><td colspan=\"2\" style=\"text-align:center;background:$bgcolor2;\">$file</td></tr>";
				echo "<tr><td style=\"vertical-align:middle; text-align:center;\"><img src=\"$uploaddir/$file\" alt=\"$file\" /></td><td style=\"vertical-align:middle; text-align:center;\"><a href=\"$admin_file.php?op=multimenudelimg&amp;siteid=$siteid&amp;delmenuimg=$file&amp;ok=0\"><img src=\"images/icons/delete.png\" alt=\""._ELIMINA."\" title=\""._ELIMINA."\" /></a><br /></td></tr>";
			}
		}
		closedir($handle);
		echo "</table>";
	}
	CloseTable();
	OpenTable();
	echo "<div class=\"content\" style=\"text-align:center\"><strong>[ <span style=\"color:#FF0000;\">"._ATTENZIONE."</span>: <a href=\"".$admin_file.".php?op=multimenudeletefolder&amp;siteid=$siteid&amp;ok=0\" title=\""._DEL_APMENU_IMAGE_UPLOAD_DIRECTORY."\">"._DEL_APMENU_IMAGE_UPLOAD_DIRECTORY."</a> ]</strong></div>";
	echo "<div class=\"content\" style=\"text-align:justify\">"._DEL_APMENU_IMAGE_UPLOAD_DIRECTORY_INFO."</div>";
	CloseTable();
	}
		include("footer.php");
    }
	
function multimenuerr($msg) {
	include('header.php');
    OpenTable();
	echo "<div style=\"text-align:center\"><strong>$msg<br /><br />
	"._INDIETRO."</strong></div>";
	CloseTable();
	include('footer.php');
}
		
function check_image_type($type) {
	switch($type) {
		case 'image/jpeg':
		case 'image/pjpeg':
		case 'image/jpg':
			return '.jpg';
			break;
		case 'image/gif':
			return '.gif';
			break;
		case 'image/x-png':
		case 'image/png':
			return '.png';
			break;
		default:
			return false;
			break;
	}
	return false;
}	
	

//installazione/disinstallazione
function multimenu_install($ok=0) {
    global $prefix, $db, $admin_file, $verifica, $siteid, $multiext;
	if (!$verifica) { Header("Location: ".$admin_file.".php?siteid=$siteid"); }
	if($ok==1) {
      $result = $db->sql_query("CREATE TABLE ".$prefix."_multimenu_cat (`catid` int(11) NOT NULL auto_increment, `catname` varchar(60) NOT NULL default '', `catattiva` int(11) NOT NULL default '1', `catcss` varchar(50) NOT NULL default '', `site_id` int(11) NOT NULL default '1', PRIMARY KEY  (`catid`)) ENGINE=MyISAM;");
	  $resultel = $db->sql_query("CREATE TABLE ".$prefix."_multimenu_el (`elid` int(11) NOT NULL auto_increment, `elcatid` int(11) NOT NULL default '0', `elnome` varchar(60) NOT NULL default '', `elimg` varchar(30) NOT NULL default '', `elurl` varchar(200) NOT NULL default '', `elparent` int(11) NOT NULL default '0', `eltarget` varchar(10) NOT NULL default '', `elattivo` int(11) NOT NULL default '1', `elpeso` int(11) NOT NULL default '1', `elevel` int(11) NOT NULL default '0', `elcustom` varchar(50) NOT NULL,
  `elcss` varchar(50) NOT NULL, `site_id` int(11) NOT NULL default '1', PRIMARY KEY  (`elid`)) ENGINE=MyISAM;");
  
	include("header.php");
	multiadminmenu('multimenu_install');
	OpenTable();
	echo "<h2>Installazione Tabelle Multi menu</h2>";
	echo "<div class=\"content\"><strong>Ecco i risultati dell'installazione:</strong><br /><br />\n";
	  if (!$result) { echo "Installazione Tabella ".$prefix."_multimenu_cat - <span style=\"color:#AA0000;\">Fallita!</span><br />\n"; } else { echo "Installazione Tabella ".$prefix."_multimenu_cat - <span style=\"color:#00AA00;\">Completo!</span><br />\n"; }
      if (!$resultel) { echo "Installazione Tabella ".$prefix."_multimenu_el - <span style=\"color:#AA0000;\">Fallita!</span><br />\n"; } else { echo "Installazione Tabella ".$prefix."_multimenu_el - <span style=\"color:#00AA00;\">Completo!</span><br />\n"; }
	  
	echo is_writable( "blocks/block-Multi_Menu/theme/img" ) ? '<span style="color:#00AA00;">'._APMENUDIRSCRIVIBILE.'</span>' : '<span style="color:#AA0000;">'._APMENUDIRNONSCRIVIBILE.'</span>';
	  
	echo "<br /><hr /><strong>Operazione effettuata, se è tutto OK, <a href=\"".$admin_file.".php?op=multimenuindex\" title=\"Clicca qui per andare in amministrazione di Multi Menu\">clicca qui</a> per andare in amministrazione di Multi Menu.<br /></strong></div>";
	CloseTable();
	include("footer.php");
	} else {
	include("header.php");
	multiadminmenu('multimenu_install');
	OpenTable();
	echo "<h2>Installazione Tabelle Multi Menu</h2>";
	echo "<div class=\"title\" style=\"text-align:center;\"><strong>"._APMENUINSTALL."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\"><strong>"._APMENUINSTALL2."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\"><strong>"._CONTINUA."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\">[ <a href=\"".$admin_file.".php?op=multimenu_install&amp;ok=1\" title=\""._SI."\">"._SI."</a> | <a href=\"".$admin_file.".php?op=multimenuindex\" title=\""._NO."\">"._NO."</a> ]</div>";	
	CloseTable();
	include("footer.php");
    }
}

function multimenu_destall($ok=0) {
    global $prefix, $db, $admin_file, $verifica, $siteid, $multiext;
	if (!$verifica) { Header("Location: ".$admin_file.".php?siteid=$siteid"); }
	include("header.php");
	multiadminmenu('multimenu_install');
	OpenTable();
	echo "<h2>Disinstallazione Tabelle Multi Menu</h2>";
	if($ok==1) {
	echo "<div class=\"content\"><strong>Ecco i risultati della disinstallazione:</strong><br /><br />\n";
	$result = $db->sql_query("DROP TABLE ".$prefix."_multimenu_cat");
	if (!$result) { echo "Disinstallazione Tabella ".$prefix."_multimenu_cat - <span style=\"color:#AA0000;\">Fallita!</span><br />\n"; } else { echo "Disinstallazione Tabella ".$prefix."_multimenu_cat - <span style=\"color:#00AA00;\">Completo!</span><br />\n"; }

	$result = $db->sql_query("DROP TABLE ".$prefix."_multimenu_el");
	if (!$result) { echo "Disinstallazione Tabella ".$prefix."_apsidemenu_el - <span style=\"color:#AA0000;\">Fallita!</span><br />\n"; } else { echo "Disinstallazione Tabella ".$prefix."_multimenu_el - <span style=\"color:#00AA00;\">Completo!</span><br />\n"; }
			
	echo "<br /><hr /><strong>Operazione effettuata, se è tutto OK, <a href=\"".$admin_file.".php\" title=\"Clicca qui per tornare all'amministrazione del sito\">clicca qui</a> per tornare all'amministrazione del sito.</strong></div>";	  
	} else {
	echo "<div class=\"title\" style=\"text-align:center;\"><strong>"._APMENUDESTALL."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\"><strong>"._APMENUDESTALL2."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\"><strong>"._CONTINUA."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\">[ <a href=\"".$admin_file.".php?op=multimenu_destall&amp;ok=1\" title=\""._SI."\">"._SI."</a> | <a href=\"".$admin_file.".php?op=multimenuindex\" title=\""._NO."\">"._NO."</a> ]</div>";	
	}
	CloseTable();
	include("footer.php");
}
	
function multimenuCreateDir() {
global $admin_file, $siteid, $multiext, $catpath;

if (file_exists("".$catpath."")) { apsidemenuerr(""._ERROR_APMENU_DIR_EXISTS.": ".$catpath.""); }
if (@mkdir("".$catpath."", 0777)) {
$contenutofileindice = '';
$fileindice = @fopen("".$catpath."/index.html", "w");
@fwrite($fileindice, $contenutofileindice);
@fclose($fileindice);
include("header.php");
OpenTable();
echo "<div class=\"content\" style=\"text-align:center\"><strong>"._CREATE_APMENU_UPLOAD_DIRECTORY_SUCCESS."</strong><br /><br />".$catpath."<br /><br />[ <a href=\"".$admin_file.".php?op=multimenuimmagini$multiext\" title=\""._BACKTO_GESTIMG."\">"._BACKTO_GESTIMG."</a> ]<br /><br /></div>";
CloseTable();
include("footer.php");
} else { apsidemenuerr(""._ERROR_APMENU_DIR_CREATE.": ".$catpath."<br />"._ERROR_APMENU_DIR_CREATE_CHECK.""); }
}

function multimenudeletefolder($ok=0) {
global $admin_file, $siteid, $multiext, $catpath;
if($ok==1) {
multimenu_delete_folder($catpath);
Header("Location: ".$admin_file.".php?op=multimenuimmagini&siteid=$siteid");
} else { 
	include("header.php");
	multimenu();
	title(""._TITOLOELIMINAAPMENUCARTELLA."");
	OpenTable();
	echo "<div class=\"title\" style=\"text-align:center;\"><strong>"._MSGELIMINAAPMENUCARTELLA."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\"><strong>"._CONTINUA."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\">[ <a href=\"".$admin_file.".php?op=multimenudeletefolder&amp;ok=1$multiext\" title=\""._SI."\">"._SI."</a> | <a href=\"".$admin_file.".php?op=multimenuimmagini$multiext\" title=\""._NO."\">"._NO."</a> ]</div>";	
	CloseTable();
	include("footer.php");
	}
}

function multimenu_delete_folder($tmp_path){
  if(!is_writeable($tmp_path) && is_dir($tmp_path)){chmod($tmp_path,0777);}
    $handle = opendir($tmp_path);
  while($tmp=readdir($handle)){
    if($tmp!='..' && $tmp!='.' && $tmp!=''){
         if(is_writeable($tmp_path."/".$tmp) && is_file($tmp_path."/".$tmp)){
                 unlink($tmp_path."/".$tmp);
         }elseif(!is_writeable($tmp_path."/".$tmp) && is_file($tmp_path."/".$tmp)){
             chmod($tmp_path."/".$tmp,0666);
             unlink($tmp_path."/".$tmp);
         }
        
         if(is_writeable($tmp_path."/".$tmp) && is_dir($tmp_path."/".$tmp)){
                apcoinslider_delete_folder($tmp_path."/".$tmp);
         }elseif(!is_writeable($tmp_path."/".$tmp) && is_dir($tmp_path."/".$tmp)){
                chmod($tmp_path."/".$tmp,0777);
                apcoinslider_delete_folder($tmp_path."/".$tmp);
         }
    }
  }
  closedir($handle);
  rmdir($tmp_path);
  if(!is_dir($tmp_path)){return true;}
  else{return false;}
} 
	
switch($op) {
	default:
    multimenuindex();
    break;
	case "multimenunuovacat":
    multimenunuovacat();
    break;
    case "multimenucatadd":
    multimenucatadd($catname, $catcss, $catattiva, $siteid1);
    break;
    case "multimenucatdel":
    multimenucatdel($catid, $ok);
    break;
    case "multimenucatedit":
    multimenucatedit($catid);
    break; 
	case "multimenucatsave":
	multimenucatsave($catid, $catname, $catattiva, $catcss, $siteid1);
	break;
	case "multimenuseel":
    multimenuseel($catid);
    break;
	case "multimenunuovoel":
    multimenunuovoel($catid);
    break;
    case "multimenueladd":
    multimenueladd($elcatid, $elnome, $elimg, $nblocks, $nmodules, $ntext, $elparent, $eltarget, $elattivo, $elcustom, $elcss);
    break;
	case "modselectparent":
	modselectparent($elcatid, $elparent, $elid);
	break;
	case "multimenueldel":
    multimenueldel($elid, $ok);
    break;
    case "multimenueledit":
    multimenueledit($elid);
    break; 
	case "multimenuelsave":
	multimenuelsave($elid, $elcatid, $elnome, $elimg, $nblocks, $nmodules, $ntext, $elparent, $eltarget, $elattivo, $elcustom, $elcss);
	break;
	case "multimenuimmagini":
    multimenuimmagini();
    break;
	
	case "multimenuuploadimg":
	$uploaddir = 'blocks/block-Multi_Menu/theme/img/site'.$siteid.'/';
	$dimensione_massima=51200; //cambiare per aumentare o diminuire il peso consentito
	$dimensione_massima_Kb=$dimensione_massima/1024;
	if (check_image_type($_FILES['userfile']['type']) == false) multimenuerr(""._APMENUERRORE1."");
	if($_FILES['userfile']['size']>$dimensione_massima) multimenuerr(""._APMENUERRORE4."$dimensione_massima_Kb Kb");
	if (file_exists($uploaddir . $_FILES['userfile']['name'])) multimenuerr(""._APMENUERRORE2."");
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $_FILES['userfile']['name'])) {
   	Header("Location: ".$admin_file.".php?op=multimenuimmagini&siteid=$siteid");
	} else {
	multimenuerr(""._APMENUERRORE3."");
	}
	break;
	
	case "multimenudelimg":
	if($ok==1) {
	unlink("blocks/block-Multi_Menu/theme/img/site".$siteid."/$delmenuimg");
	Header("Location: ".$admin_file.".php?op=multimenuimmagini&siteid=$siteid");
	} else { 
	include("header.php");
	multimenu();
	title(""._TITOLOELIMINAIMG."");
	OpenTable();
	echo "<div class=\"title\" style=\"text-align:center;\"><strong>"._MSGELIMINAIMG."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\">$delmenuimg</div>";
	echo "<div class=\"content\" style=\"text-align:center;\"><img src=\"blocks/block-Multi_Menu/theme/img/site".$siteid."/$delmenuimg\" alt=\"$delmenuimg\" title=\"$delmenuimg\" /></div>";
	echo "<div class=\"content\" style=\"text-align:center;\"><strong>"._CONTINUA."</strong></div>";
	echo "<div class=\"content\" style=\"text-align:center;\">[ <a href=\"".$admin_file.".php?op=multimenudelimg&amp;delmenuimg=$delmenuimg&amp;ok=1$multiext\" title=\""._SI."\">"._SI."</a> | <a href=\"".$admin_file.".php?op=multimenuimmagini$multiext\" title=\""._NO."\">"._NO."</a> ]</div>";	
	CloseTable();
	include("footer.php");
	}
		break;
	
	case "multimenupesoel":
    multimenupesoel($elpesomod, $elpesorig, $elidorig, $elparent, $catid);
    break;
    case "multimenu_install":
    multimenu_install($ok);
    break;
	case "multimenu_destall":
    multimenu_destall($ok);
    break;
	case "multimenuCreateDir":
    multimenuCreateDir();
    break;
	case "multimenudeletefolder":
	multimenudeletefolder($ok);
	break;
  }

} else {
    echo "Access Denied";
}
?>
