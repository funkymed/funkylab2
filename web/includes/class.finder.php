<?
class finder{
	
	function search($searchword){
		$wikibuffer= trim($searchword);
		$searchRESULT="";
		if ($wikibuffer!=""){
			$tab=explode(" ",$wikibuffer);	
			$wiki=$tab[0];	
			$nbword=count($tab);
			$wikiimplode = implode("+", $tab);
			$countresult=0;		
						
			//==================================================================================================
			//	RECHERCHE SUR LES PARAGRAPHES DES BILLETS
			//==================================================================================================
			
			if ($nbword>=2){
				$requete="SELECT * FROM billet WHERE type='texte' AND texte LIKE '%".$tab[0]."%'";
				for ($i = 1; $i <= count($tab)-1; $i++) {
					$requete.=" OR type='texte' AND texte LIKE '%".$tab[$i]."%'";
				}					
				$requete.=" ORDER BY id";				
			}else{				
				$requete="SELECT * FROM billet WHERE type='texte' AND texte LIKE  '%".$wiki."%' ORDER BY id";				
			}		
			
			$resprod=mysql_query($requete);	
			
			while ($row = mysql_fetch_array($resprod)) { 				
				$countresult++;
				$searchRESULT.=finder::getpage($row['parent'],finder::highlight(stripslashes($row['texte']),$wikibuffer));
			}			
			
						
			//==================================================================================================
			//	RECHERCHE DANS LES GALERIES
			//==================================================================================================
			
			
			
			
			$searchRESULT.= "<P align='center'>Vous avez $countresult réponse à votre recherche.</P>";
			
		}else{
			$searchRESULT.= "<P align='center'>Veuillez taper un mot pour votre recherche!</P>";
		}
		
		$searchRESULT="
		<table border=\"0\" >
			<tr>
				<td>
						$searchRESULT
				</td>
			</tr>
		</table>";
		
		return($searchRESULT);
	}

	function getpage($id,$texte){
		$resprod=mysql_query("SELECT * FROM billet WHERE id='$id'");	
		$row = mysql_fetch_array($resprod);
		$pageid=$row['id'];
		$pagenom=$row['nom'];
		
		$buffer="
		<table id=\"souscat\">
			<tr>
				<td>
					<a href=\"index.php?billet=$pageid\">".$row['nom']."</a>
				
					$texte			
				</td>
			</tr>
		</table><br/>
		";
		return($buffer);	
	}

	function highlight($buffer,$word){
		if ($word!=""){
			$couleur = array('highlight1', 'highlight2', 'highlight3', 'highlight4', 'highlight5');
			$countcolor=0;	
			
			$surligne=explode(" ",$word);
			for ($i = 0; $i <= count($surligne)-1; $i++) {
				$wordsurligne=$surligne[$i];
				$newword="<span class='$couleur[$countcolor]'>$wordsurligne</span>";
				$buffer = eregi_replace($wordsurligne,$newword,$buffer);				
				if ($countcolor>=4){$countcolor=0;}else{$countcolor+=1;}
			}	
		}
		return($buffer);
	}	
}
?>