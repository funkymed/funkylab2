<?
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
			$res=mysql_query("DELETE FROM comments WHERE id=$idFree");											
		}	

	}

	if (isset($_GET['ADDWORK'])){
		switch($_GET['ADDWORK']){
			/************ MENU ***************/
			case "ADDELEMENTGAL":
				$nom=$_GET['image_nom'];
				$file=$_GET['minibrowserlist'];
				$description=$_GET['desc'];
				$type=$_GET['listgalerie'];	
				$prix=$_GET['image_prix'];	
				$tailleX=$_GET['image_tailleX'];
				$tailleY=$_GET['image_tailleY'];
				$motscle=$_GET['image_mots'];			
				$date=$_GET['image_date'];	
				$pays=$_GET['image_pays'];
				$resolution=$_GET['image_resolution'];
				$plimus=$_GET['image_plimus'];
				addelement("",$nom,$file,$description,$type,$prix,$tailleX,$tailleY,$motscle,$date,$pays,$resolution,$plimus,"ADD");					
				break;		
			case "MODIFELEMENTGAL":	
				$nom=$_GET['image_nom'];
				$file=$_GET['minibrowserlist'];
				$description=$_GET['desc'];
				$type=$_GET['listgalerie'];	
				$prix=$_GET['image_prix'];	
				$tailleX=$_GET['image_tailleX'];
				$tailleY=$_GET['image_tailleY'];
				$motscle=$_GET['image_mots'];			
				$date=$_GET['image_date'];	
				$pays=$_GET['image_pays'];
				$resolution=$_GET['image_resolution'];
				$plimus=$_GET['image_plimus'];
				addelement("",$nom,$file,$description,$type,$prix,$tailleX,$tailleY,$motscle,$date,$pays,$resolution,$plimus,$_GET['modifID']);					
				break;	
		}
	}
	
	$autorisation=$_SESSION["funkylab_autorisation"];
	if ($autorisation[2]=="1"){		
		if (isset($_GET['list'])){			
			$str="";
			if(isset($_GET['listgalerie'])){
				$str=$_GET['listgalerie'];
			}
			imagegallerie::listimage($str);
		}
	}else{
		echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire ça.</P>";
	}	
	
	function addelement($id,$nom,$file,$description,$type,$prix,$tailleX,$tailleY,$motscle,$date,$pays,$resolution,$plimus,$addmodif){
		$auteur=$_SESSION["funkylab_id"];
		if ($addmodif=="ADD"){
			$query="INSERT INTO photo (id,nom,file,description,type,prix,tailleX,tailleY,motscle,date,pays,resolution,plimus) VALUES 
			('$id','$nom','$file','$description','$type','$prix','$tailleX','$tailleY','$motscle','$date','$pays','$resolution','$plimus')";	
		}else{
			$query="UPDATE photo SET nom='$nom',file='$file',description='$description',type='$type',prix='$prix',tailleX='$tailleX',tailleY='$tailleY',motscle='$motscle',date='$date',pays='$pays',resolution='$resolution',plimus='$plimus' WHERE id='$addmodif'";
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