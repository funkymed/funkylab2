<?
	class comment{
		
		function add_com($add_pseudo,$add_mail,$add_commentaire,$idbillet){
			$pseudo=addslashes(strip_tags($add_pseudo));
			$newemail=addslashes(strip_tags($add_mail));
			$newcom=addslashes(strip_tags($add_commentaire));
			$IPposter=$_SERVER['REMOTE_ADDR'];
			$date=codeladate();
			$query="INSERT INTO comments (id,commentdate,billetid,pseudo,email,text,ip_utilisateur) VALUES 
			('','$date','$idbillet','$pseudo','$newemail','$newcom','$IPposter')";			
			$resultat=@mysql_query($query);
		}
		
		function checkcomment($add_pseudo,$add_mail,$add_commentaire,$idbillet){	
			
			$error=false;
			$message=array();
		  	$msgtxt="";
		  		
		  	if($add_pseudo==""){
			  	$error=true;
			  	$message[]="Vous n'avez pas entré de pseudo";
		  	}
		  	if ($add_mail==""){
			  	$error=true;
			  	$message[]="Vous n'avez pas saisie votre email";
		  	} 	
		  	
		  	if (verifmail($add_mail)==false){
				$error=true;
		  		$message[]="Votre email est incorrect";
			}
			
		  	if ($add_commentaire==""){
			  	$error=true;
			  	$message[]="Vous n'avez pas saisie de commentaire";
		  	}
		  	if (FILTRECOM==true){
			  	if (blacklist::checkmot($add_commentaire)==true){
				  	$error=true;
				  	$message[]="Vous ne devez pas tenir ce genre de propos sur le forum !";
			  	}
		  	}
		  	
			if ($error==true){ 
				$msgtxt.=  "<P class=\"titrebox\">Votre commentaire n'a pas été envoyé car il contient des erreurs</P>";			
			}else{
				$msgtxt.=  "<P class=\"titrebox\">votre commentaire est enregistré</P>";
				comment::add_com($add_pseudo,$add_mail,$add_commentaire,$idbillet);			
			}
		  	for ($xx=0;$xx<=count($message)-1;$xx++){
			  	$msgtxt.= "<P class='titrebox'>".$message[$xx]."</P>";
		  	}		
			$buffer="<div id='botomform'><fieldset ><legend>COMMENTAIRE</legend><p>".$msgtxt."</p></fieldset></div>";
		
			return($buffer);
		
		}
	}

	class blacklist{
		function checkmot($commentaire){
			$resblack=mysql_query("SELECT * FROM blacklist");
			$error=false;		
			while ($rowblack = mysql_fetch_array($resblack)){   
				$mot=$rowblack['mot'];
				if (strstr($commentaire, $mot) ) {
					$error=true;		
				}			
			}		
			return($error);
		}	
		
		function censure($commentaire){
			$resblack=mysql_query("SELECT * FROM blacklist");
			$error=false;		
			while ($rowblack = mysql_fetch_array($resblack)){   
				$mot=$rowblack['mot'];
				if (strstr($commentaire, $mot) ) {
					$commentaire=str_replace($mot, "xxx", $commentaire);
				}			
			}		
			return($commentaire);
		}
	}
?>