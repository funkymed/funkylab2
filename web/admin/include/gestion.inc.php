<?php

	if (isset($_SESSION["funkylab_login"])){

		displaymenu();		
		include "modules/dragwin/gestion.dragwin.php";	
		
		$requesturi=explode("?",$_SERVER['REQUEST_URI']);
		
		if(count($requesturi)<2 && !isset($_POST['list'])){
			accueil();
		}
		
		if (isset($_POST['cat'])){
			switch($_POST['cat']){
				case 259: // TEMPLATE
					include "modules/template/template.gestion.module.php";
					break;
				case 239: // PATCH
					include "modules/updater/updater.gestion.module.php";
					break;	
			}
		}	
		
		if (isset($_GET['cat'])){
			switch($_GET['cat']){
				case 239: // PATCH
					include "modules/updater/updater.gestion.module.php";
					break;	

				case 240: // ACCUEIL
					if (isset($_GET['optimisesql'])){
						optimisebase("funkylab2");
					}								
					accueil();
					break;																	
				case 241: // BLACKLIST					
					include "modules/comments/blacklist.gestion.module.php";
					break;	
							
				case 242: // COMMENTAIRES
					include "modules/comments/comments.gestion.module.php";
					break;	
					
				case 243: // BILLET
					include "modules/billet/billet.gestion.module.php";
					break;	
					
				case 244: // GALLERIE					
					include "modules/imagegallerie/imagegallerie.gestion.module.php";
					break;
											
				case 245: // TYPE					
					include "modules/imagegallerie/imagegallerie.type.gestion.module.php";
					break;	
																																				
				case 247: // MENU				
					include "modules/menu/menu.gestion.module.php";
					break;	
																
				case 251: // NEWSLETTER
					include "modules/newsletter/newsletter.gestion.module.php";
					break;	
					
				case 252: // EMAIL NEWSLETTER
					include "modules/newsletter/newsletter.gestion.module.php";
					break;	
					
				case 253: // MESSAGERIE
					include "modules/messagerie/messagerie.gestion.module.php";
					break;			
						
				case 254: // CONFIG
					include "include/config.inc.php";
					break;				
		
				case 255: // UTILISATEUR				
					include "modules/user/user.gestion.module.php";
					break;
					
				case 256: // BDPUB
					include "modules/bdpub/bdpub.gestion.module.php";
					break;					
					
				case 257: // COMPTE WEB
					include "modules/website/website.gestion.module.php";
					break;
					
				case 258: // FLUX QUICKTIME
					include "modules/fluxquicktime/fluxquicktime.gestion.module.php";
					break;
				case 259: // TEMPLATE
					include "modules/template/template.gestion.module.php";
					break;
					
			}
			
		}
	}
		
	if (isset($_GET['apropos'])){
		include "modules/apropos/apropos.gestion.module.php";
	}else{
		if (isset($_GET['help'])){
			include "help/help.php";
		}else{
			include "modules/login/login.php";
		}
	}
		
	function displaymenu(){
		$autorisation=$_SESSION["funkylab_autorisation"];
		if ($autorisation[4]=="1"){
			$megabrowser="<a href=\"javascript:open_newpopup('../browser.php','browser',600,600,'no','no');\" DISABLED><IMG SRC='".SKIN."/images/megabrowser.png' border=0 align='absmiddle'>Gestionnaire de fichier</A>";
		}else{
			$megabrowser="";
		}
				echo "
		<TABLE border=0 width=100% class=\"menuline\" cellpadding=\"0\" cellspacing=\"0\">
			<TR >
				<TD class=\"menubackgr\" width=300><div id=\"myMenuID\"></div><SCRIPT LANGUAGE=\"JavaScript\">";
					include "scripts/menu.php";
				echo"</SCRIPT></TD>
				<TD align='center' width=200>$megabrowser</TD><TD width=200 align='center'>";
				
				messagerie::checkmail();
				
				echo"</TD>
				<TD align='right' class=\"menubackgr\"><a href='index.php?delog'><font color='#FFB000'>D�connexion</font></A> <font color='#AAAAAA'>",$_SESSION["funkylab_name"],"&nbsp;&nbsp;</font></TD>
			</TR>
			<TR>
		</TABLE>
		";	
	}	
	
	function accueil(){
		windowscreate("ACCUEIL",null,null,"debut",0);
		
		$info=_get_browser();
		echo"
		<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
			<TR class='windcontenu3'>
				<TD></	TD>
			</TR>
			<TR class='winline'>
				<TD colspan=3></TD>
			</TR>
			<TR>
				<TD align='center'>
					<TABLE>
						<TR>
							<TD align='center'>
								<fieldset>
									<legend>DETECTION DU NAVIGATEUR INTERNET</legend>";
								if ($info['browser']!="FIREFOX"){
									echo "<p align='left'>Attention vous n'utilisez pas <a href='http://www.mozilla-europe.org/fr/products/firefox/' target='_blank'>Firefox</a>, nous vous invitons � le telecharger pour travailler avec Funkylab dans des conditions optimale.
									</p>
									<p align='left'>Mozilla Firefox est le navigateur de nouvelle g�n�ration de la fondation Mozilla. Firefox vous permettra de surfer plus vite, plus s�rement et plus efficacement qu'avec n'importe quel autre navigateur.</p>
									<a href='http://www.mozilla-europe.org/fr/products/firefox/' target='blank'><img src='",SKIN,"/images/bigfirefox.gif' border=0></a>
									<p>Nous pr�cisons que nous ne sommes pas en partenariat avec Firefox.<br/>
									 Ce choix a �t� fais pour des raisons technique.</p>
									";			
								}else{
									echo "
									<p align='left'>Vous utilisez Firefox. Vous pouvez profiter des effets visuel ainsi qu'une fiabilit� maximale.</p>
									<p align='left'>Funkylab a �t� optmis� pour Firefox. Certains effets comme les couches alpha dans les images au format PNG ne sont pas support� sur d'autre navigateur ainsi qu'une compatibilit� avec AJAX.</p>
									";			
								}
							
								echo "</fieldset>
								<br/>
								<fieldset>
								<legend>ETAT DE LA BASE DE DONN�E</legend>
								",checkallbase(),"
								</fieldset>
								
								<br/>
								<fieldset>
								<legend>OCCUPATION DU SITE</legend>
								<font size=+1>Votre site occupe ".round(dskspace("../")/1048576)." Mo sur le serveur</font><br/>
								Le contenu multim�dia occupe ".round(dskspace("../image")/1048576)." Mo<br/>
								Les templates du site occupent ".round(dskspace("../template")/1048576)." Mo
								</fieldset>
							</TD>
						</TR>
					</TABLE>
				</TD>
			</TR>
		</TABLE>";		
	
		windowscreate(null,null,null,null,null);
	}
	
	function checkallbase(){
    $buffer='';
    /*
		$base = BASEDEFAUT;
		$table = mysql_list_tables($base);
		$txt="";
		$req = mysql_query('SHOW TABLE STATUS');
		$optimise=false;
		$datalose="";
		while($data = mysql_fetch_assoc($req))
		{
		    if($data['Data_free'] > 0)
		    {
		         $optimise=true;
		    }
		    $datalose+=$data['Data_free'];
		}
		if ($optimise==true){
			if ($datalose<=1024){
				$message="<P>La base n'a pas besoin d'�tre optimis�</p>";	
			}
			if ($datalose>=1024){
				$message="<P>Vous devriez optimiser la base</p>";	
			}			
			$action="<a href='index.php?optimisesql=funkylab2&cat=240'>Optimiser la base</A>";
		}else{
			$message="<P>Optimis�e</P>";
			$action="aucune action possible";
		}
		$buffer="
			<table align='center' width=400>
				<tr>
					<td align='center' class='userbox'>ETAT</td>
					<td align='center' class='userbox'>PERTE</td>
					<td align='center' class='userbox'>ACTION</td>
				</tr>
				<tr>
					<td align='center'>$message</td>
					<td align='center'>$datalose  Octets</td>
					<td align='center'>$action</td>
				</tr>
			</table>
		";
    */
		return($buffer);
	}
		
	function _get_browser(){
		$browser = array ( //reversed array
			"OPERA",
			"MSIE",            // parent
			"NETSCAPE",
			"FIREFOX",
			"SAFARI",
			"KONQUEROR",
			"MOZILLA"        // parent
	 	 );
	  	$info['browser'] = "OTHER";
		foreach ($browser as $parent){
			if ( ($s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent)) !== FALSE ){           
				$f = $s + strlen($parent);
				$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 5);
				$version = preg_replace('/[^0-9,.]/','',$version);
				$info['browser'] = $parent;
				$info['version'] = $version;
				break; // first match wins
			}
		}
		return $info;
	}
	
?>