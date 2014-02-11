<? 	session_start();
	include "../config/config.bdd.url.php";
	include "../config/root.config.php";
	/*
	@mysql_select_db(BASE_ADMIN) or die("Impossible de se connecter à la base de données");
	if (isset($_SESSION['funkylab_id'])){
		$skinadmin=recupeall($_SESSION['funkylab_id'],"admin","skinadmin");
	}else{
		$skinadmin="funkylabv2";
	}
	*/
	define ("SKIN", "template/".$skinadmin);	
	$hideMenuList =false;
	//-----------------------------------------------------------------
	// ENTETE
	//-----------------------------------------------------------------	
	$buffer="<html>\n";			
	$buffer.= "\t<head>\r";
	$buffer.= "\t\t<link rel=\"stylesheet\" href=\"../".SKIN."/css/css.css\" type=\"text/css\">\n";
	
	$buffer.= "
	<script language=\"JavaScript\" src=\"../scripts/ThemeOffice/theme.js\"></script>
	<script language=\"JavaScript\" src=\"../scripts/tiny_mce/tiny_mce.js\"></script>";

	$buffer.="
		<script language=\"javascript\" type=\"text/javascript\">
			tinyMCE.init({
				mode : \"textareas\",
				theme : \"simple\"
			});
		</script>
	
	";
