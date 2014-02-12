<?php
/* =================================================================================================
 * G�n�rateur de menu
 * Par Cyril Pereira
 * cyril.pereira@gmail.com
 * =================================================================================================*/

class Generate_Menu{
	

	function javascriptmenu($menuid){
		$btn_id=array();
		$btn_image=array();
		$RESbtn=mysql_query("SELECT id,image,url FROM menu WHERE parent='$menuid' AND type='type_bouton' ORDER BY ordre");				
		while ($ROWbtn = mysql_fetch_array($RESbtn)){
			$btn_id[]=$ROWbtn['id'];
			$btn_image[]=$ROWbtn['image'];
		}	
		
		$RESmenu=mysql_query("SELECT url FROM menu WHERE id='$menuid'");				
		$ROWmenu = mysql_fetch_array($RESmenu);
		
		$optionMENU=explode(",",$ROWmenu['url']);
		switch($optionMENU[0]){
			
			//==================================================================================================
			//	JAVASCRIPT MENU DEROULANT
			//==================================================================================================	
			case "deroulant":
				if($optionMENU[1]=="vertical"){
					$direction="right";
					if ($ROWbtn['url']=="image"){
						$file=ROOT.CURRENT_SKIN."/images/btn_".$btn_image[0]."_off.gif";
						$size = getimagesize($file);		
						$largeurpic=$size[0];
						$hauteurpic=-$size[1];
					}else{
						$largeurpic=50;
						$hauteurpic=-15;
					}
				}else{
					$direction="down";
					$largeurpic=0;
					$hauteurpic=0;
				}
				
				if (count($btn_image)!=0){
					$script="
					<script language=\"javascript\">
						if (TransMenu.isSupported()) {
							TransMenu.updateImgPath('".CURRENT_SKIN."/images/');
							var ms = new TransMenuSet(TransMenu.direction.$direction,$largeurpic,$hauteurpic,TransMenu.reference.bottomLeft);
							TransMenu.subpad_x = 0;
							TransMenu.subpad_y = 0;
						
							document.getElementById(\"menu_$btn_image[0]\").onmouseover = function() {
								ms.hideCurrent();
							}";			
							$nbtn=0;
							for ($AA=0;$AA<=count($btn_image)-1;$AA++){	
								$nbtn++	;
								$script.=Generate_Menu::menulist($btn_id[$AA],$btn_image[$AA],$AA+1);
							}
					
					$script.="				
							document.getElementById(\"menu_".$btn_image[$AA-1]."\").onmouseover = function() {
								ms.hideCurrent();
							}					
							TransMenu.renderAll();
						}
						init1=function(){TransMenu.initialize();}
						if (window.attachEvent) {
							window.attachEvent(\"onload\", init1);
						}else{
							TransMenu.initialize();			
						}
					</script>";	
				}else{
					$script="&nbsp;";
				}
				break;
				
			case "lien":
				$script="&nbsp;";
				break;
	
			case "tableau":
				$script="&nbsp;";
				break;
				
			}
		return($script);		
	}

	function menulist($id,$btn_name,$AA){
		$txtreturn="\r";
		$txtreturn.="\t\t\t\t\tvar tmenu_".$btn_name." = ms.addMenu(document.getElementById(\"menu_".$btn_name."\"));\n";
		$txtreturn.=Generate_Menu::makesub($id,"tmenu_".$btn_name,null,$AA,$id);	
		return($txtreturn);
	}
	
	function makesub($id,$nomparent,$root,$AA,$grandpa){
		
		$sub="";
		$subchild=mysql_query("SELECT * FROM menu WHERE parent='$id' ORDER BY ordre");
		$countITEM=-1;		
		
		while ($rowchild = mysql_fetch_array($subchild)){
			$countITEM+=1;
			$sub.=Generate_Menu::checkTypeJavascript($countITEM,$id,$nomparent,$root,$AA,$grandpa,$rowchild);
		}
		
		return($sub);
	}
	
	function checkTypeJavascript($countITEM,$id,$nomparent,$root,$AA,$grandpa,$rowchild){
			$sub="";			
			$childID=$rowchild['id'];
			$subTITRE=stripslashes($rowchild['nom']);	
			$childNOM=$rowchild['image'];
			$childURL=$rowchild['url'];	
			$childtype=$rowchild['type'];
		
			switch(true){				
				
				case ($childtype=="type_gallerie"):	
					$resGAL=mysql_query("SELECT type FROM type_photo WHERE id='$childURL'");
					$rowGAL = mysql_fetch_array($resGAL);
					$nomGAL=strtolower($rowGAL['type']);
					$varID=explode(",",$childURL);
					$sub.="\t\t\t\t\t$nomparent.addItem(\"".$subTITRE."\", \"index.php?galerie=".$varID[0]."&menuset=$grandpa\", 0, 0);\n";					
					if ($varID[1]==true){
						$sub.="\t\t\t\t\tvar tsub_$nomparent = $nomparent.addMenu($nomparent.items[$countITEM]);\n";						
						$resGALERIE=mysql_query("SELECT type,id FROM type_photo WHERE parent='".$varID[0]."' ORDER by id");						
						while ($rowGALERIE = mysql_fetch_array($resGALERIE)) {	
						    $newChild['id']=99+$countITEM;
						    $newChild['ordre']="0";
						    $newChild['nom']=$rowGALERIE['type'];
						    $newChild['image']=33*$countITEM;
						    $newChild['type']="type_gallerie";
						    $newChild['url']=$rowGALERIE['id'].",0";
						    $newChild['parent']=$childID;						  
							$sub.=Generate_Menu::checkTypeJavascript($countITEM,$childID,"tsub_$nomparent",$root,$AA,$grandpa,$newChild);
						}	
						$sub.="\r";			
					}
					break;
					
				case ($childtype=="type_url"):
					$sub.="\t\t\t\t\t$nomparent.addItem(\"$subTITRE\", \"$childURL\", 0, 0);\n";					
					break;
					
				case ($childtype=="type_submenu"):
					$sub.="\t\t\t\t\t$nomparent.addItem(\"$subTITRE\", \"\", 0, 0);\n";
					$sub.="\t\t\t\t\tvar tsub_$nomparent = $nomparent.addMenu($nomparent.items[$countITEM]);\n";
					$sub.=Generate_Menu::makesub($childID,"tsub_".$nomparent,null,$AA,$id);
					$sub.="\r";
					$sub;
					break;
				
				case ($childtype=="type_contenu"):	
				
					$varID=explode(",",$childURL);
						
					$sub.="\t\t\t\t\t$nomparent.addItem(\"$subTITRE\", \"index.php?billet=".$varID[0]."&menuset=$grandpa\", 0, 0);\n";
				
					if ($varID[1]==true){						
						$sub.="\t\t\t\t\tvar tsub_$nomparent = $nomparent.addMenu($nomparent.items[$countITEM]);\n";	
						$newChild=array();		
						$query="SELECT * FROM billet WHERE parent='".$varID[0]."' ORDER by id";			
						
						$resBILLET=mysql_query($query);
						while ($rowBILLET = mysql_fetch_array($resBILLET)) {	
							if ($rowBILLET['type']=="page" || $rowBILLET['type']=="categorie"){
							    $newChild['id']=99+$countITEM;
							    $newChild['ordre']="0";
							    $newChild['nom']=stripslashes($rowBILLET['nom']);
							    $newChild['image']=33*$countITEM;
							    $newChild['type']="type_contenu";
							    $newChild['url']=$rowBILLET['id'].",0";
							    $newChild['parent']=$childID;						  
								$sub.=Generate_Menu::checkTypeJavascript($countITEM,$childID,"tsub_$nomparent",$root,$AA,$grandpa,$newChild);
							}
						}	
						
						$sub.="\r";
					} 					
					break;
					
				default:
					$sub.="\t\t\t\t\t$nomparent.addItem(\"$subTITRE\", \"$childURL\", 0, 0);\n";
					break;
			}
			return($sub);
	}
	
	
//==================================================================================================
//	HTML 
//==================================================================================================

	function makemenuhtml($parent){
		$RESmenu=mysql_query("SELECT url,id,nom FROM menu WHERE id='$parent'");				
		$ROWmenu = mysql_fetch_array($RESmenu);
		
		$menuidvar=$ROWmenu['id'];
		$menunom=stripslashes($ROWmenu['nom']);
		$optionMENU=explode(",",$ROWmenu['url']);
		
		$optionMENU=explode(",",$ROWmenu['url']);
		switch($optionMENU[0]){
			
			//==================================================================================================
			//	MENU DEROULANT
			//==================================================================================================

			case "deroulant":
				if($optionMENU[1]=="vertical"){
					$brreturn="<br/>\n";
				}else{
					$brreturn="";
				}
				$buffer="
				<div id=\"wrap$menuidvar\">
					<div id=\"$menunom\">				
						";				
						if (isset($_GET['menuset'])){
							$menuset=$_GET['menuset'];
						}else{
							$menuset=99;
						}
						$RESbtn=mysql_query("SELECT * FROM menu WHERE parent='$parent' AND type='type_bouton' ORDER BY ordre");				
						while ($ROWbtn = mysql_fetch_array($RESbtn)){
							$idmenu=$ROWbtn['id'];
							$fileimage=$ROWbtn['image'];
							
							if ($idmenu==$menuset){
								$set="on";
							}else{
								$set="off";
							}

							if ($ROWbtn['url']=="image"){
								$buffer.="<a href=\"#\" id=\"menu_$fileimage\"><img SRC='".CURRENT_SKIN."/images/btn_".$fileimage."_$set.gif' border=0></a>$brreturn";	
							}else{
									$buffer.="<a href=\"#\" id=\"menu_$fileimage\">".stripslashes($ROWbtn['nom'])."</a>&nbsp;&nbsp;$brreturn";	
							}
						}	
						$buffer.="
					</div>
				</div>";
				break;
				
			//==================================================================================================
			//	MENU LIEN
			//==================================================================================================
		
			case "lien":
				$buffer=Generate_Menu::menu_lien($parent,$optionMENU[1]);
				break;
					
			//==================================================================================================
			//	MENU TABLEAU
			//==================================================================================================
	
			case "tableau":
				$buffer=Generate_Menu::menu_tableau($parent,$optionMENU[1]);
				break;
					
			default:
				$buffer="";
				break;
		}
			
		return($buffer);
	}
	
	//----------------------------------------------------------------
	// MENU EN TABLEAU
	//----------------------------------------------------------------
	
	function menu_tableau($menuselect,$option){
		$restype=mysql_query("SELECT * FROM menu WHERE parent='$menuselect'");		
		$select="";
		$buffer="";
				
		$buffer.="		
			<table id=\"menutableau\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td valign=\"top\">";
		
				$ul="";
				while ($row = mysql_fetch_array($restype)) 
				{      	 
					$buffer.=Generate_Menu::menu_tableau_parent($row,$ul,$menuselect,$menuselect,$option);	
					if ($option=="horizontal"){
						$buffer.="</td><td valign=\"top\">";
					}
				}	
					
		$buffer.="</table>";		
		return($buffer);
	}		
	
	function menu_tableau_parent($row,$ul,$parent,$menuselect,$option){		
		$txt="";
		$select="";		
		$id = $row['id'];
		$type =stripslashes($row['nom']);
		$childIMAGE=$row['image'];
		$childURL=$row['url'];	
		$childtype=$row['type'];
		$type = $ul.$type;
		
		if (isset($_GET['menuset'])){
			$menuset=$_GET['menuset'];
		}else{
			$menuset=99;
		}

		if ($id==$menuset){
			echo $id." ON <br/>";
			$set="on";
		}else{
			echo $id." OFF <br/>";
			$set="off";
		}
		
		if ($option=="vertical"){
			switch(true){				
				case ($childtype=="type_gallerie"):	
					$txt.="<tr><td><a href=\"index.php?galerie=$childURL&menuset=$parent\">$type</a></td></tr>";			
					break;
				case ($childtype=="type_url"):
					$txt.="<tr><td><a href=\"$childURL\" target=\"_blank\">$type</a></td></tr>";			
					break;
				case ($childtype=="type_contenu"):	
					$txt.="<tr><td><a href=\"index.php?billet=$childURL&menuset=$parent\">$type</a></td></tr>";			
					break;
				case ($childtype=="type_bouton"):	
					if ($row['url']=="image"){
						$type="<img src=\"".CURRENT_SKIN."/images/btn_".$type."_".$set.".gif\" border=\"0\">";
					}
					
					$txt.="<tr><td class=\"rubrique\">$type</td></tr>";				
					
					break;
				case ($childtype=="type_submenu"):	
					$txt.="<tr><td class=\"subrub\">$type</td></tr>";		
					break;					
			}
		}
		
		if ($option=="horizontal"){
			switch(true){				
				case ($childtype=="type_gallerie"):	
					$type = $ul.$type;
					$txt.="<a href=\"index.php?galerie=$childURL&menuset=$parent\">$type</a><br/>";		
					break;
				case ($childtype=="type_url"):
					
					$txt.="<a href=\"$childURL\" target=\"_blank\">$type</a><br/>";		
					break;
				case ($childtype=="type_contenu"):	
					$type = $ul.$type;
					$txt.="<a href=\"index.php?billet=$childURL&menuset=$parent\">$type</a><br/>";			
					break;
				case ($childtype=="type_bouton"):	
					if ($row['url']=="image"){
						$type="<img src=\"".CURRENT_SKIN."/images/btn_".$type."_".$set.".gif\" border=\"0\">";
					}

					$txt.="$type<br/>";		
					break;
				case ($childtype=="type_submenu"):	
					$txt.="$type<br/>";		
					break;					
			}
		}
		
		$restype=mysql_query("SELECT * FROM menu WHERE parent='$id'");
		$ul.="&nbsp;&nbsp;&nbsp;";
		while ($row = mysql_fetch_array($restype)) 
		{      	 
			$txt.=Generate_Menu::menu_tableau_parent($row,$ul,$id,$menuselect,$option);				
		}	
		
		return($txt);
	
	}

	//----------------------------------------------------------------
	// MENU EN LIEN
	//----------------------------------------------------------------
	
	function menu_lien($menuselect,$option){
		$restype=mysql_query("SELECT * FROM menu WHERE parent='$menuselect'");		
		$select="";
		$buffer="";
			
			$ul="";
			while ($row = mysql_fetch_array($restype)) 
			{      	 
				$buffer.=Generate_Menu::menu_lien_parent($row,$ul,$menuselect,$menuselect,$option);	
				if ($option=="horizontal"){						
					$buffer=substr($buffer,0,strlen($buffer)-2);
					
				}
				if ($option=="vertical"){
					$buffer.="</ul>";
				}
			}	
					
		$buffer.="</table>";		
		return($buffer);
	}		
	
	function menu_lien_parent($row,$ul,$parent,$menuselect,$option){		
		$txt="";
		$select="";		
		$id = $row['id'];
		$type =stripslashes($row['nom']);
		$childIMAGE=$row['image'];
		$childURL=$row['url'];	
		$childtype=$row['type'];
		
		if (isset($_GET['menuset'])){
			$menuset=$_GET['menuset'];
		}else{
			$menuset=99;
		}

		if ($id==$menuset){
			$set="on";
		}else{
			$set="off";
		}
		
		if ($option=="vertical"){
			switch(true){				
				case ($childtype=="type_gallerie"):	
					$txt.="<li><a href=\"index.php?galerie=$childURL&menuset=$parent\">$type</a></li>";		
					break;
				case ($childtype=="type_url"):
					$txt.="<li><a href=\"$childURL\" target=\"_blank\">$type</a></li>";		
					break;
				case ($childtype=="type_contenu"):	
					$txt.="<li><a href=\"index.php?billet=$childURL&menuset=$parent\">$type</a></li>";			
					break;
				case ($childtype=="type_bouton"):	
					if ($row['url']=="image"){
						$type="<img src=\"".CURRENT_SKIN."/images/btn_".$type."_".$set.".gif\" border=\"0\">";
					}
					$txt.="<span class=\"rubrique\">$type </span><ul>";		
					break;
				case ($childtype=="type_submenu"):	
					$txt.="<span class=\"subrub\">$type</span>";		
					break;					
			}
		}
		
		if ($option=="horizontal"){
			switch(true){				
				case ($childtype=="type_gallerie"):	
					$txt.="<a href=\"index.php?galerie=$childURL&menuset=$parent\">$type</a>, ";		
					break;
				case ($childtype=="type_url"):
					$txt.="<a href=\"$childURL\" target=\"_blank\">$type</a>, ";		
					break;
				case ($childtype=="type_contenu"):	
					$txt.="<a href=\"index.php?billet=$childURL&menuset=$parent\">$type</a>, ";			
					break;
				case ($childtype=="type_bouton"):	
					if ($row['url']=="image"){
						$type="<img src=\"".CURRENT_SKIN."/images/btn_".$type."_".$set.".gif\" border=\"0\">";
					}

					$txt.="<span class=\"rubrique\">$type </span> > ";		
					break;
				case ($childtype=="type_submenu"):	
					$txt.="<span class=\"subrub\">$type</span> > ";		
					break;					
			}
		}
		
		$restype=mysql_query("SELECT * FROM menu WHERE parent='$id'");
		
		while ($row = mysql_fetch_array($restype)) 
		{      	 
			$txt.=Generate_Menu::menu_lien_parent($row,$ul,$id,$menuselect,$option);				
		}	
		
		
		return($txt);
	
	}
	
}
	
	
?>