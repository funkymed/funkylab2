<?
//==================================================================================================
//	OPERATION SUR LES BANNIERES DE PUB
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
	//	AJOUT D UNE BANNIERE
	//==================================================================================================
		
			case "ADDPUB":	
				$url=$_GET['url'];	
				$nbclic=$_GET['nbclic'];	
				$image=$_GET['minibrowserlist'];	
				addpub("",$url,$image,$nbclic,"ADD");
				break;	
				
	//==================================================================================================
	//	MODIFICATION D UNE BANNIERE
	//==================================================================================================
		
			case "MODIFPUB":
				$url=$_GET['url'];	
				$nbclic=$_GET['nbclic'];	
				$image=$_GET['minibrowserlist'];	
				addpub("",$url,$image,$nbclic,$_GET['modifID']);
				break;					
		}
	}
	
	
	//==================================================================================================
	//	LIST DES BANNIERES
	//==================================================================================================
	$autorisation=$_SESSION["funkylab_autorisation"];
	if ($autorisation[7]=="1"){
		if (isset($_GET['list'])){	
			banniere::listbanniere();
		}	
	}else{
		echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire ça.</P>";
	}
	
	//==================================================================================================
	//	FONCTION D AJOUT / MODIFICATION DES BANNIERES
	//==================================================================================================
	
	function addpub($id,$url,$image,$nbclic,$addmodif){
		if ($addmodif=="ADD"){
			$query="INSERT INTO bannieres (id,url,image,nbclic) VALUES 
			('$id','$url','$image','$nbclic')";	
		}else{
			$query="UPDATE bannieres SET url='$url',image='$image',nbclic='$nbclic' WHERE id='$addmodif'";
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