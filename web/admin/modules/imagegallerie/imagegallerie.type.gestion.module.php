<?php
	define ('TITRE', "GALLERIE");
	define ('BASE', "photo");
	define ('CAT', 244);

	if (isset($_GET['optimisesql'])){
		optimisebase($_GET['optimisesql']);
	}

	if (isset($_GET['vider'])){
		$type=$_GET['vider'];
		$resFree=mysql_query("SELECT * FROM photo WHERE type=$type");
		while ($rowFree = mysql_fetch_array($resFree)){
			$idFree=$rowFree['id'];
			$res=mysql_query("DELETE FROM photo WHERE id=$idFree");											
		}	
	}

	if (isset($_GET['ADDWORK'])){
		switch($_GET['ADDWORK']){
			/************ MENU ***************/
			case "ADDTYPEGAL":
				$parent=$_GET['type_id'];
				$type=addslashes($_GET['update_type']);
				$comment=addslashes($_GET['comment']);
				addgalerie("",$type,$parent,$comment,"ADD");	
				break;		
			case "MODIFTYPEGAL":	
				$parent=$_GET['type_id'];
				$type=addslashes($_GET['update_type']);
				$comment=addslashes($_GET['comment']);
				addgalerie("",$type,$parent,$comment,$_GET['modifID']);	
				break;	
		}
	}
	
	$autorisation=$_SESSION["funkylab_autorisation"];
	if ($autorisation[2]=="1"){		
		if (isset($_GET['list'])){			
			$str="";
			if(isset($_GET['type'])){
				$str=$_GET['type'];
			}
			imagetype::listype($str);
		}
	}else{
		echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire �a.</P>";
	}
	
	function addgalerie($id,$type,$parent,$comment,$addmodif){
		$auteur=$_SESSION["funkylab_id"];
		if ($addmodif=="ADD"){
			$query="INSERT INTO type_photo (id,type,parent,commentaire) VALUES ('$id','$type','$parent','$comment')";	
		}else{
			$query="UPDATE type_photo SET type='$type',parent='$parent',commentaire='$comment' WHERE id='$addmodif'";
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