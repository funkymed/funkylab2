<?

	//----------------------------------------------------------------
	// LANGUE RETOUR SERVEUR
	//----------------------------------------------------------------
	function sendNameLangue($langue){
		$langue=explode(";",$langue);
		$langue=explode(",",$langue[0]);		
		
		$langueSERVEUR=array(	
			"fr"  => array(
		      "variable"  => array("fr-fr","fr-be","fr-ca","fr-lu","fr-ch"),
		      "pays"  => array("France","Belgique","Canada","Luxembourg","Suisse"),
		      "langue" => array("Français")
		 ),
			"es"  => array(
		      "variable"  => array("es-ar","es-bo","es-cl","es-co","es-cr","es-sv","es-ec","es-gt","es-hn","es-mx","es-ni","es-pa","es-py","es-pe","es-pr","en-tt","es-uy","es-ve"),
		       "pays"  => array("Argentine","Bolivie","Chilie","Colombie","Costa Rica","El Salvador","Equateur","Guatemala","Honduras","Mexique","Nicaragua","Panama","Paraguay","Pérou","Puerto Rico","Trinidad","Uruguay","Venezuela"),
		       "langue" => array("Espagnol")
		 ),
		 "en"  => array(
		      "variable"  => array("en-za","en-bz","en-bz","en-gb","en-us","en-au"),
		      "pays"  => array("Afrique du sud","Bélize","Grande Bretagne","Amérique","Australie"),
		      "langue" => array("Anglais")
		 ),
		 "pt"  => array(
		      "variable"  => array("pt-br","ar-qa"),
		      "pays"  => array("Brésil","Quatar"),
		      "langue" => array("Portugais")
		 ),
		 "ar"  => array(
		      "variable"  => array("ar-sa","ar-bh","ar-ae","en-au"),
		      "pays"  => array("Arabie Saoudite","Bahreïn","Emirat arabe uni"),
		      "langue" => array("Arabe")
		 ),
		 "de"  => array(
		      "variable"  => array("de-at","de-lu","ar-ae","de-ch"),
		      "pays"  => array("Austrian","Liechtenstein","Luxembourg","Suisse"),
		      "langue" => array("Allemand")
		 ),		     
		);

		$count=0;
		foreach ($langueSERVEUR[$langue[0]]['variable'] as $variable){	
			if($variable==$langue[1]){
			 $pays=$langueSERVEUR[$langue[0]]['pays'][$count];
			}
			$count++;
		}
		
		$langueNAV=$langueSERVEUR[$langue[0]]['langue'];
		$langue=$langueSERVEUR[$langue[0]]['langue'][0];
		$langue_=array(
			"pays"=>$pays,
			"langue"=>$langue,
		);
		return($langue_);
		
	}
	//----------------------------------------------------------------
	// LIST DES LANGUES
	//----------------------------------------------------------------
	
	function listlangue($langue){
		if ($langue==""){
			$langue="Français";
		}
		
		$langue_liste=array(
			'Allemand', 'Anglais','Arabe','Espagnole','Français','Portugais'
		);
		
		
		
		if (strstr($_SERVER['REQUEST_URI'],"/admin")){
			$varonindex="";
		}else{
			$varonindex="onchange=\"submit()\"";
		}
		
		
		$bufferlangue="<select name=\"new_langue\" class=\"listpagecat\" $varonindex>";
				foreach($langue_liste as $langue_){
					if ($langue==$langue_){
						$select="selected";
					}else{
						$select="";
					}
					$bufferlangue.="<option value=\"$langue_\" $select>".$langue_."</option>\n";
				}
		$bufferlangue.="</select>";
		return($bufferlangue);
	}

	//----------------------------------------------------------------
	// ECRITURE DU LOG
	//----------------------------------------------------------------

	function writelog($file,$log,$date){
		if ($date==true){
			$log=date('d/m/Y')." a ".date('H:i:s')." ".$log."\n";
			$file_open=fopen($file, 'a');
			fwrite ($file_open, $log);
		}else{
			$file_open=fopen($file, 'w');
			fwrite ($file_open, $log);
		}
		fclose($file_open);
	}
	
	//----------------------------------------------------------------
	// TAILLE D OCCUPATION DU SITE
	//----------------------------------------------------------------

	function dskspace($dir){
	   $s = stat($dir);
	   $space = $s["size"];
	   if (is_dir($dir))
	   {
	     $dh = opendir($dir);
	     while (($file = readdir($dh)) !== false)
	     
	       if ($file != "." and $file != "..")
	         $space += dskspace($dir."/".$file);
	     closedir($dh);
	   }
	   return $space;
	}


	//----------------------------------------------------------------
	// LIST LES BOUTONS DU TEMPLATE SELECTIONNEE
	//----------------------------------------------------------------
	
	function listbtn($template,$choixactuel){
		$template="../".$template."/images/";
		$buffer="";		
		$dir = opendir($template);
		$count=0;
	
		$buffer.= "<select name='add_image' class='listselect'>\n";		
		while ($f = readdir($dir)) {
			$count+=1;
			if ($count>=3){
				if (strstr($f,"btn_") && strstr($f,"on")){
					$filename=explode("_",$f);
					if ($choixactuel==$filename[1]){
						$buffer.= "<option value='".$filename[1]."' selected=selected> ".$filename[1]."\n";	 
					}else{
						$buffer.= "<option value='".$filename[1]."'> ".$filename[1]."\n";	
					}
				}
			}
	  	}
	  	
	  	echo "</select> ";
		closedir($dir);
		
		return($buffer);
	}
	
	//----------------------------------------------------------------
	// LIST LES REPERTOIRE ET GENERE UNE LIST SELECT
	//----------------------------------------------------------------
	
	function listskin($rep,$name,$choixactuel){
		$buffer="";
		
		$dir = opendir($rep);
		$count=0;
		$buffer.= "<SELECT NAME='$name' class='listselect'>\n";
		$buffer.= "<OPTION VALUE='$choixactuel'> $choixactuel\n";	
		while ($f = readdir($dir)) {
			$count+=1;
			 if(is_dir($rep.$f) && ($count>=3)) {
				if ($choixactuel!=$f){
					$buffer.= "<OPTION VALUE='$f'> $f\n";	
				}
			 }
	  	}
	  	echo "</SELECT> ";
		closedir($dir);
		
		return($buffer);
	}
	
	//----------------------------------------------------------------
	// DECODE UNE DATE TIRET 2006-12-01 ET RENVOIS LA DATE EN FRANCAIS Mercredi 12 decembre 2006
	//----------------------------------------------------------------
	
	function decodedate($date){
		if ($date!="" && $date!="0000-00-00"){
			$dayFrancais=array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
			$dayEnglish=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
			$mois=array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Décembre');
			$explodate=explode("-",$date);
			$theday=date("l", mktime (0,0,0,$explodate[1],$explodate[2],$explodate[0]));
			for ($aa=0;$aa<=7;$aa++){
				if ($dayEnglish[$aa]==$theday){
					break;
				}			
			}			
			if ($explodate[2]==0){
				$newdate=$mois[$explodate[1]-1]." ".$explodate[0];
			}else{
				$newdate=$dayFrancais[$aa]." ".$explodate[2]." ".$mois[$explodate[1]-1]." ".$explodate[0];
			}			
			return($newdate);
		}
	}
	
	//----------------------------------------------------------------
	// VERIFIS SI LE MAIL EST CONFORME coco@yahoo.com
	//----------------------------------------------------------------
	
	function verifmail($email){
	   if (!ereg("[^@]{1,64}@[^@]{1,255}", $email)) {
	    return false;
	  }
	  $email_array = explode("@", $email);
	  $local_array = explode(".", $email_array[0]);
	  for ($i = 0; $i < sizeof($local_array); $i++) {
	     if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
	      return false;
	    }
	  }  
	  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { 
	    $domain_array = explode(".", $email_array[1]);
	    if (sizeof($domain_array) < 2) {
	        return false; 
	    }
	    for ($i = 0; $i < sizeof($domain_array); $i++) {
	      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
	      	return false;
	      }
	    }
	  }
	  return true;
	}
		
	//----------------------------------------------------------------
	// VERIFICATION SI L EMAIL EXISTE DEJA DANS LA BASE EMAIL DE LA NEWSLETTER
	//----------------------------------------------------------------
	
	function checkifexiste($mail){
		$check=mysql_query("SELECT * FROM mail_newsletter WHERE mail='$mail'");
		$checkmail=false;
		while ($row = mysql_fetch_array($check)) { 
			$checkmail=true;
		}
		return($checkmail);		
	}
	
	//----------------------------------------------------------------
	// GENERATION D UN MOT DE PASS
	//----------------------------------------------------------------
	
	function randomPWD() {
		$t= array('1','2','3','4','5','6','7','8','9',
		'a','b','c','d','e','f','g','h');
		$mot="";
		srand((double)microtime()*1000000);
		for ($x=0;$x<6;$x++) {
			$mot.=$t[rand(0,16)] ;
		}
		return $mot;
	}
	
	//----------------------------------------------------------------
	// VERIFIS SI L UTILISATEUR EST ADMIN
	//----------------------------------------------------------------
	
	function testuseradmin(){
		if ($_SESSION["funkylab_admin"]==1){
			return(true);
		}else{		
			return(false);
		}	
	}
	
	//----------------------------------------------------------------
	// VERIFIS SI L UTILISATEUR EST SUPER ADMIN
	//----------------------------------------------------------------
	
	function testusersuperadmin(){
		if ($_SESSION["funkylab_superadmin"]==1){
			return(true);
		}else{		
			return(false);
		}	
	}
	
	//----------------------------------------------------------------
	// VERIFIS SI LA CHECKBOX EST SELECTIONNE ET RENVOIS LE INPUT
	//----------------------------------------------------------------
	
	function checkedornot($name,$value,$checker){
		$buffer="<input type='checkbox' name='$name' value='$value' ";
		if ($checker=="1"){
			$buffer.= "checked=\"checked\">";
		}else{
			$buffer.= ">";
		}
		return($buffer);
	}
	
	//----------------------------------------------------------------
	// DATE DU JOUR COMPATIBLE MYSQL
	//----------------------------------------------------------------
	
	function codeladate(){ return(date("Y-m-d H:i:s"));}
	
	//----------------------------------------------------------------
	// TRANSFORME UNE DATE AVEC SLASHES 01/12/2006 EN TIRET 2006-12-01
	//----------------------------------------------------------------
	
	function dateslash($dateacode){
		$dateexplode=explode("/",$dateacode);
		return($dateexplode[2]."-".$dateexplode[1]."-".$dateexplode[0]);
	}	
	
	//----------------------------------------------------------------
	// TRANSFORME UNE DATE AVEC TIRET 2006-12-01 EN SLASHES 01/12/2006
	//----------------------------------------------------------------
	
	function datetiret($dateacode){
		$dateexplode=explode("-",$dateacode);
		return($dateexplode[2]."/".$dateexplode[1]."/".$dateexplode[0]);
	}
	
	//----------------------------------------------------------------
	// INSERT DANS LE LOG
	//----------------------------------------------------------------
	
	function logaction ($stringlog){
		$stringlog=$stringlog." par ".$_SESSION["funkylab_login"]." le ".date("m/d/Y")." à ".date('H:m:s')."\r\n";
		$datelog=date("m.y");
		$ipcheck="log/$datelog-log.php";
		$workon=fopen($ipcheck,"a");
		fputs($workon,$stringlog);
		fclose($workon);
	}
	
	//----------------------------------------------------------------
	// RENVOIS LE PSEUDO D UN ID DE LA  TABLE ADMIN
	//----------------------------------------------------------------
	
	function recupepseudo($userpost_id){
		$user_res=mysql_query("SELECT * FROM admin WHERE id=$userpost_id");
		while ($user_row = mysql_fetch_array($user_res))
		{  
			return($user_row['login']);		
		}
	}
	
	//----------------------------------------------------------------
	// RENVOIS L ID D UN PSEUDO DE LA TABLE ADMIN
	//----------------------------------------------------------------
	
	function getidfrompseudo($pseudowork){
		$pseudo_res=mysql_query("SELECT * FROM admin WHERE login='$pseudowork'");
		while ($pseudo_row = mysql_fetch_array($pseudo_res))
		{  
			return($pseudo_row['id']);		
		}
	}	
	
	//----------------------------------------------------------------
	// RENVOIS LE NOMBRE D ELEMENT D UN CHAMPS PRECIS  DANS UNE TABLE 
	//----------------------------------------------------------------
	
	function countbase($base,$colonne,$queltype){
		$txt="SELECT * FROM $base WHERE $colonne='$queltype'";
		$res_prod=mysql_query($txt);	
		$nb=0;
		while ($row = mysql_fetch_array($res_prod)){$nb+=1;}
		return($nb);
	}
	
	//----------------------------------------------------------------
	// RENVOIS LE NOMBRE D ENTREE DANS UNE TABLE
	//----------------------------------------------------------------
	
	function allcount($base){
		$txt="SELECT * FROM $base";
		$res_prod=mysql_query($txt);	
		$nb=0;
		while ($row = mysql_fetch_array($res_prod)){$nb+=1;}
		return($nb);
	}
	
	//----------------------------------------------------------------
	// RECUPERATION DE DONNEE PAR ID DANS UNE TABLE
	//----------------------------------------------------------------
	
	
	function recupenomtype($type_id,$base){
		$type_res=mysql_query("SELECT * FROM $base WHERE id='$type_id'");
		$type_row = mysql_fetch_array($type_res);
		return($type_row['type']);		
	}	
	
	//----------------------------------------------------------------
	// RECUPERATION DE DONNEE PAR ID DANS UNE TABLE D UN CHAMPS PRECIS
	//----------------------------------------------------------------
	
	function recupeall($type_id,$base,$retourne){
		$type_res=mysql_query("SELECT * FROM $base WHERE id='$type_id'");
		$type_row = mysql_fetch_array($type_res);
		return(stripslashes($type_row[$retourne]));		
	}		
	
	//----------------------------------------------------------------
	// LIST SELECT
	//----------------------------------------------------------------
	
	function optionlist($base,$idselect,$nomselect){
		$res=mysql_query("SELECT * FROM $base ORDER BY $nomselect");
		echo "\n<SELECT NAME='type_id' class='listselect'>\n
		<OPTION VALUE=''> Selectionnez\n";	
		while ($row = mysql_fetch_array($res)) 
		{      	  
			$id = $row[$idselect];
			$nom = $row[$nomselect];
			echo "<OPTION VALUE='",$id,"'> ",$nom,"\n";
		}
		echo "</SELECT>";
	}	
	//----------------------------------------------------------------
	// MENULIST DE GALERIE	
	//----------------------------------------------------------------
	
	function listypeGALERIE($set){ // MENULIST
		$buffer="";		
		$autorisation=$_SESSION["funkylab_autorisation"];
		if ($autorisation[6]=="1"){
			$idstart=$_SESSION["funkylab_optionadmin"];
			$varsql="id='".$idstart."'";
		}else{
			$varsql="parent='0'";
		}
		
		$restype=mysql_query("SELECT * FROM type_photo WHERE $varsql");		
		$select="";
		$buffer.="		
			<select name='type_id'  class='listpagecat'>
				<option value=\"0\"> Aucun\n";
				$ul="";
				while ($row = mysql_fetch_array($restype)) 
				{      	 
					$buffer.=listparentTYPEGALERIE($row,$ul,$set);			
				}	
				$buffer.="
			</select>";
		
		return($buffer);
	}		

	function listparentTYPEGALERIE($row,$ul,$set){
			
			$txt="";
			$select="";		
			$id = $row['id'];
			$type = $ul.stripslashes($row['type']);
			
			
			if(is_array($set)){
				if (in_array($id,$set)){
					$select="selected=\"selected\"";
				}
			}else{
				if ($id==$set){
					$select="selected=\"selected\"";
				}	
			}		
			switch($row['type']){
				case "page":
					$style="style=\"color:aaaaaa;\"";
					break;
				default:
					$style="";
					break;
			}
			if (isset($_GET['id'])){
				if ($_GET['select']!="ADDTYPEGAL"){
					if ($row['id']==$_GET['id']){
						$select.=" disabled";
					}
				}
			}
			
			$txt.= "\t\t\t\t<option $style value=\"$id\" $select> $type\n";
			if (!strstr($select,"disabled")){
				$restype=mysql_query("SELECT * FROM type_photo WHERE parent='$id'");
				$ul.="&nbsp;&nbsp;&nbsp;&nbsp;";
				while ($row = mysql_fetch_array($restype)) 
				{      	 
					$txt.=listparentTYPEGALERIE($row,$ul,$set);			
				}	
			}
			return($txt);
		
	}
	
	//----------------------------------------------------------------
	// ELEMENTS PRINCIPAL DE L ADMIN
	//----------------------------------------------------------------
	
	function windowscreate($titre,$countprod,$title,$debutfin,$cat){
		
		if ($debutfin=="debut"){
			echo "<BR>
			<TABLE border=0 align='center' class='windcontenu0'><TR><TD>
				<TABLE border=0 width=100% cellspacing=0 cellpadding=0>
					<TR>
						<TD class='wintitle'><IMG SRC='",SKIN,"/images/logocontenu.png' align='middle'>".$titre."</TD>
						<TD align='right'>";
							$autorisation=$_SESSION["funkylab_autorisation"];
							
							if ((($cat==251)&& ($autorisation[6]=="0")) ||($cat==244)){
								echo "<form action='index.php' method='get'>";
								
								if ($cat==244){		
									if(isset($_GET['listgalerie'])){
										$type=$_GET['listgalerie'];
									}else{
										$type=0;
									}
									echo listgalerie($type);
								}else{
									if(isset($_GET['listcategorie'])){
										$type=$_GET['listcategorie'];
									}else{
										$type=0;
									}
									echo listbillet($type);	
								}
														
								echo "<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
								<input type=hidden name=cat value=".$_GET['cat'].">
								<input type=hidden name=list>
								<input type=hidden name=page value=0>
								</form>";
							}
							
							if ($countprod!=null){
								$nb=allcount($countprod);
								$pluriel="";
								if ($nb>=2){
									$pluriel="S";
								}					
								echo $nb." ELEMENT".$pluriel,"<BR>";
								$restype=mysql_query("SELECT * FROM type_".BASE." ORDER BY type");
								$counttype=0;
								$select="";
							}
							
							echo "
						</TD>
						
					</TR>
				</TABLE>";	
		}else{
			echo "</TD></TR></TABLE>";
		}
	}
	
	//----------------------------------------------------------------
	// LIST SELECT DES BILLETS
	//----------------------------------------------------------------
	
	function listbillet_multiple($set,$typebillet="categorie"){ // MULTIPLE
		$buffer="";		
		$restype=mysql_query("SELECT * FROM billet WHERE parent='0' AND type='categorie'");		
		$select="";
		$buffer.="		
			<select Name=\"add_billet_newsletter[]\" size=\"8\" class=\"listpagecat\" multiple>
			<option value=\"0\"> aucun";
				$ul="";
				while ($row = mysql_fetch_array($restype)) 
				{      	 
					$buffer.=listparentbillet($row,$ul,$set,$typebillet);			
				}	
				$buffer.="
			</select>";
		
		return($buffer);
	}
	
	function listbillet($set,$typebillet="categorie"){ // MENULIST
		$buffer="";		
		$autorisation=$_SESSION["funkylab_autorisation"];
		if ($autorisation[6]=="1"){
			$idstart=$_SESSION["funkylab_optionadmin"];
			$varsql="id='".$idstart."'";
		}else{
			$varsql="parent='0'";
		}
		
		$restype=mysql_query("SELECT * FROM billet WHERE $varsql AND type='categorie'");		
		$select="";
		$buffer.="		
			<select name='listcategorie'  class='listpagecat'>
				<option value=\"0\"> Aucun";
				$ul="";
				while ($row = mysql_fetch_array($restype)) 
				{      	 
					$buffer.=listparentbillet($row,$ul,$set,$typebillet);			
				}	
				$buffer.="
			</select>";
		
		return($buffer);
	}		
	
	function listparentbillet($row,$ul,$set,$typebillet){
	
		$renderbillet=false;
		switch($typebillet){
			case "all":
				if (($row['type']=="categorie") || ($row['type']=="page")){		
					$renderbillet=true;
				}
				break;			
			case "categorie":				
				if ($row['type']=="categorie"){		
					$renderbillet=true;
				}
				break;	
			default:
				$renderbillet=false;
				break;			
		}	
		
		if ($renderbillet==true){				
			$txt="";
			$select="";		
			$id = $row['id'];
			$type = $ul.stripslashes($row['nom']);
			
			
			if(is_array($set)){
				if (in_array($id,$set)){
					$select="selected=\"selected\"";
				}
			}else{
				if ($id==$set){
					$select="selected=\"selected\"";
				}	
			}		
			switch($row['type']){
				case "page":
					$style="style=\"color:aaaaaa;\"";
					break;
				default:
					$style="";
					break;
			}
			if ($_GET['cat']!=251){
				if (isset($_GET['id'])){
					if ($_GET['select']!="ADDCAT" && $_GET['select']!="ADDPAGE"){
						if ($row['id']==$_GET['id']){
							$select.=" disabled";
						}
					}
				}
			}
			
			$txt.= "\t\t\t\t<option $style value=\"$id\" $select> ".$type."</option>\n";
			if (!strstr($select,"disabled")){
				$restype=mysql_query("SELECT * FROM billet WHERE parent='$id'");
				$ul.="&nbsp;&nbsp;&nbsp;&nbsp;";
				while ($row = mysql_fetch_array($restype)) 
				{      	 
					$txt.=listparentbillet($row,$ul,$set,$typebillet);			
				}	
			}
			return($txt);
		}
	}
	
	//----------------------------------------------------------------
	// LIST SELECT DES GALERIES
	//----------------------------------------------------------------
	
	function listgalerie($set){
		$buffer="";		
		$restype=mysql_query("SELECT * FROM type_photo WHERE parent='0' ORDER BY type");		
		$select="";
		$buffer.="		
			<select name='listgalerie'  class='listpagecat'>
				<option value=\"ALL\"> Selectionnez une galerie";
				$ul="";
				while ($row = mysql_fetch_array($restype)) 
				{      	 
					$buffer.=listparentgalerie($row,$ul,$set);			
				}	
				$buffer.="
			</select>";
		
		return($buffer);
	}		
	
	function listparentgalerie($row,$ul,$set){
		$txt="";
		$select="";		
		$id = $row['id'];
		$type = $ul.stripslashes($row['type']);
		if ($id==$set){
			$select="SELECTED";
		}
		$txt.= "<option value=\"$id\" $select> ".$type."</option>\n";
		
		$restype=mysql_query("SELECT * FROM type_photo WHERE parent='$id'");
		$ul.="&nbsp;&nbsp;&nbsp;&nbsp;";
		while ($row = mysql_fetch_array($restype)) 
		{      	 
			$txt.=listparentgalerie($row,$ul,$set);			
		}	
		return($txt);
	}
	
	//----------------------------------------------------------------
	// FUNCTION DE REDUCTION DU TEXTE
	//----------------------------------------------------------------
	
	function reduitletext($buffer,$newlenght){
		if (strlen($buffer)>$newlenght){
			$buffer= substr($buffer, 0, $newlenght);			
			$buffer=$buffer." (...)";
		}
		return($buffer);
	}	
	
	//----------------------------------------------------------------
	// VERIFICATION DE L ETAT D UNE BASE
	//----------------------------------------------------------------
	
		function checkbase($base,$cat,$page,$addurl){
		$table = mysql_list_tables($base);
		$req = mysql_query('SHOW TABLE STATUS');
		$message="";
		while($data = mysql_fetch_assoc($req))
		{
		    if($data['Data_free'] > 1024)
		    {		         
			    //echo $data['Data_free'];
			    $message="<P  align='center' class='ALERTEDOUBLON'>Vous devriez optimiser la base
		         <br/>
		         <br/>
		         <a href='index.php?optimisesql=$base&page=$page&cat=".$cat.$addurl."'>Optimiser la base</A></P><BR>";
		    }
		}
		return($message);
	}		
	
	//----------------------------------------------------------------
	// OPTIMISE UNE TABLE DE LA BASE
	//----------------------------------------------------------------
	
	function optimisebase($base){
		$base =$_GET['optimisesql'];
		$table = mysql_list_tables($base);
		$sql = "OPTIMIZE TABLE ";
		
		$req = mysql_query('SHOW TABLE STATUS');
		while($data = mysql_fetch_assoc($req))
		{
			if($data['Data_free'] > 0){
				$sql .= '`'.$data['Name'].'`, ';
			}
		}
		$sql = substr($sql, 0, (strlen($sql)-2));
		mysql_query($sql); 
	}	
	
	//----------------------------------------------------------------
	// LIST SELECT
	//----------------------------------------------------------------
	
	function optionlist_actuel($base,$idselect,$nomselect,$codesup,$order){
		$res=mysql_query("SELECT * FROM $base $codesup ORDER BY $nomselect");		
		echo "\n<SELECT NAME='add_$base' class='listselect'>\n";		
		echo" <OPTION VALUE='0'> AUCUN\n";		
		while ($row = mysql_fetch_array($res)) 
		{      	  
			$addinfo="";
			$select="";			
			$id = $row['id'];
			$nom = stripslashes($row[$nomselect]);				
			if (isset($row['parent'])){
				$parent=$row['parent'];
				if ($parent!=0){
					$addinfo=" / ".recupeall($parent,$base,$nomselect);
				}
			}			
			if ($id==$idselect){
				$select="SELECTED";
			}			
			echo "<OPTION VALUE='",$id,"' $select> ",$nom," $addinfo\n";		
		}		
		echo "</SELECT>";
	}	
	
	//----------------------------------------------------------------
	// LIST SELECT
	//----------------------------------------------------------------
	
	function optionlist_ok($base,$idselect,$nomselect,$idtype){
		$buffer="";
		$res=mysql_query("SELECT * FROM $base");	
		$buffer.= "\n<SELECT NAME='type_id' class='listselect'>\n";
		$buffer.="<OPTION VALUE='0'>AUCUNE\n";
		echo mysql_error();
		while ($row = mysql_fetch_array($res)) 
		{      	  
			$id = $row[$idselect];
			$nom = $row[$nomselect];
			if ($id==$idtype){
				$select="SELECTED";
			}else{
				$select="";				
			}
			$buffer.= "<OPTION VALUE='".$id."' $select> ".$nom."\n";
		}
		$buffer.= "</SELECT>";
		return($buffer);
	}	
	
	//----------------------------------------------------------------
	// VARIABLE UNIQUE / RECUPERATION DU TEMPS
	//----------------------------------------------------------------
	
	function unicvar(){
		$tmonth=date('n');
		$tday=date('d');
		$tyear=date('Y');
		$hour=date('H');
		$min=date('i');
		$sec=date('s');
		return(mktime ($hour,$min,$sec,$tmonth,$tday,$tyear));
	}	
	
?>