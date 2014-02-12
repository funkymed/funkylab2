<?php
			
			echo"
			<script type=\"text/javascript\">
				function writeMenuListBillet(varID){
			    	 document.getElementById('menuListBillet').innerHTML =file('modules/billet/billetderoulant.php?id='+varID);
			     }
			     
			     function writeMenuListMenu(varID){
			    	 document.getElementById('menuListMenu').innerHTML =file('modules/menu/menuderoulant.php?id='+varID);
			     }    
			
				function file(fichier){
					if(window.XMLHttpRequest) // FIREFOX
					  xhr_object = new XMLHttpRequest();
					else if(window.ActiveXObject) // IE
					  xhr_object = new ActiveXObject(\"Microsoft.XMLHTTP\");
					else
					  return(false);
					xhr_object.open(\"GET\", fichier, false);
					xhr_object.send(null);
					if(xhr_object.readyState == 4) return(xhr_object.responseText);
					else return(false);
			     }
			</script>
			";
			
			if (isset($_GET['cat'])){
				if (($_GET['cat']==254) || ($_GET['cat']==256)){
					$cat254=true;
				}
			}
			
			if (isset($cat254) || ((isset($_GET['select']) && ($_GET['cat']==243)) || (isset($_GET['GALERIE']) || (isset($_GET['select']) && ($_GET['cat']==244))))){				
					$javax=false;
					$initindex="";
					if (isset($_GET['select'])) {
						switch($_GET['select']){
							case "ADDIMAGE":					
								$extfilter="true";
								$exttype="image";
								$javax=true;
								break;									
							case "ADDFILE":					
								$extfilter="false";
								$exttype="image";
								$javax=true;
								break;
							case "ADDVIDEO":					
								$extfilter="true";
								$exttype="video";
								$javax=true;
								break;
							case "ADDIMAGEPAGE":
								$extfilter="true";
								$exttype="image";
								$javax=true;
								break;	
							case "ADDELEMENTGAL":					
								$extfilter="false";
								$exttype="image";
								$javax=true;
								break;
							case "ADDAUDIO":					
								$extfilter="true";
								$exttype="audio";
								$javax=true;
								break;
							case "1":
								
								if (isset($_GET['id'])){
									$id=$_GET['id'];
									if ($_GET['cat']==256){
										$id=$_GET['id'];
										$restypepage=mysql_query("SELECT image FROM bannieres WHERE id=$id");
										$rowtypepage = mysql_fetch_array($restypepage);			
										$file = $rowtypepage['image'];
									}
									if ($_GET['cat']==244){
										$extfilter="false";
										$exttype="image";
										$javax=true;
										$restypepage=mysql_query("SELECT * FROM photo WHERE id=$id");
										$rowtypepage = mysql_fetch_array($restypepage);			
										$file = $rowtypepage['file'];
									}							
									
									if ($_GET['cat']==243){
										$restypepage=mysql_query("SELECT * FROM billet WHERE id=$id");
										$rowtypepage = mysql_fetch_array($restypepage);			
										$type = $rowtypepage['type'];	
										$file = $rowtypepage['texte'];
										switch($type){
											case "video":
												$extfilter="true";
												$exttype="video";
												$javax=true;
												break;	
											case "imagepage":				
												$extfilter="true";
												$exttype="image";
												$javax=true;
												break;
											case "file":					
												$extfilter="false";
												$exttype="image";
												$javax=true;
												break;		
											case "image":					
												$extfilter="true";
												$exttype="image";
												$javax=true;
												break;
											case "audio":					
												$extfilter="true";
												$exttype="audio";
												$javax=true;
												break;
											default:
												$file="";
												$javax=false;
												break;
										}
									}
								}else{
									$javax=false;
								}
								break;
						}
						
						if ($_GET['select']==1){
							$initindex="onload=\"writediv('$file')\"";
						}else{
							$initindex="onload=\"writediv('')\"";
						}
						
					}
					
					if ($_GET['cat']==254){
						$extfilter="true";
						$exttype="image";
						$javax=true;
						$restypepage=mysql_query("SELECT * FROM config WHERE id=0");
						$rowtypepage = mysql_fetch_array($restypepage);			
						$file = $rowtypepage['watermark_image'];
						$initindex="onload=\"writediv('$file')\"";
					}	
					
								
					if ($_GET['cat']==256){
						$extfilter="true";
						$exttype="image";
						$javax=true;

					}
					
					if ($javax==true){
						echo"
						<script type=\"text/javascript\">
							function writediv(texte){
						    	 document.getElementById('minibrowser').innerHTML =file('../minibrowser.php?filter=$extfilter&exttype=$exttype&minibrowserlist='+texte);
						     }  
						</script>
						";
					}
		 	}else{
			 	$initindex="";
		 	}
	 	?>