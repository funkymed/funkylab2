<?php	header('Content-Type: text/xml');
	print "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
	print "<funkylab version=\"0.1\">";
	$buffer="";
	include "admin/config/config.bdd.url.php"; 
	include "admin/config/root.config.php"; 
	include "admin/include/globale.inc.php";
	$pass=md5($_GET['mdp']);	
	$sql = "SELECT * FROM admin WHERE login = '".$_GET['login']."' AND pass = '".$pass."'";
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
	$data = mysql_fetch_array($req);
	if (($_GET['login']=="") OR ($_GET['mdp']=="")){ 
		$buffer.="\t<etat>\n";
		$buffer.="\t\t<msg>pas de login/mdp</msg>\n";
		$buffer.="\t\t<value>0</value>\n";		
		$buffer.="\t</etat>\n";		
	}else{	
		if (($_GET['login']==$data['login']) AND ($pass==$data['pass'])){				
			$buffer.="\t<etat>\n";
			$buffer.="\t\t<msg>ok</msg>\n";
			$buffer.="\t\t<value>1</value>\n";		
			
			$count=0;
			$resCountCom=mysql_query("SELECT * FROM comments");	
			while ($rowCountCom = mysql_fetch_array($resCountCom)){ 
				$count++;
			}
			$buffer.="\t\t<nbcomtotal>".$count."</nbcomtotal>\n";		
			
			$buffer.="\t</etat>\n";						
			$query="SELECT * FROM messagerie WHERE recepteur=".$data['id'];
			$resMSG=mysql_query($query);	
			$nbMSG=0;
			$nbMSGnonlu=0;
			while ($rowMSG = mysql_fetch_array($resMSG)){				
				if ($rowMSG['lu']=="non"){				
					$nbMSGnonlu+=1;
				}				
				$nbMSG++;
			}								
			$buffer.="\t<utilisateur>\n";			
			$buffer.="\t\t<nom>".utf8_encode(stripslashes($data['nom']))."</nom>\n";			
			$buffer.="\t\t<prenom>".utf8_encode(stripslashes($data['prenom']))."</prenom>\n";			
			$buffer.="\t\t<login>".utf8_encode(stripslashes($data['login']))."</login>\n";			
			$buffer.="\t\t<email>".$data['email']."</email>\n";	
			$buffer.="\t\t<messagenonlu>$nbMSGnonlu</messagenonlu>\n";			
			$buffer.="\t\t<nbmessage>$nbMSG</nbmessage>\n";	
			$buffer.="\t</utilisateur>\n";
			$query="SELECT nom,id,auteur FROM billet WHERE type='page' AND visible='1' ORDER BY id DESC LIMIT ".$_GET['nbpage'];
			$resBILLET=mysql_query($query);			
			
			$buffer.="\t<billet>\n";
			while ($rowBILLET = mysql_fetch_array($resBILLET)){ 
				$bufferPAGE="";
				$bufferPAGE.="\t\t<page>\n";
				$bufferPAGE.="\t\t\t<titre>".utf8_encode(stripslashes($rowBILLET['nom']))."</titre>\n";
				$resAUTEUR=mysql_query("SELECT prenom,nom FROM admin WHERE id='".$rowBILLET['auteur']."'");
				$rowAUTEUR = mysql_fetch_array($resAUTEUR);
				$auteur_name=utf8_encode(stripslashes($rowAUTEUR['prenom']." ".strtoupper($rowAUTEUR['nom'])));
				$bufferPAGE.="\t\t\t<auteur>".$auteur_name."</auteur>\n";
				$query="SELECT * FROM comments WHERE billetid='".$rowBILLET['id']."' ORDER by id ASC";
				$count=0;
				$resCOM=mysql_query($query);
				$lastdate="";
				$auteurLastCom="";
				$auteurEmail="";
				$lastcomment="";
				while ($rowCOM = mysql_fetch_array($resCOM)){ 
					$count++;
					$lastdate=$rowCOM['commentdate'];
					$auteurLastCom=utf8_encode(stripslashes($rowCOM['pseudo']));
					$auteurEmail=$rowCOM['email'];
					$lastcomment=utf8_encode(stripslashes($rowCOM['text']));
				}
				if ($lastdate!=""){
					$lastdate=explode(" ",$lastdate);
					$heure=$lastdate[1];
					$lastdate=utf8_encode(decodedate($lastdate[0])." � ".$heure);					
				}
				$bufferPAGE.="\t\t\t<id>".$rowBILLET['id']."</id>\n";
				$bufferPAGE.="\t\t\t<nbcom>".$count."</nbcom>\n";
				$bufferPAGE.="\t\t\t<lastcom>\n";
				$bufferPAGE.="\t\t\t\t<auteur>".$auteurLastCom."</auteur>\n";
				$bufferPAGE.="\t\t\t\t<email>".$auteurEmail."</email>\n";
				$bufferPAGE.="\t\t\t\t<commentaire><![CDATA[".$lastcomment."]]></commentaire>\n";
				$bufferPAGE.="\t\t\t\t<date>".$lastdate."</date>\n";
				$bufferPAGE.="\t\t\t</lastcom>\n";
				$bufferPAGE.="\t\t</page>\n";
				
				if ($count!=0){
					$buffer.=$bufferPAGE;
				}else{
					$buffer.="";
				}
				
			}
			$buffer.="\t</billet>\n";
			
			
			
			
		}else{			
			$buffer.="\t<etat>\n";
			$buffer.="\t\t<msg>erreur</msg>\n";
			$buffer.="\t\t<value>0</value>\n";		
			$buffer.="\t</etat>\n";
		}
	}	
	$buffer.="</funkylab>";
	print($buffer);
?>