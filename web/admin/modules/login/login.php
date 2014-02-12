<?php
if (isset($_SESSION["funkylab_login"])){
}else{
	
	if (isset($_POST['login'])){
		
		$pass=md5($_POST['pass']);
		
		$sql = "SELECT * FROM admin WHERE login = '".$_POST['login']."' AND Pass = '".$pass."'";
		$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
		$data = mysql_fetch_array($req);

		if (($_POST['login']=="") OR ($_POST['pass']=="")){ 
			erreurlogin();
		}else{	
			if (($_POST['login']==$data['login']) AND ($pass==$data['pass'])){	
				$id=$data['id'];
				$lastconnect=codeladate();
				$query="UPDATE admin SET derniereconnexion='$lastconnect' WHERE id='$id'";
				$result=mysql_query($query);						

				$_SESSION['funkylab_id'] = $data['id'];			
				$_SESSION['funkylab_login'] = $_POST['login'];					
				$_SESSION['funkylab_admin'] = $data['admin'];				
				$_SESSION['funkylab_autorisation'] = explode(",",$data['autorisation']);				
				$_SESSION['funkylab_name'] = $data['nom']." ".$data['prenom'];				
				$_SESSION['funkylab_optionadmin'] = $data['optionadmin'];
				  
				//$_SESSION['funkylab_superadmin'] = $data['superadmin'];			
				
				displaymenu();	
				accueil();
			}else{
				
				erreurlogin();
			}
		}
	}else{
		include ("connect.inc.php");
	}
}	

function erreurlogin(){
	echo "
	<BR>
	<TABLE border=0 class='loginbox' align='center' width=500 cellspacing=\"15\" cellpadding=\"0\">
		<TR>
			<TD width=200>
				<center><IMG SRC='",SKIN,"/images/security.png'></center>
				<P>Bienvenue dans l'administration du site</P>
				<P>Utilisez un nom d'utilisateur et un mot de passe valides pour obtenir l'acc�s � la console d'administration.</P>
				<P>Si vous avez oubli� votre mot de passe cliquez <a href='index.php?losepass'>ici</A>
			</TD>
			<TD class='titrelogin'>
				<fieldset>
				<legend>Connexion</legend>
					<P align='center'><a href='index.php'>Veuillez recommencer!!</A><BR>
					Si vous avez perdu votre mot de passe cliquez <a href='index.php?losepass'>ici</A></P>
				</fieldset>			
			</TD>
		</TR>
	</TABLE>	
	";
}
?>