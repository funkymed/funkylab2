<? 		header('Content-Type: text/xml');

echo"<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
?>
<rss version="2.0">
<?
include "admin/config/config.bdd.url.php"; 
include "admin/config/root.config.php"; 
include "admin/include/globale.inc.php";
?><channel>
	<title><? echo utf8_encode($nomsite); ?></title>
	<link><? echo ROOT; ?></link>
	<description><? echo utf8_encode($nomsite); ?></description>
	<language>fr</language>
	<copyright><? echo ROOT; ?></copyright>
	<lastBuildDate><? echo date_rfc822(codeladate()) ?></lastBuildDate>
	<generator>http://www.funkylab.info</generator>
	<docs><? echo ROOT."/rss.php"; ?></docs>
	<ttl>20</ttl>
	<?
	print METADISPLAY("billet","page","texte",20,300);
	
	function METADISPLAY($tableparse,$content,$titre,$max,$maxlength){
		$count=0;			
		$query = "SELECT * FROM $tableparse WHERE type='page' ORDER BY date_debut LIMIT $max";
		$res=mysql_query($query);		
		$buffer="";		
		while ($row = mysql_fetch_array($res)){
			$count+=1;			
			$text=reduitletext(stripslashes($row['nom']),$maxlength);			
			$newsid=$row['id'];
			$newsparent=$row['parent'];			
			$date_debut=$row['date_debut'];
			$date_debut=date_rfc822($date_debut);
			$auteur=recupeall($row['auteur'],"admin","email")." (".recupeall($row['auteur'],"admin","prenom")." ".recupeall($row['auteur'],"admin","nom").")";			
			$category =cheminfer("",$newsparent); 			
			$queryTEXTE = "SELECT * FROM billet WHERE type='texte' AND parent='$newsid'";
			$resTEXTE=mysql_query($queryTEXTE);
			$rowTEXTE = mysql_fetch_array($resTEXTE);
			$description =stripslashes(htmlspecialchars(strip_tags($rowTEXTE['texte'])));			
			$buffer.= "\t<item>\n";
			$buffer.= "\t\t<title>".$text."</title>\n";
			$buffer.= "\t\t<link>".ROOT."index.php?billet=".$newsid."</link>\n";
			$buffer.= "\t\t<guid>".ROOT."index.php?billet=".$newsid."</guid>\n";
			$buffer.= "\t\t<description>".$description."</description>\n";
			$buffer.= "\t\t<pubDate>".$date_debut."</pubDate>\n";
			$buffer.= "\t\t<category>".$category."</category>\n";
			$buffer.= "\t\t<author>".$auteur."</author>\n";
			$buffer.= "\t</item>\n";
		}
		$buffer.= "\r";
		return(utf8_encode($buffer));
	}

	function cheminfer($cheminfer,$id){
		$resFER=mysql_query("SELECT nom,id,parent FROM billet WHERE id='$id'");	
		$rowFER = mysql_fetch_array($resFER);
		$nomFER=stripslashes($rowFER['nom']);
		$idFER=stripslashes($rowFER['id']);
		if ($rowFER['parent']==0){
			$cheminfer.="$nomFER ";					
		}else{
			$cheminfer.=cheminfer($cheminfer,$rowFER['parent'])." / $nomFER";	
		}			
		return($cheminfer);	
	}
	
	function unhtmlentities($chaineHtml) {
	        $tmp = get_html_translation_table(HTML_ENTITIES);
	        $tmp = array_flip ($tmp);
	        $chaineTmp = strtr ($chaineHtml, $tmp);
	
	        return $chaineTmp;
	}
	function date_rfc822($date_heure) {
		$date_heure.=" ".date('H:i:s');
		$date_heure=explode(" ",$date_heure);
        list($annee, $mois, $jour) =explode("-",$date_heure[0]);
        list($heures, $minutes, $secondes) = explode(":",$date_heure[1]);
        $time = mktime($heures, $minutes, $secondes, $mois, $jour, $annee);
        return gmdate("D, d M Y H:i:s +0100", $time);
	}
		
?></channel>
</rss>