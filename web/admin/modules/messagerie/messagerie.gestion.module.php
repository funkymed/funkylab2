<?php
//==================================================================================================
//	OPERATION DE MESSAGERIE
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
		
			case "ADDMSG":
				$fromqui=$_SESSION["funkylab_id"];
				$recepteur=$_GET['recepteur'];
				$date_envois=codeladate();
				$objet=addslashes($_GET['objet']);
				$message=addslashes($_GET['message']);
				addmessage("",$fromqui,$recepteur,$date_envois,$objet,$message,"non","ADD");
				break;	
		}
	}
	
	if (isset($_GET['list'])){
		messagerie:: listmesssage();
	}		
	
	//==================================================================================================
	//	FONCTION D AJOUT / MODIFICATION DE COMPTE UTILISATEUR
	//==================================================================================================
	
	function addmessage($id,$fromqui,$recepteur,$date_envois,$objet,$message,$lu,$addmodif){
		if ($addmodif=="ADD"){
			$query="INSERT INTO messagerie (id ,fromqui,recepteur,date_envois,objet,message,lu) VALUES 
			('$id','$fromqui','$recepteur','$date_envois','$objet','$message','$lu')";	
		}else{
			$query="UPDATE messagerie SET fromqui='$fromqui',recepteur='$recepteur',date_envois='$date_envois',objet='$objet',message='$message',lu='$lu' WHERE id='$addmodif'";
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