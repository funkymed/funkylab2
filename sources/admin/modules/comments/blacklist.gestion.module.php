<?

//==================================================================================================
//	OPERATION SUR LA BLACKLIST DES COMMENTAIRES
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
		case "ADDBLACKLIST":	
				$mot=addslashes($_GET['mot']);
				addblacklist("",$mot,"ADD");
				break;	
	//==================================================================================================
	//	MODIFICATION D UN MOT INTERDIT
	//==================================================================================================
		case "MODIFBLACKLIST":
				$mot=addslashes($_GET['mot']);
				addblacklist("",$mot,$_GET['modifID']);
				break;
		}
	}
		
	//==================================================================================================
	//	LIST DES MOTS DE LA BLACKLIST
	//==================================================================================================
	$autorisation=$_SESSION["funkylab_autorisation"];
	if ($autorisation[5]=="1"){
		if (isset($_GET['list'])){	
			blacklist::listmots();
			
		}	
	}else{
		echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire ça.</P>";
	}			
	
	//==================================================================================================
	//	FONCTION D AJOUT / MODIFICATION DE MOTS DE LA BLACKLIST
	//==================================================================================================
	
	function addblacklist($id,$mot,$addmodif){
		$utilisateur=$_SESSION["funkylab_id"];
		if ($addmodif=="ADD"){
			$query="INSERT INTO blacklist (id,mot,utilisateur) VALUES 
			('$id','$mot','$utilisateur')";	
		}else{
			$query="UPDATE blacklist SET mot='$mot',utilisateur='$utilisateur' WHERE id='$addmodif'";
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