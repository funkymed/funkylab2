<? 
	header("Content-type: text/html"); 	
	header("Content-Disposition: filename=index.php\r\n\r\n"); 
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache");  
	
	session_start();

	/**************************************************
	
		NOM    : FUNKYLAB V2.03
		DATE   : 11/2005
		AUTEUR : Cyril PEREIRA 
		MAIL   : cyril.pereira@gmail.Com
		SITE   : http://www.cyrilpereira.com
		
	**************************************************/
	
	$timedepart= get_micro_time();
	
	if (isset($_GET['delog'])){ unset($_SESSION["funkylab_login"]);session_destroy(); }
	if (isset($_GET['demo'])){ $_SESSION["funkylab_login"] = "demo";}
	
	function get_micro_time(){
		list($usec, $sec)=explode(" ",microtime());
		return ((float)$usec+(float)$sec);
	}	
	require_once('config/config.bdd.url.php');
	require_once('config/root.config.php');
?>
<HTML>
	<head>
		<TITLE>Funkylab V2</TITLE>
		
		<script language="JavaScript" src="scripts/JSCookMenu.js"></script>
		<script language="JavaScript" src="scripts/ThemeOffice/theme.js"></script>
		<script language="JavaScript" src="scripts/tiny_mce/tiny_mce.js"></script>
		
		<? include "include/ajax.minibrowser.php"; ?>
	
		<script language="javascript" type="text/javascript">
			tinyMCE.init({
				mode : "exact",
				elements : "elm1",		
				textarea_trigger : "convert_this",
				theme : "simple"
			});			
		
			function ConfirmChoice(messageToCconfirm,urlredirect){
				answer = confirm(messageToCconfirm)
				if (answer ==true){
					location = urlredirect
				}
			}
		
			function deroule(ind,nbElement){				
				nbElement++;
				objicone=document.getElementById('fleche'+ind);
				objchange=document.getElementById('listobjchange-'+ind+'-'+1);				
				
				if (objchange.style.display=='none'){
					objicone.src="<? print SKIN; ?>/images/listopen.png";
					for (varI=1;varI<nbElement;varI++){
						//document.write(varI);
						document.getElementById('listobjchange-'+ind+'-'+varI).style.display='';
					}
					
				}else{					
					objicone.src="<? print SKIN; ?>/images/listclose.png";			
					for (varI=1;varI<nbElement;varI++){
						//document.write(varI);
						document.getElementById('listobjchange-'+ind+'-'+varI).style.display='none';
					}
					
				}
			}
		
		</script>

		<?
		echo "
		<script LANGUAGE='JavaScript'>
			function open_newpopup(bUrl, bName, bWidth, bHeight, bResize)
			{
				var lar=screen.width/2;
				var hau=screen.height/2;
				var lo=lar-bWidth/2;
				var ho=hau-bHeight/2;
				var newFenetre = window.open(bUrl,bName,'directories=no,location=no,toolbar=no,directories=no,menubar=no,resizable='+bResize+',scrollbars=yes,status=no,top='+ho+',left='+lo+',width='+bWidth+',height='+bHeight);
				if (navigator.appVersion.indexOf(\"MSIE 4.0\") < 0) newFenetre.focus();
			}
		</script>
	";		
		
		require_once('include/globale.inc.php');
		include "modules/messagerie/messagerie.module.php";
		include "modules/user/user.module.php";
		include "modules/newsletter/newsletter.module.php";
		include "modules/menu/menu.module.php";
		include "modules/billet/billet.module.php";
		include "modules/imagegallerie/imagegallerie.module.php";
		include "modules/imagegallerie/imagegallerie.type.module.php";
		include "modules/comments/comments.module.php";
		include "modules/comments/blacklist.module.php";
		include "modules/bdpub/bdpub.module.php";
		include "modules/template/template.module.php";
		include "modules/updater/updater.module.php";
			
		echo "<link rel=\"stylesheet\" href=\"",SKIN,"/css/css.css\" type=\"text/css\">\n";
		echo "\t\t<link rel=\"stylesheet\" href=\"",SKIN,"/css/theme.css\" type=\"text/css\">\n";
		
		if(isset($_GET['select'])){
			if (($_GET['select']=="1") && ($_GET['cat']=="253")){
				if(isset($_GET['idmail'])){
					messagerie::checkifread($_GET['idmail']);
				}
			}
		}		
		echo "\t\t<link rel=\"stylesheet\" href=\"".SKIN."/dragwin/css/css.css\" type=\"text/css\">";	
		?>
		
		<script language="JavaScript" src="scripts/JSCookTree.js"></script>
		<link rel="stylesheet" href="help/ThemeLibrary/theme.css" type="text/css">
		<script language="JavaScript" src="help/ThemeLibrary/theme.js"></script>	
		
	</head>	
	
	<?
		if (isset($_GET['cat'])){
			switch($_GET['cat']){
				case 247:
					$initindex.=" onload=\"writeMenuListMenu();\"";
					break;
				case 243:
					$initindex.=" onload=\"writeMenuListBillet();\"";
					break;
			}
		 }
	?>
	
	
	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <? echo $initindex; ?> >	
		<?	
			include SKIN."/index.php";
			$timearrive= get_micro_time();
			$temps=$timearrive-$timedepart;
			$temps=number_format($timearrive-$timedepart,2);
			echo "<P align='center'>temps pour créer la page : ",$temps," secondes<br/>
			optimisé pour <a href='http://www.mozilla-europe.org/fr/products/firefox/' target='blank'>Firefox</a><br/>
			<a href='http://www.mozilla-europe.org/fr/products/firefox/' target='blank'><img src='",SKIN,"/images/firefox.gif' border=0></a></P>
			";
			mysql_close($basetowork);
		?>	
		</body>	
</html>
