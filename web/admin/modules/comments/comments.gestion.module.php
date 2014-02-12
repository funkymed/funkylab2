<?php

//==================================================================================================
//	OPERATION SUR LES COMMENTAIRES
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
	//	AJOUT D UN MOT INTERDIT
	//==================================================================================================
		case "ADDCOMMENT":	
				$pseudo=addslashes($_GET['pseudo']);
				$email=$_GET['email'];
				$billetid=$_GET['billetid'];				
				$message=addslashes($_GET['message']);	
				addcomm("",codeladate(),$billetid,$pseudo,$email,$message,"admin",$_GET['modifID']);
				break;	
	//==================================================================================================
	//	MODIFICATION D UN MOT INTERDIT
	//==================================================================================================
		case "MODIFCOMMENT":
				$pseudo=addslashes($_GET['pseudo']);
				$email=$_GET['email'];
				$billetid=$_GET['billetid'];
				$message=addslashes($_GET['message']);				
				addcomm("","",$billetid,$pseudo,$email,$message,"",$_GET['modifID']);
				break;
		}
	}
		
	
		
	//==================================================================================================
	//	LIST DES COMMENTAIRES
	//==================================================================================================
	
	$autorisation=$_SESSION["funkylab_autorisation"];
	if ($autorisation[5]=="1"){
		if (isset($_GET['list'])){	
			comments::listcom($_GET['page']);	
		}	
	}else{
		echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire �a.</P>";
	}
	
	//==================================================================================================
	//	FONCTION D AJOUT / MODIFICATION DES COMMENTAIRES
	//==================================================================================================
	
	function addcomm($id,$commentdate,$billetid,$pseudo,$email,$text,$ip_utilisateur,$addmodif){
		$utilisateur=$_SESSION["funkylab_id"];
		if ($ip_utilisateur=="admin"){
			$query="INSERT INTO comments (id,commentdate,billetid,pseudo,email,text,ip_utilisateur) VALUES 
			('$id','$commentdate','$billetid','$pseudo','$email','$text','$ip_utilisateur')";	
		}else{
			$query="UPDATE comments SET billetid='$billetid',pseudo='$pseudo',email='$email',text='$text',ip_utilisateur='$ip_utilisateur' WHERE id='$addmodif'";
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