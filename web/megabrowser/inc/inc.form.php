<?php
	if ($admin==false){	
		$buffer0.= "
			<FORM ACTION='$filephp' method='POST' id=\"dialoguebox\" >
				<FIELDSET>
				<LEGEND align='right' ><a href='$filephp'><IMG SRC='$root/megabrowser/template/img/delete.png' border=0></A></LEGEND>
					<LABEL for=\"login\">Login utilisateur </LABEL> 
					<INPUT NAME='login' SIZE=20 height=10 class='btn'> 
					<LABEL for=\"pass\">Mot de passe</LABEL> 
					 <INPUT type='password' NAME='pass' SIZE=20 height=10 class='btn'><BR><BR>
					<INPUT TYPE='submit' VALUE='valider' class='btn' onmouseover=\"className='btnOVER'\" onmouseout=\"className='btn'\">
				</FIELDSET>
			</FORM>	
		";
	}
	
	if (isset($_GET['boxconfigview'])){	
		$buffer0.="
		<FORM ACTION='$filephp' method='GET' id=\"dialoguebox\">\n
			<FIELDSET>
				<LEGEND align='right'><a href='$filephp?dir=$dirset$addurl'><IMG SRC='$root/megabrowser/template/img/delete.png' border=0></A></LEGEND>	
				<LABEL for=\"maxparligne\">Nombre d'image par ligne </LABEL>
					<SELECT NAME='maxparligne' class='btn'>\n";
					
					for ($TT=1;$TT<=6;$TT++){
						$NB=$TT*2;
						$set="";
						if ($NB==$maxparligne){
							$set="SELECTED";
						}
						$buffer0.= "
						<OPTION VALUE='$NB' $set> $NB\n";		
					}
			$buffer0.= "
				</SELECT>
				<INPUT TYPE='hidden' name='dir' VALUE='$dirset'><BR><BR>
				<INPUT TYPE='submit' VALUE='Valider' class='btn' onmouseover=\"className='btnOVER'\" onmouseout=\"className='btn'\">
				</FIELDSET>
		</FORM>
		";
	}
	
	if ($admin==true){	
		if (isset($_GET['boxnewrep'])){	
			$buffer0.= "
				<FORM ACTION='$filephp' method='GET' id=\"dialoguebox\">
					<FIELDSET>
						<LEGEND align='right'><a href='$filephp?dir=$dirset$addurl'><IMG SRC='$root/megabrowser/template/img/delete.png' border=0></A></LEGEND>
						<LABEL for=\"newrep\">Nouveau repertoire</LABEL>
						<INPUT NAME='newrep' SIZE=30  class='textezone'>
						<INPUT TYPE='hidden' name='dir' VALUE='$dirset' >
						<INPUT TYPE='hidden' name='maxparligne' VALUE='$maxparligne'><BR><BR>
						<INPUT TYPE='submit' VALUE='Valider' class='btn' onmouseover=\"className='btnOVER'\" onmouseout=\"className='btn'\">
					</FIELDSET>
				</FORM>";
		}
		
		if (isset($_GET['boxload'])){	
			$buffer0.= "
				<form enctype=\"multipart/form-data\" action=\"$filephp\"  method=\"post\" id=\"dialoguebox\">	
					<fieldset>					
						<LEGEND align='right'><a href='$filephp?dir=$dirset$addurl'><IMG SRC='$root/megabrowser/template/img/delete.png' border=0></A></LEGEND>	
						<LABEL for=\"fichier\">Charger un fichier dans le repertoire courant</label>
						<input name=\"fichier\" type=\"file\">
						<input type=\"hidden\" name=\"posted\" value=\"1\">
						<input type=\"hidden\" name=\"target\" value=\"$dirset\">
						<input type=\"hidden\" name=\"dir\" value=\"$dirset\">						
						<INPUT TYPE=\"hidden\" name=\"maxparligne\" VALUE=\"$maxparligne\"><br/><br/>
						<input type=\"submit\" value=\"Charger\" class='btn' onmouseover=\"className='btnOVER'\" onmouseout=\"className='btn'\">
					</fieldset>
				</form>";
		}
		
		if (isset($_GET['boxgalerie'])){	
			$buffer0.= "
			<form enctype=\"multipart/form-data\" action=\"$filephp\"  method=\"GET\" id=\"dialoguebox\">	
				<fieldset>
					<legend align='right'><a href='$filephp?dir=$dirset$addurl'><img src='$root/megabrowser/template/img/delete.png' border=0></a></legend>	
					".listgalerie()."<br/><br/>
					<input type=\"submit\" value=\"Valider\" class='btn' onmouseover=\"className='btnOVER'\" onmouseout=\"className='btn'\">
					<input type=\"hidden\" name=\"dir\" value=\"$dirset\">
					<input type=\"hidden\" name=\"convertgalerie\" value=\"$dirset\">					
				</fieldset>
			</form>";
		}
	}
	
	$buffer0.= "
	<script LANGUAGE='JavaScript'>
		function open_newpopup(bUrl, bName, bWidth, bHeight, bResize)
		{
			var lar=screen.width/2;
			var hau=screen.height/2;
			var lo=lar-bWidth/2;
			var ho=hau-bHeight/2;
			var newFenetre = window.open(bUrl,bName,'directories=no,location=no,toolbar=no,directories=no,menubar=no,resizable='+bResize+',scrollbars=no,status=no,top='+ho+',left='+lo+',width='+bWidth+',height='+bHeight);
			if (navigator.appVersion.indexOf(\"MSIE 4.0\") < 0) newFenetre.focus();
		}
	</script>	
	";
?>