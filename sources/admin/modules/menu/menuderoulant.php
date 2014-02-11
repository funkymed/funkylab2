<?

	include "../../config/config.bdd.url.php";
	include "../../config/root.config.php";
	
	$var=array(	
	 	"type_menu"  => array(
	        "nom"  => array("Ajouter un bouton au menu"),
	        "option"  => array("ADDMENU")
	     ),
	 	"type_bouton"  => array(
	        "nom"  => array("Ajouter un element au bouton"),
	        "option"  =>array("ADDMENU")
	     ),
	     "type_submenu"  => array(
	        "nom"  => array("Ajouter element au sous menu"),
	        "option"  => array("ADDMENU")
	     ),
	     "type_contenu"  => array(
	          "nom"  => array("Traduire le titre du paragraphe"),
	          "option"  => array("ADDMENU")
	     ),
	     "type_gallerie"  => array(
	          "nom"  => array("Traduire le titre du paragraphe"),
	          "option"  => array("ADDMENU")
	     ),
	     "type_url"  => array(
	          "nom"  => array("Traduire le titre du paragraphe"),
	          "option"  => array("ADDMENU")
	     )
     );

	$buffer="
	<select name=\"select\" class=\"listselect\" onchange=\"this.form.submit();\">
		<option>Selectionnez une option ou un element";
	     if(isset($_GET['id'])){
		    $id=$_GET['id'];
		    if ($_GET['id']!="undefined"){
				@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter à la base de données");		
				$res=mysql_query("SELECT * FROM menu WHERE id='$id'");
				$row = mysql_fetch_array($res);					
				$varset=$row['type'];		
				$count=count($var[$varset]['nom']);
				for ($XX=0;$XX<$count;$XX++){
					$buffer.="<option value=\"".$var[$varset]['option'][$XX]."\">".$var[$varset]['nom'][$XX];				
				}
				$buffer.="<option value=\"1\">modifier";
			 }else{
				 $buffer.="<option value=\"ADDNEWMENU\">ajouter un menu";
			 }
		 }
	$buffer.="</select>";
	print $buffer;
	
?>