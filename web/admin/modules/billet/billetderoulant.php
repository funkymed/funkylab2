<?php

	include "../../config/config.bdd.url.php";
	include "../../config/root.config.php";
	
	$var=array(	
	 	"categorie"  => array(
	        "nom"  => array("ajouter une categorie","ajouter une page","Traduire le titre de la categorie"),
	        "option"  => array("ADDCAT","ADDPAGE","ADDTRADCAT")
	     ),
	 	"page"  => array(
	        "nom"  => array("Flux RSS","Image (JPG/GIF/PNG)","Galerie","Video","Audio","Date","HTML","Url (Lien Internet)","Fichier a telecharger","Paragraphe","Traduire le titre de la page"),
	        "option"  =>array("ADDRSS","ADDIMAGEPAGE","ADDGAL","ADDVIDEO","ADDAUDIO","ADDDATE","ADDHTML","ADDURL","ADDFILE","ADDTEXTE","ADDTRADPAGE")
	     ),
	     "texte"  => array(
	        "nom"  => array("ajouter un titre","ajouter une image","Traduire le paragraphe"),
	        "option"  => array("ADDTITRE","ADDIMAGE","ADDTRADTEXTE")
	     ),
	     "titre"  => array(
	          "nom"  => array("Traduire le titre du paragraphe"),
	          "option"  => array("ADDTRADTITRE")
	     )

     );

	$buffer="
	<select name=\"select\" class=\"listselect\" onchange=\"this.form.submit();\">
		<option>Selectionnez une option ou un billet";
	     if(isset($_GET['id'])){
		     
		    $id=$_GET['id'];
		    if ($_GET['id']!="undefined"){
				@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");		
				$res=mysql_query("SELECT * FROM billet WHERE id='$id'");
				$row = mysql_fetch_array($res);			
				
				switch($row['type']){
					case "categorie":
						$menulist=true;
						break;
					case "rss":
						$menulist=true;
						break;						
					case "page":
						$menulist=true;
						break;
					case "texte":
						$menulist=true;
						break;
					case "titre":
						$menulist=true;
						break;
					default:
						$menulist=false;
						break;
				}
			
				if ($menulist==true){
					$varset=$row['type'];		
					$count=count($var[$varset]['nom']);
					for ($XX=0;$XX<$count;$XX++){
						$buffer.="<option value=\"".$var[$varset]['option'][$XX]."\">".$var[$varset]['nom'][$XX];				
					}
				}    
				$buffer.="<option value=\"1\">modifier
				<!-- <option value=\"2\">effacer-->";

			 }else{
				 $buffer.="<option value=\"ADDCAT\">ajouter une categorie";
			 }
		 }
	$buffer.="</select>";
	print $buffer;
	
?>