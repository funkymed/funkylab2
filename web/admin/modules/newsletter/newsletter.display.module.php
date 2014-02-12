<?php
	echo "
	<script LANGUAGE='JavaScript'>			
		function closewin()
		{
			window.close();
		}
	</script>";	
	include "../../config/config.bdd.url.php";
	include "../../config/root.config.php";
	include "../../include/globale.inc.php";
	
	$id=$_GET['id'];
	
	$res=mysql_query("SELECT * FROM newsletter WHERE id=$id");
	$row = mysql_fetch_array($res);
	
	$date=$row['date'];
	
	$filearchive=explode(" ",$date);
	$filearchive=$filearchive[0];
	
	$date=explode(" ",$date);
	$date=decodedate($date[0]);
		
	$parqui=$row['parqui'];
	$senddate=$row['senddate'];
	$titre=stripslashes($row['titre']);
	$edito=stripslashes($row['edito']);
	
	$billets=$row['billets'];
	if ($billets!=0){
		$billets=explode(",",$billets);
	}
	
	
	$lastbillet=$row['lastbillet'];
	define("URLSKINLETTER",ROOTURL.CURRENT_SKIN);		
	define("ARCHIVE","../../../archives_newsletter");
	define("URLSITE",ROOTURL);

	$mail ="
	<html>
		<head>
			<title>Newsletter $nomsite $date </title>
			<link rel=\"stylesheet\" href=\"".URLSKINLETTER."/newsletter/css/css.css\" type=\"text/css\">
		</head>
		<body>
		
		<table id=\"page\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
				<td><img src=\"".URLSKINLETTER."/newsletter/entete.jpg\"></td>
			</tr>
			<tr>
				<td background=\"".URLSKINLETTER."/newsletter/version.gif\" align='right' class='newsletterdate'>
					Newsletter du $date
				</td>
			</tr>
			<tr>
				<td>
					<p class='titre'>$titre</p>
					<p class='edito'>$edito</p>
				</td>		
			</tr>";
			
			if(is_array($billets)){
				$mail.="					
				<tr>
					<td>
						<br/>
						<p class='newsletterdate'>LES BILLETS IMPORTANT</p>";
						$mail.=billet_light($billets);					
						$mail.="
					</td>		
				</tr>";
			}

			
			if($lastbillet>0){
				$mail.="					
				<tr>
					<td>
						<br/>
						<p class='newsletterdate'>LES $lastbillet DERNIERES PAGE AJOUT�E</p>";
						$mail.=last_billet_light($lastbillet);					
						$mail.="
					</td>		
				</tr>";
			}
			
			$mail.="	
			<tr>
				<td>
					<hr class=\"hrstyle\">
					<p align='center'>Si vous desirez vous desabonner entrez � nouveau votre email dans la zone \"newsletter\" sur notre site <a href='".URLSITE."' target='_blank'>".URLSITE."</a></P>
				</td>		
			</tr>		
		</table>";
		
		if (isset($_GET['debug'])){	
			$mail.="<P align='center'><a href='javascript:closewin()' class='userbox'>&nbsp;Fermer la fenetre&nbsp;</A></P>";
		}
		$mail.="
		</body>
	</html>
