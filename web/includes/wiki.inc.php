<script LANGUAGE="JavaScript">
	function open_newpopup(bUrl, bName, bWidth, bHeight, bResize)
	{
		var lar=screen.width/2;
		var hau=screen.height/2;
		var lo=lar-bWidth/2;
		var ho=hau-bHeight/2;
		var newFenetre = window.open(bUrl,bName,'directories=no,location=no,toolbar=no,directories=no,menubar=no,resizable='+bResize+',scrollbars=no,status=no,top='+ho+',left='+lo+',width='+bWidth+',height='+bHeight);
		if (navigator.appVersion.indexOf("MSIE 4.0") < 0) newFenetre.focus();
	}
</script>

<?php
if (isset($HTTP_GET_VARS['wiki'])){	

	
	$wikibuffer= trim($HTTP_GET_VARS['wiki']);
	
	$tab=explode(" ",$wikibuffer);

	$wikiimplode = implode("+", $tab);
	
	$wiki=$tab[0];

	if (isset($HTTP_GET_VARS['type'])){	
		menusearch($HTTP_GET_VARS['type'],$wikibuffer);
	}else{
		menusearch("",$wikibuffer);
	}


	echo "<P><B>Resultat de votre recherche sur <span class='highlight'>",$wikibuffer,"</span></B></P>";
	
	if (count($tab)<=1){
		$resprod=mysql_query("SELECT * FROM produit WHERE produit_nom LIKE '%$wiki%' OR produit_detail LIKE '%$wiki%' ORDER BY fabricant_id");	
	}else{

		$requete="SELECT * FROM produit WHERE produit_nom LIKE '%".$tab[0]."%'";
		for ($i = 1; $i <= count($tab)-1; $i++) {
			$requete=$requete." AND produit_nom LIKE '%".$tab[$i]."%'";
		}
		
		$requete=$requete." OR produit_detail LIKE '%".$tab[0]."%'";
		for ($i = 1; $i <= count($tab)-1; $i++) {
			$requete=$requete." AND produit_detail LIKE '%".$tab[$i]."%'";
		}
		$requete=$requete." ORDER BY produit_nom";		
		
		$resprod=mysql_query($requete);
	}

	$fabricant_idold=0;
	echo "<TABLE border=0><TR><TD>";

	
	while ($row = mysql_fetch_array($resprod)) { 
		$produit_id=$row['produit_id']; 
		$produit_nom=highlight($row['produit_nom'],$wikiimplode);   	  
		$produit_detail=$row['produit_detail'];    	  
		$type_id=$row['type_id'];    	  
		$produit_pic=$row['produit_pic'];    	  
		$fabricant_id=$row['fabricant_id']; 
 		$type=recupetype($type_id);
 		if ($fabricant_id!=$fabricant_idold)
 		{
	 		echo "</TD></TR></TABLE><BR><TABLE class='tablemenu' width=600><TR><TD><P><a href='index.php?viewprod&fabricant=",$fabricant_id,"&searchfor=",$wikiimplode,"'><span class='BIGFONT'>",recupefab($fabricant_id),"</span></A> <b>[<a href='",urlfab($fabricant_id),"'>www</A>]</B></P>";
 		}
 		echo "<a href='index.php?viewprod=$produit_id&searchfor=",$wikiimplode,"'>",$produit_nom,"</A> / [<a href='index.php?viewprod&searchfor=",$wikiimplode,"&type=",$type_id,"'>",$type,"]</A><BR>";	 	
 		
 		$fabricant_idold=$fabricant_id;
 	}	
	echo "</TD></TR></TABLE><BR>";
}

	if (isset($HTTP_GET_VARS['viewprod'])){
	
		$wikibuffer= trim($HTTP_GET_VARS['searchfor']);
		if (isset($HTTP_GET_VARS['type'])){	
			menusearch($HTTP_GET_VARS['type'],$wikibuffer);
		}else{
			menusearch("",$wikibuffer);
		}		
		
		$viewprod=$HTTP_GET_VARS['viewprod'];
	
		if (isset($HTTP_GET_VARS['fabricant']))
		{
			$fabricant=$HTTP_GET_VARS['fabricant'];
			$resprod=mysql_query("SELECT * FROM produit WHERE fabricant_id='$fabricant' ORDER BY produit_nom");		
		}else{
		
			$resprod=mysql_query("SELECT * FROM produit WHERE produit_id ='$viewprod' ORDER BY produit_nom");		
		}

		if (isset($HTTP_GET_VARS['type'])){
			$findtype=$HTTP_GET_VARS['type'];
			$resprod=mysql_query("SELECT * FROM produit WHERE type_id='$findtype' ORDER BY produit_nom");	
		}

		if (isset($HTTP_GET_VARS['selecttype'])){
			echo getidfromtype($HTTP_GET_VARS['selecttype']);
			$findtype=getidfromtype($HTTP_GET_VARS['selecttype']);			
			//$resprod=mysql_query("SELECT * FROM produit WHERE type_id='$findtype' ORDER BY produit_nom");	
		}

		/********** SEARCH 4 *******/
		
		if (isset($HTTP_GET_VARS['searchfor'])){
			$searchfor=$HTTP_GET_VARS['searchfor'];			
			$fabricant_idold=0;
					
			while ($row = mysql_fetch_array($resprod)) { 				
			
				$produit_id=$row['produit_id']; 
				$produit_nom=highlight($row['produit_nom'],$searchfor); 	  
				$nametoget=$row['produit_nom'];	
						
				if ($searchfor!=""){
					$produit_detail=highlight($row['produit_detail'],$searchfor);				
				}else{
					$produit_detail=nl2br($row['produit_detail']);
				}
				$type_id=$row['type_id'];    	  
				$produit_pic=$row['produit_pic'];    	  
				$fabricant_id=$row['fabricant_id']; 
				$produit_pic=$row['produit_pic']; 
				$type=recupetype($type_id);
				
				$id_user=$row['id_user']; 
			
				$fabname=recupefab($fabricant_id);
			
				$size = getimagesize($produit_pic);
				$wpx=$size[0]+20;
				$hpx=$size[1]+40;		
			
				if ($fabricant_idold!=$fabricant_id){
					echo "<P><a href='index.php?viewprod&fabricant=",$fabricant_id,"&searchfor=",$searchfor,"'><span class='BIGFONT'>",$fabname,"</span></A> <b>[<a href='",urlfab($fabricant_id),"'>www</A>]</B></P>";
				}
				
				echo "
				<TABLE class='tablemenu' width=600>		
					<TR>
						<TD class='titremenu'><a href='index.php?viewprod=",$produit_id,"&searchfor=",$searchfor,"'>",$produit_nom,"</A> (<a href='index.php?viewprod&type=",$type_id,"&searchfor=",$searchfor,"'>",$type,"</A>)</TD>
					</TR>
					<TR>
						<TD><TABLE class='piccadre'  align='right' bgcolor=#FFFFFF cellspacing=0 cellpadding=5><TR><TD>";
							if ($produit_pic!=""){
								echo "<center><a href=\"javascript:open_newpopup('imgview.php?fabname=$fabname&name=",$nametoget,"&pic=",$produit_pic,"','cam',",$wpx,",",$hpx,",'no','no');\"  onmouseover=\"self.status='clickez pour voir en full size'; return true;\"><IMG SRC='thumb/",$produit_pic,"' width=200 border=0 alt='Full Size'></A></center>";								
							}				
							echo "</TD></TR></TABLE>$produit_detail
							
							
							
							
						</TD>
					</TR>
					<TR><TD><P align='right'>par <a href='index.php?membreid=",$id_user,"'>",recupepseudo($id_user),"</A></P></TD></TR>
				</TABLE><BR>
				";
				$fabricant_idold=$fabricant_id;
				
			}
		}	
		
		/********** SEARCH 4 *******/
	}
	
	

	/************************
	*  RECUPERATION TYPE    *
	************************/
	function recupetype($type_id){
		$type_res=mysql_query("SELECT * FROM type WHERE type_id=$type_id");
		while ($type_row = mysql_fetch_array($type_res))
		{  
			return($type_row['type_nom']);		
		}
	}																					
	/************************
	*     URL FABRICANT     *
	************************/
	function urlfab($fab_id){
		$fab_res=mysql_query("SELECT * FROM fabricant WHERE fabricant_id=$fab_id");
		while ($fab_row = mysql_fetch_array($fab_res))
		{  
			return($fab_row['fabricant_url']);		
		}
	}

	/************************
	*RECUPERATION FABRICANT *
	************************/
	function recupefab($fab_id){
		$fab_res=mysql_query("SELECT * FROM fabricant WHERE fabricant_id=$fab_id");
		while ($fab_row = mysql_fetch_array($fab_res))
		{  
			return($fab_row['fabricant_nom']);		
		}
	}
	
	/************************
	*     HIGHLIGHT WORD    *
	************************/
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
		return(nl2br($buffer));
	}	

	/************************
	*           SELECT      *
	************************/
	function makeselectwiki($premier,$valuedefault){
		$res=mysql_query("SELECT * FROM type ORDER BY type_nom");	
		
		echo "\n<SELECT NAME='type' class='tablemenu'>\n
		
		<OPTION VALUE='$valuedefault'> ",$premier,"\n";
		while ($row = mysql_fetch_array($res)) 
		{      	  
			$nom = $row['type_nom'];
			$id = $row['type_id'];
			echo "<OPTION VALUE='",$id,"'> ",$nom," (",countbase("produit","type_id",$id),")\n";
		}
	
		echo "</SELECT>";	
	}
	
	function menusearch($typeset,$textenter){
		echo "<taBLE width=100%><TR>";
		
		echo "<TD align='left'>
		<FORM ACTION='index.php' method='GET'><INPUT class='tablemenu' TYPE='submit' VALUE='Search'>
		<INPUT class='tablemenu' NAME='wiki' SIZE=15 value='$textenter'>
		
		</FORM></TD>";
		
		echo "<TD align='right'> <FORM ACTION='index.php' method='GET'>\n <input type='hidden' name='viewprod'>\n";
		
		if ($typeset!=""){
			makeselectwiki(recupetypename($typeset),$typeset);	
		}else{
			makeselectwiki("Choisissez un genre de produit � lister",$typeset);	
		}
		echo "<INPUT class='tablemenu' TYPE='submit' VALUE='DISPLAY'>\n
		<input type='hidden' name='searchfor'>\n
		
		</FORM></TD></TR></TABLE>";
		
		
	}	
	
	/************************
	*  RECUPERATION TYPE    *
	************************/
	function recupetypename($idtype){

		$user_res=mysql_query("SELECT * FROM type WHERE type_id=$idtype");
		
		while ($type_row = mysql_fetch_array($user_res))
		{  
			return($type_row['type_nom']);		
		}
	}
	
	/************************
	*  count dans base      *
	************************/	
	function countbase($base,$colonne,$queltype){
		$txt="SELECT * FROM $base WHERE $colonne=$queltype";
		$res_prod=mysql_query($txt);	
		$nb=0;
		while ($row = mysql_fetch_array($res_prod)){$nb+=1;}
		return($nb);
	}

	?>	