<?
	/*********************************************
						billet
	*********************************************/
	if (isset($_GET['ADDWORK'])){	
		
		switch($_GET['ADDWORK']){
			/************ CATEGORIE ***************/
			case "ADDCAT":
				$nom=addslashes($_GET['titre']);
				$option=$_GET['sensaffichage'].",".$_GET['formatpage'].",".$_GET['nbparpage'];
				$parent=$_GET['listcategorie'];
				$overrideskin=$_GET['skinsite'];
				addnewbillet($nom,$parent,$overrideskin,"categorie",$option,"","","","","ADD");
				break;
			case "MODIFCAT":
				$nom=addslashes($_GET['titre']);
				$option=$_GET['sensaffichage'].",".$_GET['formatpage'].",".$_GET['nbparpage'];
				if ($_GET['modifID']!=$_GET['listcategorie']){
					$parent=$_GET['listcategorie'];
				}else{
					$parent=0;
				}
				$overrideskin=$_GET['skinsite'];
				addnewbillet($nom,$parent,$overrideskin,"categorie",$option,"","","","",$_GET['modifID']);
				$_GET['listcategorie']=$_GET['modifID'];
				break;
			/************ TRADUCTION TITRE CATEGORIE ***************/
			case "ADDTRADCAT":
				$nom=addslashes($_GET['titre']);
				$option=$_GET['new_langue'];
				$parent=$_GET['listcategorie'];
				addnewbillet($nom,$parent,"","traduction_categorie",$option,"","","","","ADD");
				break;
				
			case "MODIFTRADCAT":
				$nom=addslashes($_GET['titre']);
				$option=$_GET['new_langue'];
				$parent=$_GET['listcategorie'];
				addnewbillet($nom,$parent,"","traduction_categorie",$option,"","","","",$_GET['modifID']);
				break;
				
			/************ PAGE ***************/
			case "ADDPAGE":
				$debut=date("Y-m-d");
				$nom=addslashes($_GET['titre']);				
				
				if (isset($_GET['commentaire'])){
					$option="actif";
				}else{
					$option="inactif";
				}
				
				if (isset($_GET['option_signature'])){
					$option.=",1";
				}else{
					$option.=",0";
				}
				
				if (isset($_GET['option_titre'])){
					$option.=",1";
				}else{
					$option.=",0";
				}
				
				if (isset($_GET['option_cadre'])){
					$option.=",1";
				}else{
					$option.=",0";
				}
				
				$zone=$_GET['formatpage'];
				$parent=$_GET['listcategorie'];	
				addnewbillet($nom,$parent,"","page",$option,$zone,$debut,"","","ADD");	
				break;
			case "MODIFPAGE":
				$nom=addslashes($_GET['titre']);
				
				if (isset($_GET['commentaire'])){
					$option="actif";
				}else{
					$option="inactif";
				}
				
				if (isset($_GET['option_signature'])){
					$option.=",1";
				}else{
					$option.=",0";
				}
				
				if (isset($_GET['option_titre'])){
					$option.=",1";
				}else{
					$option.=",0";
				}
				
				if (isset($_GET['option_cadre'])){
					$option.=",1";
				}else{
					$option.=",0";
				}
				
				$parent=$_GET['listcategorie'];	
				$zone=$_GET['formatpage'];				
				$overrideskin=$_GET['skinsite'];	
				
				$query="SELECT date_debut FROM billet WHERE id='".$_GET['modifID']."'";
				$resCOM=mysql_query($query);	
				$rowCOM = mysql_fetch_array($resCOM);
				$debut=$rowCOM['date_debut'];			
				
				addnewbillet($nom,$parent,$overrideskin,"page",$option,$zone,$debut,"","",$_GET['modifID']);
				break;		
			/************ TRADUCTION TITRE PAGE ***************/
			case "ADDTRADPAGE":
				$nom=addslashes($_GET['titre']);
				$option=$_GET['new_langue'];
				$parent=$_GET['CATID'];
				addnewbillet($nom,$parent,"","traduction_page",$option,"","","","","ADD");
				break;
				
			case "MODIFTRADPAGE":
				$nom=addslashes($_GET['titre']);
				$option=$_GET['new_langue'];
				$parent=$_GET['CATID'];
				addnewbillet($nom,$parent,"","traduction_page",$option,"","","","",$_GET['modifID']);
				break;

			/************ VIDEO ***************/
			case "ADDVIDEO":			
				$file=$_GET['minibrowserlist'];
				if ($file!=""){
					$parent=$_GET['CATID'];
					$zone=returnzone();
					if(isset($_GET['downloadable'])){
						$option="download";
					}else{
						$option="";
					}
					$nom=explode("/",$file);
					$nom=$nom[count($nom)-1];
					addnewbillet($nom,$parent,"","video",$option,$zone,"","",$file,"ADD");	
				}
				break;	
			case "MODIFVIDEO":
				$file=$_GET['minibrowserlist'];
				if ($file!=""){
					$parent=$_GET['CATID'];
					$zone=returnzone();
					if(isset($_GET['downloadable'])){
						$option="download";
					}else{
						$option="";
					}
					$nom=explode("/",$file);
					$nom=$nom[count($nom)-1];
					
					addnewbillet($nom,$parent,"","video",$option,$zone,"","",$file,$_GET['modifID']);
				}
				break;				
			/************ AUDIO ***************/
		
			case "ADDAUDIO":							
				$file=$_GET['minibrowserlist'];
				if ($file!=""){
					$nom=addslashes($_GET['nom']);
					$parent=$_GET['CATID'];
					$zone=returnzone();
					
					if (isset($_GET['download'])){
						$option="1";
					}else{
						$option="0";
					}
					
					addnewbillet($nom,$parent,"","audio",$option,$zone,"","",$file,"ADD");	
				}
				break;	
			case "MODIFAUDIO":
				$file=$_GET['minibrowserlist'];
				if ($file!=""){
					$nom=addslashes($_GET['nom']);
					$parent=$_GET['CATID'];
					$zone=returnzone();
					
					if (isset($_GET['download'])){
						$option="1";
					}else{
						$option="0";
					}
					
					addnewbillet($nom,$parent,"","audio",$option,$zone,"","",$file,$_GET['modifID']);
				}
				break;
				
		/************ IMAGE ***************/
			case "ADDIMAGEPAGE":			
				$file=$_GET['minibrowserlist'];
				if ($file!=""){
					$parent=$_GET['CATID'];
					$zone=returnzone();
					if (isset($_GET['zoom'])){
						$zoom=$_GET['zoom'];
					}else{
						$zoom="null";
					}
					$taille=$_GET['taille'];
					$option=$taille."/".$zoom;
					$nom=explode("/",$file);
					$nom=$nom[count($nom)-1];
					addnewbillet($nom,$parent,"","imagepage",$option,$zone,"","",$file,"ADD");	
				}
				break;	
			case "MODIFIMAGEPAGE":
				$file=$_GET['minibrowserlist'];
				if ($file!=""){
					$parent=$_GET['CATID'];
					$zone=returnzone();
					if (isset($_GET['zoom'])){
						$zoom=$_GET['zoom'];
					}else{
						$zoom="null";
					}
					$taille=$_GET['taille'];
					$option=$taille."/".$zoom;
					$nom=explode("/",$file);
					$nom=$nom[count($nom)-1];
					
					addnewbillet($nom,$parent,"","imagepage",$option,$zone,"","",$file,$_GET['modifID']);
				}
				break;	
							
			/************ DATE ***************/
			case "ADDDATE":
				switch($_GET['datemode']){
					case "start":
						$debut=dateslash($_GET['debut_start']);
						$fin="";
						break;
						
					case "limite":					
						$debut=dateslash($_GET['debut_limite']);
						$fin=dateslash($_GET['fin_limite']);
						break;
											
					default:
						$debut=date("Y-m-d");
						$fin="";
						break;
				}		
				$parent=$_GET['CATID'];
				addnewbillet("",$parent,"","date",$_GET['datemode'],"0",$debut,$fin,"","ADD");	
				break;	
			case "MODIFDATE":
				switch($_GET['datemode']){
					case "start":
						$debut=dateslash($_GET['debut_start']);
						$fin="";
						break;
						
					case "limite":					
						$debut=dateslash($_GET['debut_limite']);
						$fin=dateslash($_GET['fin_limite']);
						break;
											
					default:
						$debut=date("Y-m-d");
						$fin="";
						break;
				}		
				$parent=$_GET['CATID'];
				addnewbillet("",$parent,"","date",$_GET['datemode'],"0",$debut,$fin,"",$_GET['modifID']);
				break;	
			/************ RSS ***************/
			case "ADDRSS":
				$nom=addslashes($_GET['fluxrss']);
				$parent=$_GET['CATID'];
				$nbrss=$_GET['nbrss'];
				$option=$nbrss.",".$_GET['rssoption'];
				$zone=returnzone();
				addnewbillet($nom,$parent,"","rss",$option,$zone,"","","","ADD");	
				break;	
			case "MODIFRSS":
				$nom=addslashes($_GET['fluxrss']);
				$parent=$_GET['CATID'];
				$nbrss=$_GET['nbrss'];
				$option=$nbrss.",".$_GET['rssoption'];
				$zone=returnzone();
				addnewbillet($nom,$parent,"","rss",$option,$zone,"","","",$_GET['modifID']);
				break;	
			/************ PARAGRAPHE ***************/
			case "ADDTEXTE":
				$texte=addslashes($_GET['texte']);
				$option=$_GET['alignement'];
				$zone=returnzone();
				$parent=$_GET['CATID'];
				addnewbillet("texte",$parent,"","texte",$option,$zone,"","",$texte,"ADD");	
				break;							
			case "MODIFTEXTE":
				$texte=addslashes($_GET['texte']);
				$option=$_GET['alignement'];
				$zone=returnzone();
				$parent=$_GET['CATID'];
				addnewbillet("texte",$parent,"","texte",$option,$zone,"","",$texte,$_GET['modifID']);
				break;	
			/************ TRADUCTION PARAGRAPHE ***************/
			case "ADDTRADTEXTE":
				$texte=addslashes($_GET['texte']);
				$option=$_GET['new_langue'];		
				$parent=$_GET['CATID'];
				addnewbillet("traduction texte",$parent,"","traduction_texte",$option,"","","",$texte,"ADD");	
				break;							
			case "MODIFTRADTEXTE":
				$texte=addslashes($_GET['texte']);
				$option=$_GET['new_langue'];		
				$parent=$_GET['CATID'];
				addnewbillet("traduction texte",$parent,"","traduction_texte",$option,"","","",$texte,$_GET['modifID']);
				break;	

			/************ HTML ***************/
			case "ADDHTML":
				$html=addslashes($_GET['html']);
				$zone=returnzone();
				$parent=$_GET['CATID'];
				addnewbillet("html",$parent,"","html","",$zone,"","",$html,"ADD");	
				break;							
			case "MODIFHTML":
				$html=addslashes($_GET['html']);
				$zone=returnzone();
				$parent=$_GET['CATID'];
				addnewbillet("html",$parent,"","html","",$zone,"","",$html,$_GET['modifID']);
				break;			
			/************ URL ***************/
			case "ADDURL":
				$nom=addslashes($_GET['nomdulien']);
				$option=$_GET['ouvrirlien'];
				$zone=returnzone();
				$parent=$_GET['CATID'];
				$texte=$_GET['lien'];
				addnewbillet($nom,$parent,"","link",$option,$zone,"","",$texte,"ADD");	
				break;	
			case "MODIFURL":
				$nom=addslashes($_GET['nomdulien']);
				$option=$_GET['ouvrirlien'];
				$zone=returnzone();
				$parent=$_GET['CATID'];
				$texte=$_GET['lien'];
				addnewbillet($nom,$parent,"","link",$option,$zone,"","",$texte,$_GET['modifID']);
				break;		
				
			/************ FILE ***************/
			case "ADDFILE":
				$nom=addslashes($_GET['nomfichier']);
				$file=$_GET['minibrowserlist'];
				$zone=returnzone();
				$parent=$_GET['CATID'];
				addnewbillet($nom,$parent,"","file","",$zone,"","",$file,"ADD");	
				break;	
			case "MODIFFILE":
				$nom=addslashes($_GET['nomfichier']);
				$file=$_GET['minibrowserlist'];
				$zone=returnzone();
				$parent=$_GET['CATID'];
				addnewbillet($nom,$parent,"","file","",$zone,"","",$file,$_GET['modifID']);
				break;
				
			/************ TITRE ***************/
			case "ADDTITRE":
				$nom=addslashes($_GET['titre']);
				$parent=$_GET['CATID'];
				addnewbillet($nom,$parent,"","titre","","0","","","","ADD");	
				break;	
			case "MODIFTITRE":
				$nom=addslashes($_GET['titre']);
				$parent=$_GET['CATID'];
				addnewbillet($nom,$parent,"","titre","","0","","","",$_GET['modifID']);
				break;	
			/************ TRADUCTION TITRE ***************/
			case "ADDTRADTITRE":
				$nom=addslashes($_GET['titre']);
				$option=$_GET['new_langue'];
				$parent=$_GET['CATID'];
				addnewbillet($nom,$parent,"","traduction_titre",$option,"0","","","","ADD");	
				break;	
			case "MODIFTRADTITRE":
				$nom=addslashes($_GET['titre']);
				$option=$_GET['new_langue'];
				$parent=$_GET['CATID'];
				addnewbillet($nom,$parent,"","traduction_titre",$option,"0","","","",$_GET['modifID']);
				break;	

			/************ IMAGE ***************/
			case "ADDIMAGE":
				$parent=$_GET['CATID'];
				$option=$_GET['alignement'];
				$file=$_GET['minibrowserlist'];
				$nom=explode("/",$file);
				$nom=$nom[count($nom)-1];
				
				$taille=$_GET['taille'];
				
				if (isset($_GET['zoom'])){
					$zoom=$_GET['zoom'];
				}else{
					$zoom="null";
				}
				
				$zone=$taille."/".$zoom;
				
				addnewbillet($nom,$parent,"","image",$option,$zone,"","",$file,"ADD");	
				break;			
			case "MODIFIMAGE":
				$parent=$_GET['CATID'];
				$option=$_GET['alignement'];
				$file=$_GET['minibrowserlist'];
				$nom=explode("/",$file);
				$nom=$nom[count($nom)-1];
				
				$taille=$_GET['taille'];
				
				if (isset($_GET['zoom'])){			
					$zoom=$_GET['zoom'];
				}else{
					$zoom="null";
				}
				
				$zone=$taille."/".$zoom;
								
				addnewbillet($nom,$parent,"","image",$option,$zone,"","",$file,$_GET['modifID']);
				break;	
				
			/************ GALLERIE ***************/
			case "ADDGAL":
				$parent=$_GET['CATID'];
				$zone=returnzone();
				$texte=$_GET['listgalerie'];
				$nom=recupenomtype($texte,"type_photo");
				
				if (isset($_GET['nbparlignerandom'])){
					$nb=$_GET['nbparlignerandom'];
				}
				if (isset($_GET['nbparlignegalerie'])){
					$nb=$_GET['nbparlignegalerie'];
				}			
					
				$option=$nb.",".$_GET['option'].",".$_GET['affichage'];
				
				addnewbillet($nom,$parent,"","gallerie",$option,$zone,"","",$texte,"ADD");	
				break;					
			case "MODIFGAL":
				$parent=$_GET['CATID'];
				$zone=returnzone();
				$texte=$_GET['listgalerie'];
				$nom=recupenomtype($texte,"type_photo");
				
				if ($_GET['option']=="random"){
					$nb=$_GET['nbparlignerandom'];
				}
				if ($_GET['option']=="galerie"){
					$nb=$_GET['nbparlignegalerie'];
				}			
				
				$option=$nb.",".$_GET['option'].",".$_GET['affichage'];
				addnewbillet($nom,$parent,"","gallerie",$option,$zone,"","",$texte,$_GET['modifID']);
				break;				
		}
	}
	
	
	function addnewbillet($nom,$parent,$overrideskin,$type,$option,$zone,$date_debut,$date_fin,$texte,$addmodif){
		$auteur=$_SESSION["funkylab_id"];
		
		if ($addmodif=="ADD"){
			$query="INSERT INTO billet (nom,parent,overrideskin,type,option_element,zone,date_debut,date_fin,texte,auteur) VALUES 
			('$nom','$parent','$overrideskin','$type','$option','$zone','$date_debut','$date_fin','$texte','$auteur')";	
		}else{
			$query="UPDATE billet SET nom='$nom',parent='$parent',overrideskin='$overrideskin',type='$type',option_element='$option',zone='$zone',date_debut='$date_debut',date_fin='$date_fin',texte='$texte',auteur='$auteur' WHERE id='$addmodif'";			
		}		
		
		$resultat=@mysql_query($query);		
		/*
		echo $query;	
		
		if ($resultat=="1"){ 
			echo "<P>c bon</P>"; 	
		}else{ 
			echo mysql_error()."<BR><BR>";
			echo "<P>ERREUR</P>"; 
		}		
		*/
	}	
		
	
	if (isset($_GET['optimisesql'])){
		optimisebase($_GET['optimisesql']);
	}
			
	if (isset($_GET['hideset'])){
		$id=$_GET['hidepost'];
		$hideset=$_GET['hideset'];
		if ($hideset==1){$hideset=0;}else{$hideset=1;}
		$query="UPDATE billet SET visible='$hideset' WHERE id='$id'";
		$result=mysql_query($query);	
	}	
	
	if (isset($_GET['premierepage'])){	
		$id=$_GET['hidepost'];
		$premierepage=$_GET['premierepage'];
		if ($premierepage==1){	$premierepage=0;	}else{	$premierepage=1;	}
		
		$resZERO=mysql_query("SELECT * FROM billet WHERE premierepage='1'");		
		while ($rowZERO = mysql_fetch_array($resZERO)){ 						
			$idZERO=$rowZERO['id'];
			$result=mysql_query("UPDATE billet SET premierepage='0' WHERE id='$idZERO'");			
		}
		
		$query="UPDATE billet SET premierepage='$premierepage' WHERE id='$id'";
		$result=mysql_query($query);
	}
		
	if (isset($_GET['ordrenew'])){
		$id=$_GET['hidepost'];
		$ordrenew=$_GET['ordrenew'];
		$query="UPDATE billet SET ordre='$ordrenew' WHERE id='$id'";
		$result=mysql_query($query);	
	}

	
	$autorisation=$_SESSION["funkylab_autorisation"];
	if (($autorisation[1]=="1")||($autorisation[6]=="1")){
		if ($autorisation[6]=="1"){
			$_GET['type']=recupeall($_SESSION["funkylab_id"],"admin","optionadmin");
			//$_GET['type']=10;
		}
		if (isset($_GET['list'])){
			billet::billetlist(0,"TOUT","titre",null);
		}			
	}else{
		echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire ça.</P>";
	}
	
	
	function returnzone(){		
		if (isset($_GET['zone'])){
			$zone=$_GET['zone'];
		}else{
			$zone=1;
		}
		return($zone);
	}
?>