";

	if (isset($_GET['debug'])){
		print $mail;
	}else{		
		$fp = @fopen(ARCHIVE."/".$filearchive.".html","x+");
		
		if ($fp==false){
			echo "Le fichier est d�j� disponible dans les archives";
		}else{
			fseek($fp,0);
			fputs($fp,$mail);
			fclose($fp);
			@chmod($fp,0644);
		}
				
		@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
		$res=mysql_query("SELECT * FROM mail_newsletter ORDER BY id DESC");
		
		$mailto=array();
		while ($row = mysql_fetch_array($res)) {array_push($mailto,$row['mail']);}		
		echo "<P align='center'>";
		for ($AA=0;$AA<=count($mailto)-1;$AA++){
			MAIL_NVLP("Newsletter $nomsite du $date", EMAILPOST, $mailto[$AA], $mailto[$AA], $titre, $mail);	
		}
		$date=codeladate();
		$query="UPDATE newsletter SET send='1',senddate='$date' WHERE id='$id'";
		$resultat=mysql_query($query);
		echo "<BR><a href=\"../../index.php?cat=251&listletter\" target='_top'>Retour</A></P>";		
	}
	
	
	function billet_light($billets){
		$buffer="";
		for($XX=0;$XX<count($billets)-1;$XX++){
			$id=$billets[$XX];
			$res=mysql_query("SELECT * FROM billet WHERE id='$id'");
			$row = mysql_fetch_array($res);
			$cheminfer=cheminfer("",$id);
			$nom = strtoupper(stripslashes($row['nom']));
			$type=$row['type'];			
			
			$date_debut=$row['date_debut'];		
			
			$dateview="";
			
			if ($date_debut!="0000-00-00"){			
				$dateview="<p class=\"datelastbillet\">".decodedate($date_debut)."</p>";			
			}
			
			$texte="";
			$resTEXTE=mysql_query("SELECT * FROM billet WHERE parent='$id' AND type='texte'");
			while ($rowTEXTE = mysql_fetch_array($resTEXTE)){ 
				$texte.=strip_tags($rowTEXTE['texte']);
			}
			$texte= reduitletext(stripslashes($texte),500);
			switch($type){	
				case "categorie":				
					$buffer.="			
					<table id=\"hotbillet\">
						<tr>
							<td valign='top'>
								$cheminfer > <a href='".ROOTURL."index.php?billet=$id' target='blank' class='titre'>$nom</a> (categorie)<br/>
								<p>$texte</p>
								$dateview
							</td>
						</tr>				
					</table>
					";
					break;					
				case "page":
					$buffer.="			
					<table id=\"hotbillet\">
						<tr>
							<td valign='top'>
								$cheminfer > <a href='".ROOTURL."index.php?billet=$id' target='blank' class='titre'>$nom</a> (page)<br/>
								<p>$texte</p>
								<p class=\"datelastbillet\">".decodedate($date_debut)."</p>
							</td>
						</tr>				
					</table>
					";
					break;
			}
		}
		return($buffer);
	}	
	

	function last_billet_light($nb){
		$buffer="";
		@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
		$res=mysql_query("SELECT * FROM billet WHERE type='page' ORDER BY date_debut DESC LIMIT $nb");
		while ($row = mysql_fetch_array($res)){ 
			$id=$type=$row['id'];
			$nom = strtoupper(stripslashes($row['nom']));
			$type=$row['type'];			
			$date_debut=$row['date_debut'];
			$texte="";
			$resTEXTE=mysql_query("SELECT * FROM billet WHERE parent='$id' AND type='texte'");
			while ($rowTEXTE = mysql_fetch_array($resTEXTE)){ 
				$texte.=strip_tags($rowTEXTE['texte']);
			}
			$texte= reduitletext(stripslashes($texte),500);
			$cheminfer=cheminfer("",$id);
			$buffer.="			
			<table id=\"lastbillet\">
				<tr>
					<td valign='top'>
						$cheminfer > <a href='".ROOTURL."index.php?billet=$id' target='blank' class='titre'>$nom</a><br/>
						<p>$texte</p>
						<p class=\"datelastbillet\">".decodedate($date_debut)."</p>
					</td>
				</tr>				
			</table>
			";
		}
		return($buffer);
	}	
	
	function cheminfer($cheminfer,$id){
		@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
		$resFER=mysql_query("SELECT nom,id,parent FROM billet WHERE id='$id'");	
		$rowFER = mysql_fetch_array($resFER);
		$nomFER=strtoupper(stripslashes($rowFER['nom']));
		$idFER=stripslashes($rowFER['id']);
		
		if (isset($_GET['menuset'])){
			$urlmenu="&menuset=".$_GET['menuset'];
		}else{
			$urlmenu="";
		}
		
		if ($rowFER['parent']==0){
			$cheminfer.="<a href='".ROOTURL."index.php?billet=$idFER".$urlmenu."' target='blank'>$nomFER</a> ";					
		}else{
			$cheminfer.=cheminfer($cheminfer,$rowFER['parent'])." > <a href='".ROOTURL."index.php?billet=$idFER".$urlmenu."' target='blank'>$nomFER</a>";	
		}			
		return($cheminfer);	
	}
	
	function MAIL_NVLP($fromname, $fromaddress, $toname, $toaddress, $subject, $message)
	{
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=iso-8859-15\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: \"".$fromname."\" <".$fromaddress.">\n";
		$test=@mail($toaddress, $subject, $message, $headers);
		
		if ($test==1){
			echo "Email envoy� � ",$toaddress,"<br/>\n" ;
		}else{
			echo "<b>Email rejet� ",$toaddress,"</b><br/>\n" ;
		}
	}	
	
?>