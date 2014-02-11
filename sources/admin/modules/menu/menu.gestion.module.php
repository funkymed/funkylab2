<?
	/*********************************************
						MENU
	*********************************************/
	if (isset($_GET['optimisesql'])){
		optimisebase($_GET['optimisesql']);
	}
	

	if (isset($_GET['ADDWORK'])){
		switch($_GET['ADDWORK']){
			/************ ELEMENT DE MENU ***************/
			case "ADDMENU":		
				$parent=$_GET['CATID'];
				$nom=addslashes($_GET['add_nom']);
				$type=$_GET['add_type'];
				$type_var=array('type_submenu','type_info','type_gallerie','type_url');
				switch($type){
					case "type_menu":
						$image="";
						$url=$_GET['typemenu'].",".$_GET['affichage'];
						break;
					case "type_bouton":
						$image=$_GET['add_image'];
						$url=$_GET['btnprincipal'];
						if ($url=="image"){
							$nom=$image;
						}else{
							$image=unicvar();
						}
						break;
					case "type_submenu":
						$image="";
						$url="";
						break;
					case "type_contenu":
						$image=unicvar();
						$url=$_GET['listcategorie'];						
						if (isset($_GET['btn_develop'])){
							$url.=",1";
						}else{
							$url.=",0";
						}						
						break;
					case "type_gallerie":
						$image=unicvar();
						$url=$_GET['listgalerie'];
						if (isset($_GET['btn_develop'])){
							$url.=",1";
						}else{
							$url.=",0";
						}
						break;
					case "type_url":
						$image=unicvar();
						$url=$_GET['add_url'];
						break;
				}
				
				addmenu("","0",$nom,$image,$type,$url,$parent,"ADD");
				break;	
				
			case "MODIFMENU":
				$parent=$_GET['CATID'];
				$nom=addslashes($_GET['add_nom']);
				$type=$_GET['add_type'];
				$ordre=$_GET['ordre'];
				$type_var=array('type_submenu','type_info','type_gallerie','type_url');
				switch($type){
					case "type_menu":
						$image="";
						$url=$_GET['typemenu'].",".$_GET['affichage'];
						break;
					case "type_bouton":
						$image=$_GET['add_image'];
						$url=$_GET['btnprincipal'];
						if ($url=="image"){
							$nom=$image;
						}else{
							$image=unicvar();
						}
						break;
					case "type_submenu":
						$image="";
						$url="";
						break;
					case "type_contenu":
						$image=unicvar();
						$url=$_GET['listcategorie'];						
						if (isset($_GET['btn_develop'])){
							$url.=",1";
						}else{
							$url.=",0";
						}						
						break;
					case "type_gallerie":
						$image=unicvar();
						$url=$_GET['listgalerie'];
						if (isset($_GET['btn_develop'])){
							$url.=",1";
						}else{
							$url.=",0";
						}
						break;
					case "type_url":
						$image=unicvar();
						$url=$_GET['add_url'];
						break;
				}
				
				addmenu("",$ordre,$nom,$image,$type,$url,$parent,$_GET['modifID']);
				break;					
		}
	}
	
	if (isset($_GET['ordrenew'])){
		$id=$_GET['hidepost'];
		$ordrenew=$_GET['ordrenew'];
		$query="UPDATE menu SET ordre='$ordrenew' WHERE id='$id'";
		$result=mysql_query($query);	
	}
	
		
	if (isset($_GET['list'])){
		$autorisation=$_SESSION["funkylab_autorisation"];
		if ($autorisation[3]=="1"){
			menu::menulist();
		}else{
			echo "<P align='center' class='ALERTEDOUBLON'>Vous n'avez pas l'autorisation de l'administrateur pour faire ça.</P>";
		}
	}	
	

	function addmenu($id,$ordre,$nom,$image,$type,$url,$parent,$addmodif){
		$auteur=$_SESSION["funkylab_id"];
		
		if ($addmodif=="ADD"){
			$query="INSERT INTO menu (id,ordre,nom,image,type,url,parent) VALUES 
			('$id','$ordre','$nom','$image','$type','$url','$parent')";	
		}else{
			$query="UPDATE menu SET ordre='$ordre',nom='$nom',image='$image',type='$type',url='$url',parent='$parent' WHERE id='$addmodif'";
		}
		
		$resultat=@mysql_query($query);
		/*
		if ($resultat=="1"){ 
			echo "<P>c bon</P>"; 	
		}else{ 
			echo mysql_error()."<BR><BR>";
			
			echo $query;			
			echo "<P>ERREUR</P>"; 
		}
		*/
	}	
	
?>