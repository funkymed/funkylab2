<?
	if (isset($_GET['select'])){				
				$javax=false;
				$initindex="";
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
				}
				if ($javax==true){
					echo"
					<script type=\"text/javascript\">
						function writediv(texte){
					    	 document.getElementById('minibrowser').innerHTML =file('../minibrowser.php?filter=$extfilter&exttype=$exttype&minibrowserlist='+texte);
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
					$initindex="onload=\"writediv('')\"";
				}
			
	 	}else{
		 	$initindex="";
	 	}
?>