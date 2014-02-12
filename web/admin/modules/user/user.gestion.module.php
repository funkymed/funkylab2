<?php
//==================================================================================================
//	OPERATION SUR LES COMPTES UTILISATEUR
//==================================================================================================
	
	//==================================================================================================
	//	OPTMISATION DE LA BASE SQL A LA DEMANDE
	//==================================================================================================
			
	if (isset($_GET['optimisesql'])){
		optimisebase($_GET['optimisesql']);
	}	

	if (isset($_GET['ADDWORK'])){		
		switch($_GET['ADDWORK']){
	//==================================================================================================
	//	AJOUT D UN COMPTE UTILISATEUR
	//==================================================================================================
		
			case "ADDUSER":	
				$login=$_GET['add_login'];
				$pass=md5($_GET['add_pass']);
				$nom=$_GET['add_nom'];
				$prenom=$_GET['add_prenom'];
				$email=$_GET['add_email'];
				$option=$_GET['listcategorie'];
				$autorisation="";
				if(isset($_GET['check_admin']))		 		{	$admin=1;				}else{ $admin=0;			}
				if(isset($_GET['check_newsletter'])) 		{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
				if(isset($_GET['check_contenu']))	 		{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
				if(isset($_GET['check_galerie']))	 		{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
				if(isset($_GET['check_menu']))		 		{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
				if(isset($_GET['check_megabrowser']))		{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
				if(isset($_GET['check_comments']))	 		{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
				if(isset($_GET['check_restrictedbillet']))	{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
				if(isset($_GET['check_pub']))				{	$autorisation.="1";		}else{ $autorisation.="0";	}				
				
				$datecreation=codeladate();
				$derniereconnexion="";
				adduser("",$autorisation,$login,$pass,$nom,$prenom,$email,$datecreation,$derniereconnexion,$admin,$option,"ADD");
				break;	
				
	//==================================================================================================
	//	MODIFICATION D UN COMPTE UTILISATEUR
	//==================================================================================================
		
			case "MODIFUSER":
				$ressql=mysql_query("SELECT * FROM admin WHERE id='".$_GET['modifID']."'");
				$rowsql = mysql_fetch_array($ressql);
				$datecreation=$rowsql['datecreation'];
				$derniereconnexion=$rowsql['derniereconnexion'];
				$lastpass=$rowsql['pass'];
				$login=$_GET['add_login'];
				if ($_GET['add_pass']==$lastpass){
					$pass=$lastpass;
				}else{
					$pass=md5($_GET['add_pass']);
				}
				$nom=$_GET['add_nom'];
				$prenom=$_GET['add_prenom'];
				$email=$_GET['add_email'];
				$option=$_GET['listcategorie'];
				$autorisation="";
				if(testuseradmin()==true){
					if(isset($_GET['check_admin']))		 		{	$admin=1;				}else{ $admin=0;			}
					if(isset($_GET['check_newsletter'])) 		{	$autorisation.="1,";	}else{ $autorisation.="0,"; }
					if(isset($_GET['check_contenu']))	 		{	$autorisation.="1,";	}else{ $autorisation.="0,"; }
					if(isset($_GET['check_galerie']))	 		{	$autorisation.="1,";	}else{ $autorisation.="0,"; }
					if(isset($_GET['check_menu']))		 		{	$autorisation.="1,";	}else{ $autorisation.="0,"; }
					if(isset($_GET['check_megabrowser']))		{	$autorisation.="1,";	}else{ $autorisation.="0,"; }
					if(isset($_GET['check_comments']))	 		{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
					if(isset($_GET['check_restrictedbillet']))	{	$autorisation.="1,";	}else{ $autorisation.="0,";	}
					if(isset($_GET['check_pub']))				{	$autorisation.="1";		}else{ $autorisation.="0";	}
				}else{
					$admin=0;
					$autorisation=$rowsql['autorisation'];
				}
				adduser("",$autorisation,$login,$pass,$nom,$prenom,$email,$datecreation,$derniereconnexion,$admin,$option,$_GET['modifID']);
				break;					
		}
	}
		
	//==================================================================================================
	//	LIST DES COMPTES UTILISATEUR
	//==================================================================================================
		
	if (isset($_GET['list'])){	
		if (testuseradmin()==true){
			Admin_user::listuser();	
		}
	}	
	
	//==================================================================================================
	//	FONCTION D AJOUT / MODIFICATION DE COMPTE UTILISATEUR
	//==================================================================================================
	
	function adduser($id,$autorisation,$login,$pass,$nom,$prenom,$email,$datecreation,$derniereconnexion,$admin,$option,$addmodif){
		
		if ($addmodif=="ADD"){
			$query="INSERT INTO admin (id,autorisation,login,pass,nom,prenom,email,datecreation,derniereconnexion,optionadmin,admin) VALUES 
			('$id','$autorisation','$login','$pass','$nom','$prenom','$email','$datecreation','$derniereconnexion','$option','$admin')";	
		}else{
			$query="UPDATE admin SET autorisation='$autorisation',login='$login',pass='$pass',nom='$nom',prenom='$prenom',email='$email',datecreation='$datecreation',derniereconnexion='$derniereconnexion',optionadmin='$option',admin='$admin' WHERE id='$addmodif'";
		}
		
		$resultat=@mysql_query($query);
		/*
		if ($resultat=="1"){ 
			echo "<P>c bon</P>"; 	
		}else{ 
			echo mysql_error()."<BR><BR>";
			echo $query;			
			echo "<P>ERREUR</P>"; 
		}
		*/
	}		
	

?>