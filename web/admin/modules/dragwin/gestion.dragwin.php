<?php
		if (isset($_SESSION["funkylab_login"])){
			if (isset($_GET['select'])){
				echo "<script type=\"text/javascript\" src=\"modules/dragwin/wz_dragdrop.js\"></script>";
				include "modules/dragwin/inc.fenetrecreate.php";			
				define("TEMPLATE_WIN",SKIN."/dragwin/template.php");	
				
				switch($_GET['select']){
					
//==================================================================================================
//	MODIFIER
//==================================================================================================
										
					case 1:
	//==================================================================================================
	//	COMMENTAIRE
	//==================================================================================================
	
						if ($_GET['cat']=="242"){							
							if (isset($_GET['id'])){
								$id=$_GET['id'];								
								print create_windows_content("MODIFIER UN COMMENTAIRE","ADDCOMMENT",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un commentaire � modifier</P>";
							}
						}
											
	//==================================================================================================
	//	BLACKLIST
	//==================================================================================================
	
						if ($_GET['cat']=="241"){							
							if (isset($_GET['id'])){
								$id=$_GET['id'];								
								print create_windows_content("MODIFIER UN MOT DE LA BLACKLIST","ADDBLACKLIST",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un mot � modifier</P>";
							}
						}
					
	//==================================================================================================
	//	MODIFIER UN COMPTE UTILISATEUR
	//==================================================================================================
					
						if ($_GET['cat']=="255"){
							if (isset($_GET['id'])){
								
								$id=$_GET['id'];
								if ($id==$_SESSION["funkylab_id"]){
									$title="MODIFIER VOTRE COMPTE UTILISATEUR";
								}else{
									$title="MODIFIER UN UTILISATEUR";
								}
								
								print create_windows_content($title,"ADDUSER",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un compte utilisateur � modifier</P>";
							}
						}
	//==================================================================================================
	//	MODIFIER UNE GALERIE
	//==================================================================================================
											
						if ($_GET['cat']=="244"){
							if (isset($_GET['id'])){
								$id=$_GET['id'];
								print create_windows_content("MODIFIER UN ELEMENT DE GALERIE","ADDELEMENTGAL",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un �lement � modifier</P>";
							}
						}
	//==================================================================================================
	//	MODIFIER UN TYPE DE GALERIE
	//==================================================================================================
					
						if ($_GET['cat']=="245"){
							if (isset($_GET['id'])){
								$id=$_GET['id'];
								print create_windows_content("MODIFIER UNE GALERIE","ADDTYPEGAL",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner une galerie � modifier</P>";
							}
						}
	//==================================================================================================
	//	MODIFIER UN MENU
	//==================================================================================================
												
						if ($_GET['cat']=="247"){
							if (isset($_GET['id'])){
								$id=$_GET['id'];
								$resmenu=mysql_query("SELECT * FROM menu WHERE id=$id");
								$rowmenu = mysql_fetch_array($resmenu);
								if ($rowmenu['type']=="type_menu"){
									print create_windows_content("MODIFIER UN MENU","ADDNEWMENU",TEMPLATE_WIN,$id,null);
								}else{							
									print create_windows_content("MODIFIER UN ELEMENT DE MENU","ADDMENU",TEMPLATE_WIN,$id,null);
								}
								
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un menu � modifier</P>";
							}
						}
						
	//==================================================================================================
	//	MODIFIER UN BILLET
	//==================================================================================================
					
						if ($_GET['cat']=="243"){
							if (isset($_GET['id'])){
								$id=$_GET['id'];
								$restypepage=mysql_query("SELECT * FROM billet WHERE id=$id");
								$rowtypepage = mysql_fetch_array($restypepage);			
								$type = $rowtypepage['type'];
								
								switch($type){
									case "traduction_categorie":								
										print create_windows_content("MODIFIER UNE TRADUCTION DU TITRE DE LA CATEGORIE","ADDTRADCAT",TEMPLATE_WIN,$id,null);	
										break;
									case "traduction_page":								
										print create_windows_content("MODIFIER UNE TRADUCTION DU TITRE DE LA PAGE","ADDTRADPAGE",TEMPLATE_WIN,$id,null);	
										break;
									case "traduction_texte":								
										print create_windows_content("MODIFIER UNE TRADUCTION DE PARAGRAPHE","ADDTRADTEXTE",TEMPLATE_WIN,$id,null);	
										break;
									case "traduction_titre":								
										print create_windows_content("MODIFIER UNE TRADUCTION DE TITRE DE PARAGRAPHE","ADDTRADTITRE",TEMPLATE_WIN,$id,null);	
										break;	
									case "categorie":								
										print create_windows_content("MODIFIER UNE CATEGORIE","ADDCAT",TEMPLATE_WIN,$id,null);	
										break;
									case "page":								
										print create_windows_content("MODIFIER UNE PAGE","ADDPAGE",TEMPLATE_WIN,$id,null);	
										break;
									case "video":								
										print create_windows_content("MODIFIER UNE VIDEO","ADDVIDEO",TEMPLATE_WIN,$id,null);	
										break;
									case "audio":								
										print create_windows_content("MODIFIER UN FICHIER AUDIO","ADDAUDIO",TEMPLATE_WIN,$id,null);	
										break;
									case "imagepage":								
										print create_windows_content("MODIFIER UNE IMAGE","ADDIMAGEPAGE",TEMPLATE_WIN,$id,null);	
										break;	
									case "date":								
										print create_windows_content("MODIFIER UNE DATE","ADDDATE",TEMPLATE_WIN,$id,null);	
										break;		
									case "texte":								
										print create_windows_content("MODIFIER UN PARAGRAPHE","ADDTEXTE",TEMPLATE_WIN,$id,null);	
										break;	
									case "html":								
										print create_windows_content("MODIFIER DU CODE HTML","ADDHTML",TEMPLATE_WIN,$id,null);	
										break;														
									case "link":								
										print create_windows_content("MODIFIER UN LIEN","ADDURL",TEMPLATE_WIN,$id,null);	
										break;															
									case "file":								
										print create_windows_content("MODIFIER UN FICHIER","ADDFILE",TEMPLATE_WIN,$id,null);	
										break;															
									case "titre":								
										print create_windows_content("MODIFIER UN TITRE DE PARAGRAPHE","ADDTITRE",TEMPLATE_WIN,$id,null);	
										break;										
									case "image":								
										print create_windows_content("MODIFIER UNE IMAGE DE PARAGRAPHE","ADDIMAGE",TEMPLATE_WIN,$id,null);	
										break;										
									case "gallerie":								
										print create_windows_content("MODIFIER UNE GALERIE","ADDGAL",TEMPLATE_WIN,$id,null);	
										break;
									case "rss":
										print create_windows_content("MODIFIER UN FLUX RSS","ADDRSS",TEMPLATE_WIN,$id,null);	
										break;										
								}
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un billet � modifier</P>";
							}
						}
						
						
	//==================================================================================================
	//	MODIFIER UN BANDEAU DE PUB
	//==================================================================================================
							
						if ($_GET['cat']=="256"){
							
							if (isset($_GET['id'])){
								$id=$_GET['id'];
								print create_windows_content("MODIFIER UNE BANNIERE DE PUB","ADDPUB",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un bandeau de pub</P>";
							}
						}	
										
	//==================================================================================================
	//	MODIFIER UN EMAIL
	//==================================================================================================
							
						if ($_GET['cat']=="252"){
							
							if (isset($_GET['id'])){
								$id=$_GET['id'];
								print create_windows_content("MODIFIER UN EMAIL","ADDEMAIL",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un email</P>";
							}
						}			
						
	//==================================================================================================
	//	LIRE UN MESSAGE
	//==================================================================================================
							
						if ($_GET['cat']=="253"){
							if (isset($_GET['id'])){
								$id=$_GET['id'];
								print create_windows_content("LIRE UN MESSAGE","ADDMSG",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un email</P>";
							}
						}	
	//==================================================================================================
	//	MODIFIER UNE NEWSLETTER
	//==================================================================================================
							
						if ($_GET['cat']=="251"){							
							if (isset($_GET['id'])){
								$id=$_GET['id'];
								print create_windows_content("MODIFIER UNE NEWSLETTER","ADDNEWSLETTER",TEMPLATE_WIN,$id,null);	
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner une newsletter</P>";
							}
						}				
						break;	
//==================================================================================================
//	EFFACER
//==================================================================================================
					
					case 2:
					
	//==================================================================================================
	//	EFFACER UN COMMENTAIRE
	//==================================================================================================
					
						if ($_GET['cat']=="242"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM comments WHERE id=$id");								
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un commentaire � effacer</P>";
							}
						}					
					
	//==================================================================================================
	//	EFFACER UN MOT DE LA BLACKLIST
	//==================================================================================================
					
						if ($_GET['cat']=="241"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM blacklist WHERE id=$id");
								
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un mot � effacer</P>";
							}
							blacklist::listmots();
						}
									
	//==================================================================================================
	//	EFFACER UN COMPTE UTILISATEUR
	//==================================================================================================
					
						if ($_GET['cat']=="255"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM admin WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un compte utilisateur � effacer</P>";
							}
						}
						
	//==================================================================================================
	//	EFFACER UN TYPE DE GALERIE
	//==================================================================================================
							
						if ($_GET['cat']=="245"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM type_photo WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner une galerie � effacer</P>";
							}
						}	
										
	//==================================================================================================
	//	EFFACER UNE GALERIE
	//==================================================================================================
	
						if ($_GET['cat']=="244"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM photo WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un �lement � effacer</P>";
							}
						}

						
	//==================================================================================================
	//	EFFACER UN BILLET
	//==================================================================================================
	
						if ($_GET['cat']=="243"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM billet WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un billet � effacer</P>";
							}
						}
	//==================================================================================================
	//	EFFACER UN MESSAGE
	//==================================================================================================
	
						if ($_GET['cat']=="253"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM messagerie WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un message � effacer</P>";
							}
						}
								
	//==================================================================================================
	//	EFFACER UN MENU
	//==================================================================================================
							
						if ($_GET['cat']=="247"){							
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM menu WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un menu � effacer</P>";
							}
						}
						
						
	//==================================================================================================
	//	EFFACER UNE BANNIERE
	//==================================================================================================
					
						if ($_GET['cat']=="256"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM bannieres WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner une banniere de pub</P>";
							}
						}
						
	//==================================================================================================
	//	EFFACER UN EMAIL
	//==================================================================================================
					
						if ($_GET['cat']=="252"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM mail_newsletter WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un email</P>";
							}
						}
						
	//==================================================================================================
	//	EFFACER UNE NEWSLETTER
	//==================================================================================================
					
						if ($_GET['cat']=="251"){
							if (isset($_GET['id'])){		
								$id=$_GET['id'];
								$res=mysql_query("DELETE FROM newsletter WHERE id=$id");
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner une newsletter</P>";
							}
						}
						break;	
						
//==================================================================================================
//	AJOUTER
//==================================================================================================
							
	//==================================================================================================
	//	AJOUTER UNE CATEGORIE
	//==================================================================================================
						
					case "ADDCAT":
						if (isset($_GET['id'])){
							$id=$_GET['id'];
							billetype($_GET['id'],"AJOUTER UNE CATEGORIE","ADDCAT","categorie","Veuillez selectionner une categorie");
						}else{
							print create_windows_content("AJOUTER UNE CATEGORIE","ADDCAT",TEMPLATE_WIN,null,null);	
						}
						break;	
						
					case "ADDTRADCAT":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE TRADUCTION AU TITRE DE CETTE CATEGORIE","ADDTRADCAT","categorie","Veuillez selectionner une categorie");
						}
						break;

						
					case "ADDPAGE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE PAGE","ADDPAGE","categorie","Veuillez selectionner une categorie");
						}
						break;
					case "ADDTRADPAGE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE TRADUCTION AU TITRE DE CETTE PAGE","ADDTRADPAGE","page","Veuillez selectionner une page");
						}
						break;

	//==================================================================================================
	//	AJOUTER UNE PAGE
	//==================================================================================================

					case "ADDGAL":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE GALERIE","ADDGAL","page","Veuillez selectionner une page");
						}
						break;
					case "ADDURL":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE UN LIEN INTERNET","ADDURL","page","Veuillez selectionner une page");
						}
						break;
					case "ADDFILE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UN FICHIER A TELECHARGER","ADDFILE","page","Veuillez selectionner une page");
						}
						break;								
					case "ADDDATE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE UNE DATE A LA PAGE","ADDDATE","page","Veuillez selectionner une page");
						}
						break;
					case "ADDVIDEO":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE VIDEO A LA PAGE","ADDVIDEO","page","Veuillez selectionner une page");
						}
						break;	
					case "ADDAUDIO":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UN FICHIER AUDIO A LA PAGE","ADDAUDIO","page","Veuillez selectionner une page");
						}
						break;
					case "ADDIMAGEPAGE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE IMAGE A LA PAGE","ADDIMAGEPAGE","page","Veuillez selectionner une page");
						}
						break;											
					case "ADDTEXTE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UN PARAGRAPHE","ADDTEXTE","page","Veuillez selectionner une page");
						}
						break;	
					case "ADDHTML":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER DU HTML","ADDHTML","page","Veuillez selectionner une page");
						}
						break;	
					case "ADDRSS":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UN FLUX RSS","ADDRSS","page","Veuillez selectionner une page");
						}
						break;	
	//==================================================================================================
	//	AJOUTER DANS UN PARAGRAPHE
	//==================================================================================================
					case "ADDTRADTEXTE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE TRADUCTION A UN TEXTE","ADDTRADTEXTE","texte","Veuillez selectionner un paragraphe");
						}
						break;	
					case "ADDTITRE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UN TITRE AU PARAGRAPHE","ADDTITRE","texte","Veuillez selectionner un paragraphe");
						}
						break;
					case "ADDTRADTITRE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE TRADUCTION AU TITRE","ADDTRADTITRE","titre","Veuillez selectionner un paragraphe");
						}
						break;
					case "ADDIMAGE":
						if (isset($_GET['id'])){
							billetype($_GET['id'],"AJOUTER UNE IMAGE AU PARAGRAPHE","ADDIMAGE","texte","Veuillez selectionner un paragraphe");
						}
						break;			
						
	//==================================================================================================
	//	AJOUTER UN MENU
	//==================================================================================================

					case "ADDMENU":
						if (isset($_GET['id'])){
							$id=$_GET['id'];
							billetype($_GET['id'],"AJOUTER UN MENU","ADDMENU","menu","Veuillez selectionner un element de menu");
						}else{	
							echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un element de menu</P>";
						}
						break;	
					case "ADDNEWMENU":
						print create_windows_content("CREATION D'UN NOUVEAU MENU","ADDNEWMENU",TEMPLATE_WIN,null,null);	
						break;					
	//==================================================================================================
	//	AJOUTER UNE GALERIE
	//==================================================================================================

					case "ADDELEMENTGAL":
						print create_windows_content("AJOUTER UN ELEMENT A LA GALERIE","ADDELEMENTGAL",TEMPLATE_WIN,null,null);	
						break;										
	//==================================================================================================
	//	AJOUTER UN TYPE DE GALERIE
	//==================================================================================================

					case "ADDTYPEGAL":
						print create_windows_content("AJOUTER UNE GALERIE","ADDTYPEGAL",TEMPLATE_WIN,null,null);	
						break;
	//==================================================================================================
	//	CREER UN COMPTE UTILISATEUR
	//==================================================================================================

					case "ADDUSER":
						print create_windows_content("AJOUTER UN COMPTE UTILISATEUR","ADDUSER",TEMPLATE_WIN,null,null);			
						break;
						
	//==================================================================================================
	//	AJOUTER UN MOT INTERDIT A LA BLACKLIST
	//==================================================================================================

					case "ADDBLACKLIST":
						print create_windows_content("AJOUTER UN MOT INTERDIT A LA BLACKLIST","ADDBLACKLIST",TEMPLATE_WIN,null,null);			
						break;
						
	//==================================================================================================
	//	AJOUTER UN COMMENTAIRE
	//==================================================================================================

					case "ADDCOMMENT":
										
							if (isset($_GET['id'])){
								$id=$_GET['id'];								
								print create_windows_content("REPONDRE A UN COMMENTAIRE","ADDCOMMENT",TEMPLATE_WIN,$id,null);			
							}else{
								echo "<P align='center' class='ALERTEDOUBLON'>Veuillez selectionner un commentaire pour y r�pondre.</P>";
							}					
						
						break;	
								
	//==================================================================================================
	//	AJOUTER UNE BANNIERES
	//==================================================================================================

					case "ADDPUB":
						print create_windows_content("AJOUTER UNE BANNIERE DE PUB","ADDPUB",TEMPLATE_WIN,null,null);			
						break;		
						
	//==================================================================================================
	//	AJOUTER UN EMAIL
	//==================================================================================================

					case "ADDEMAIL":
						print create_windows_content("AJOUTER UN EMAIL","ADDEMAIL",TEMPLATE_WIN,null,null);			
						break;			
								
	//==================================================================================================
	//	ENVOYER UN MESSAGE
	//==================================================================================================

					case "ADDMSG":
						print create_windows_content("ENVOYER UN MESSAGE","ADDMSG",TEMPLATE_WIN,null,null);			
						break;	
						
	//==================================================================================================
	//	CREER UNE NEWSLETTER
	//==================================================================================================

					case "ADDNEWSLETTER":
						print create_windows_content("CREER UNE NEWSLETTER","ADDNEWSLETTER",TEMPLATE_WIN,null,null);			
						break;	
						
	//==================================================================================================
	//	DEFAULT
	//==================================================================================================
									
					default:
						break;
				}
			}
		}
		
	//==================================================================================================
	//	VERIFICATION SI L UTILISATEUR A CHOISIS UN ELEMENT ASSOCIE A LA CREATION D UN NOUVEL ELEMENT
	//==================================================================================================
		
		function billetype($id,$wintitle,$var,$check,$alert){
			$restypepage=mysql_query("SELECT * FROM billet WHERE id=$id");
			$rowtypepage = mysql_fetch_array($restypepage);		
			
			if ($check!="menu"){
				$type = $rowtypepage['type'];
			}else{
				$type="";
			}
			
			if (($type==$check) || ($check=="menu")){		
				print create_windows_content($wintitle,$var,TEMPLATE_WIN,null,$id);	
			}else{
				$error=true;								
			}
			
			if (isset($error)){ echo "<P align='center' class='ALERTEDOUBLON'>$alert</P>"; }
		}		
		
		
?>