/*
	
$buffer.="
		<script language=\"JavaScript\" type=\"text/javascript\">
tinyMCE.init({
		mode : \"textareas\",
		theme : \"advanced\",
		plugins : \"table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,paste,directionality,fullscreen\",
		theme_advanced_buttons1_add_before : \"save,newdocument,separator\",
		theme_advanced_buttons1_add : \"fontselect,fontsizeselect\",
		theme_advanced_buttons2_add : \"separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor\",
		theme_advanced_buttons2_add_before: \"cut,copy,paste,pastetext,pasteword,separator,search,replace,separator\",
		theme_advanced_buttons3_add_before : \"tablecontrols,separator\",
		theme_advanced_buttons3_add : \"emotions,iespell,flash,advhr,separator,print,separator,ltr,rtl,separator,fullscreen\",
		theme_advanced_toolbar_location : \"top\",
		theme_advanced_toolbar_align : \"left\",
		theme_advanced_statusbar_location : \"bottom\",
		content_css : \"example_word.css\",
	    plugi2n_insertdate_dateFormat : \"%Y-%m-%d\",
	    plugi2n_insertdate_timeFormat : \"%H:%M:%S\",
		extended_valid_elements : \"hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\",
		external_link_list_url : \"example_link_list.js\",
		external_image_list_url : \"example_image_list.js\",
		flash_external_list_url : \"example_flash_list.js\",
		file_browser_callback : \"fileBrowserCallBack\",
		paste_use_dialog : false,
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : \"_something=My somthing;_something2=My somthing2;_something3=My somthing3;\",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : true
	});

	function fileBrowserCallBack(field_name, url, type, win) {
		// This is where you insert your custom filebrowser logic
		alert(\"Filebrowser callback: field_name: \" + field_name + \", url: \" + url + \", type: \" + type);

		// Insert new URL, this would normaly be done in a popup
		win.document.forms[0].elements[field_name].value = \"someurl.htm\";
	}
		</script>\n";	
*/
	$buffer.= "\t</head>\n";	
	$buffer.= "<body>\n	";

	//-----------------------------------------------------------------
	// GESTION DES ISSETS
	//-----------------------------------------------------------------
	$buffer_textarea="";
	switch(true){		
		case (isset($_GET['add'])):
			$hideMenuList=true;
			$buffer.="<a href=\"helpmaker.php\">Retour</a><br/>";
			$buffer.= "<table class=\"loginbox\" border=\"0\" align=\"center\">\n<tr>\n	<td valign=\"top\" align=\"left\">\n<ul>\n\n";
			$id=$_GET['add'];
			$titre="";
			$page="";	
			$menuList=menuListCategorie($id);			
			$buffer.="
			<form action=\"helpmaker.php\" method=\"GET\">
				<p align=\"left\">Titre <input name=\"titre\" type=\"text\" value=\"$titre\" size=\"80\"><br/>
				Categorie $menuList</p>
				<textarea cols=\"90\" rows=\"30\"name=\"page\" >$page</textarea><br/>
				<input class=\"loginbox\" type=\"submit\" value=\"MODIFIER\">
				<input type=\"hidden\" name=\"valideajout\" value=\"$id\">
			</form>			
			</td>\n</tr>\n</table>\n\n";							
			break;
		//-----------------------------------------------------------------
		// VALIDE MODIF
		//-----------------------------------------------------------------
		case (isset($_GET['valideajout'])):
			$hideMenuList=false;
			$parent=$_GET['parent'];
			$titre=addslashes($_GET['titre']);
			$page=addslashes($_GET['page']);
			
			$query="INSERT INTO help (id,titre,page,parent) VALUES ('','$titre','$page','$parent')";	
			$resultat=@mysql_query($query);
			if ($resultat=="1"){ 
				echo "<P>c bon</P>"; 	
			}else{ 
				echo mysql_error()."<BR><BR>";
				echo $query;			
				echo "<P>ERREUR</P>"; 
			}
			
			break;
			
		//-----------------------------------------------------------------
		// MODIF
		//-----------------------------------------------------------------
		case (isset($_GET['modif'])):
		
		
			$hideMenuList=true;
			$buffer.="<a href=\"helpmaker.php\">Retour</a><br/>";
			$buffer.= "<table class=\"loginbox\" border=\"0\" align=\"center\">\n<tr>\n	<td valign=\"top\" align=\"center\">\n<ul>\n\n";
			
			$id=$_GET['modif'];			
			$res_modiftype=mysql_query("SELECT * FROM help WHERE id=$id"); 
			$row = mysql_fetch_array($res_modiftype);
			$titre=stripslashes($row['titre']);
			$page=stripslashes($row['page']);			
			$parent=$row['parent'];
			$menuList=menuListCategorie($parent);			
			$buffer.="
			<form action=\"helpmaker.php\" method=\"GET\">
				<p align=\"left\">Titre <input name=\"titre\" type=\"text\" value=\"$titre\" size=\"80\"><br/>
				Categorie $menuList</p>
				<textarea cols=\"90\" rows=\"30\"name=\"page\" >$page</textarea><br/>
				<input class=\"loginbox\" type=\"submit\" value=\"MODIFIER\">
				<input type=\"hidden\" name=\"validemodif\" value=\"$id\">
			</form>			
			";		
			
			break;
		//-----------------------------------------------------------------
		// VALIDE MODIF
		//-----------------------------------------------------------------
		case (isset($_GET['validemodif'])):
			$hideMenuList=false;
			$id=$_GET['validemodif'];
			$titre=addslashes($_GET['titre']);
			$page=addslashes($_GET['page']);
			$parent=$_GET['parent'];
			$query="UPDATE help SET titre='$titre',page='$page',parent='$parent' WHERE id='$id'";
			$resultat=@mysql_query($query);
			if ($resultat=="1"){ 
				echo "<P>c bon</P>"; 	
			}else{ 
				echo mysql_error()."<BR><BR>";
				echo $query;			
				echo "<P>ERREUR</P>"; 
			}
			break;
			
		case (isset($_GET['delete'])):
			$hideMenuList=false;
			$id=$_GET['delete'];
			$res=mysql_query("DELETE FROM help WHERE id=$id");								
			break;
	}
		
	//-----------------------------------------------------------------
	// AFFICHAGE DU SOMMAIRE DE L AIDE
	//-----------------------------------------------------------------
	if ($hideMenuList==false){
		$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=0 ORDER BY titre"); 
		$buffer.= "<table class=\"loginbox\" border=\"0\" align=\"center\" width=\"80%\">\n<tr>\n	<td valign=\"top\" align=\"left\">\n<ul>\n\n";
		$buffer.="<a href='imprimeaide.php' target='_blank'>DOC COMPLETE</a><br/>\n<br/>\n";
		$buffer.="<a href=\"?add=0\">AJOUTER NOUVELLE CATEGORIE</a><br/><br/>";
	    while ($row = mysql_fetch_array($res_modiftype)){  
		    $id= $row['id'];   
		    $titrehelp = "<font style=\"font-size:15;\">".stripslashes($row['titre'])."</font>";
		    
		    $buffer.= "<a href=\"?modif=$id\">MODIFIER</a> - <a href=\"?delete=$id\">EFFACER</a> <b>$titrehelp</b> <a href=\"?add=$id\">AJOUTER</a>\n";
		    $buffer.= "<ul>\n";
	    	$buffer.=childtype($id);
	    	$buffer.= "</ul>\n\n";
		}	
		
		$buffer.= "</ul>\n\n</td>\n</tr>\n</table>\r";
		
	}
	
	//-----------------------------------------------------------------
			
	$buffer.= "</body>";
	$buffer.= "</html>";
		
	print $buffer;
	
	//-----------------------------------------------------------------
	// MENULIST DES CATEGORIES
	//-----------------------------------------------------------------	
	
	function menuListCategorie($parent=0){
		$bufferlist="<select name=\"parent\">";
		$bufferlist.="<option value=\"0\">aucune";
		
		$res_modiftype=mysql_query("SELECT * FROM help ORDER BY titre"); 
		while ($row_child = mysql_fetch_array($res_modiftype)){   
			$id= $row_child['id'];   
			$titre = stripslashes($row_child['titre']); 
			
			if ($parent==$id){
				$bufferlist.="<option value=\"$id\" SELECTED=SELECTED>$titre";
			}else{			
				$bufferlist.="<option value=\"$id\">$titre";
			}
		}
		$bufferlist.="</select>";
		return($bufferlist);
	}
	
	//-----------------------------------------------------------------
	// FONCTION ENFANT DU SOMMAIRE
	//-----------------------------------------------------------------	
	function childtype($id){
		$txt="";
		$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=$id ORDER BY titre"); 
		while ($row_child = mysql_fetch_array($res_modiftype)){   
			$id= $row_child['id'];   
			$titrehelp = stripslashes($row_child['titre']);  		
			$pagehelp = trim(stripslashes($row_child['page']));		
			$parent= $row_child['parent'];  
			
			if ($pagehelp==""){
			    $txt.= "<a href=\"?modif=$id\">MODIFIER</a> - <a href=\"?delete=$id\">EFFACER</a> $titrehelp <a href=\"?add=$id\">AJOUTER</a><br/>\n";
			    $txt.= "<ul>\n";
		    	$txt.=childtype($id);
		    	$txt.= "</ul>\n\n";
			}else{	   
				$txt.= "<li><a href=\"?modif=$id\">MODIFIER</a> - <a href=\"?delete=$id\">EFFACER</a> $titrehelp <a href=\"?add=$id\">AJOUTER</a><br/>\n";
			}
		}
		return($txt);
	}
	
	
?>