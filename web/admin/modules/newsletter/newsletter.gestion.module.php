<?php
	/*********************************************
					NEWSLETTER
	*********************************************/
$autorisation=$_SESSION["funkylab_autorisation"];
if ($autorisation[0]=="1"){
		
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
		
			case "ADDEMAIL":			
				$email=$_GET['email'];	
				$ipEMAIL=$_SESSION["funkylab_name"];	
				$date=codeladate();
				addemail("",$email,$date,$ipEMAIL,"ADD");
				break;	
				
	//==================================================================================================
	//	MODIFICATION D UNE BANNIERE
	//==================================================================================================
		
			case "MODIFEMAIL":
				$email=$_GET['email'];	
				$ipEMAIL=$_SESSION["funkylab_name"];	
				$date=codeladate();
				addemail("",$email,$date,$ipEMAIL,$_GET['modifID']);
				break;		
	//==================================================================================================
	//	AJOUT D UNE NEWSLETTER
	//==================================================================================================
		
			case "ADDNEWSLETTER":	
				$date=codeladate();
				$parqui=$_SESSION["funkylab_id"];	
				$send=0;
				$senddate="";
				$titre=addslashes($_GET['titre_edito']);
				$edito=addslashes($_GET['texte_edito']);
				$billets=implode(",",$_GET['add_billet_newsletter']);
				$lastbillet=$_GET['lastbillet'];			
				addnewsletter("",$date,$parqui,$send,$senddate,$titre,$edito,$billets,$lastbillet,"ADD");			
				break;	
				
	//==================================================================================================
	//	MODIFICATION D UNE NEWSLETTER
	//==================================================================================================
		
			case "MODIFNEWSLETTER":
				$date=codeladate();
				$parqui=$_SESSION["funkylab_id"];	
				$send=0;
				$senddate="";
				$titre=addslashes($_GET['titre_edito']);
				$edito=addslashes($_GET['texte_edito']);
				$billets=implode(",",$_GET['add_billet_newsletter']);
				$lastbillet=$_GET['lastbillet'];			
				addnewsletter("",$date,$parqui,$send,$senddate,$titre,$edito,$billets,$lastbillet,$_GET['modifID']);
				break;				
		}
	}
	
	
	$autorisation=$_SESSION["funkylab_autorisation"];
	if ($autorisation[0]=="1"){
		if (isset($_GET['listmail'])){	
			newsletter::listmail();	
		}
		
		if ((isset($_GET['select'])) && ($_GET['select']=="SENDNEWSLETTER")){		
			newsletter::sendnewsletter($_GET['id']);
		}else{			
			
			if (isset($_GET['listletter'])){	
				newsletter::listletter();			
			}
		}
		
	}else{
		echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire �a.</P>";
	}
	

	
	
}else{
	echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire �a.</P>";
}


	//==================================================================================================
	//	FONCTION D AJOUT / MODIFICATION DES BANNIERES
	//==================================================================================================
	
	function addemail($id,$mail,$date,$IPcreation,$addmodif){
		if ($addmodif=="ADD"){
			$query="INSERT INTO mail_newsletter (id,mail,date,IPcreation) VALUES 
			('$id','$mail','$date','$IPcreation')";	
		}else{
			$query="UPDATE mail_newsletter SET mail='$mail',date='$date',IPcreation='$IPcreation' WHERE id='$addmodif'";
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
 
	//==================================================================================================
	//	FONCTION D AJOUT / MODIFICATION DE NEWSLETTER
	//==================================================================================================
	
	function addnewsletter($id,$date,$parqui,$send,$senddate,$titre,$edito,$billets,$lastbillet,$addmodif){
		if ($addmodif=="ADD"){
			$query="INSERT INTO newsletter (id,date,parqui,send,senddate,titre,edito,billets,lastbillet) VALUES 
			('$id','$date','$parqui','$send','$senddate','$titre','$edito','$billets','$lastbillet')";	
		}else{
			$query="UPDATE newsletter SET date='$date',parqui='$parqui',send='$send',senddate='$senddate',titre='$titre',edito='$edito',billets='$billets',lastbillet='$lastbillet' WHERE id='$addmodif'";
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