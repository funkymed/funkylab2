<?	
$autorisation_session_check = $_SESSION["funkylab_autorisation"];	
$menu_buffer="
var myMenu =
[
	[null,'Accueil','index.php?cat=240',null,'accueil'],
	_cmSplit,";
	

//==================================================================================================
//	ADMINISTRATION
//==================================================================================================
/*
	if (testusersuperadmin()==true){
		$menu_buffer.= "
		[null,'Hebergement',null,null,'Gestion des sitesweb',
			[null,'Gestion des Sites','index.php?cat=257&list',null,'Configuration'],
		],	
		_cmSplit,		
		";
	}
*/
	
	if (testuseradmin()==true){
		$menu_buffer.= "
		[null,'Administration',null,null,'Gestion des utilisateurs',
			[null,'Configuration du SiteWeb','index.php?cat=254',null,'Configuration'],
			
			[null,'Gestion des Utilisateurs','index.php?cat=255&list',null,'Configuration'],
			[null,'Gestion des Templates','index.php?cat=259&list',null,'Configuration'],
			[null,'Gestion des patchs','index.php?cat=239&list',null,'Configuration'],
			[null,'Base de données','myBackup.php','_blank','BDD'],
		],	
		_cmSplit,
		
		";
	}

//==================================================================================================
//	COMPTE UTILISATEUR
//==================================================================================================

	$menu_buffer.= "
	
	[null,'Mon compte',null,null,'Gestion des utilisateurs',
		[null,'Modifier mon compte','index.php?cat=255&select=1&id=".$_SESSION["funkylab_id"]."',null,'vos infos'],		
		[null,'Messagerie interne','index.php?cat=253&list',null,'Messages'],
		
		<!--_cmSplit,[null,'Agenda','index.php?cat=252&next',null,'Messages'],-->
	],	
	_cmSplit,	
	";
	
//==================================================================================================
//	CONTENU
//==================================================================================================

		if (in_array("1",$autorisation_session_check)){	
			$menu_buffer.= "[null,'Contenu',null,null,'Contenu',";
		}
		
		if ($autorisation_session_check[3]=="1"){
			$menu_buffer.= "[null,'Organisation du menu','index.php?cat=247&list',null,'Menu'],";
		}
		
		if ($autorisation_session_check[1]=="1"){
			$menu_buffer.=  "[null,'Billet','index.php?cat=243&list&order=TOUT&orderby=id&page=0',null,'Billet'],";
		}else{		
			if ($autorisation_session_check[6]=="1"){
				$menu_buffer.=  "[null,'Gérer votre espace internet','index.php?cat=243&list&order=TOUT&orderby=id&page=0',null,'Billet'],";
			}	
		}	
		
		if ($autorisation_session_check[2]=="1"){
			$menu_buffer.= "
			[null,'Galerie',null,null,'Galerie',
				[null,'Gestion des galeries','index.php?cat=245&list',null,'Categories'],			
				[null,'Elements de galerie','index.php?cat=244&list&listcategorie=ALL',null,'Elements de Categories'],
			],";
		}

		if ($autorisation_session_check[5]=="1"){
			$menu_buffer.=  "
			[null,'Commentaires',null,null,'Commentaires',
				[null,'Commentaires','index.php?cat=242&list&page=0',null,'Commentaires'],
				[null,'BlackList','index.php?cat=241&list',null,'BlackList'],
			],							
			";		
		}
		
		if ($autorisation_session_check[0]=="1"){
			$menu_buffer.=  "
			[null,'Newsletter',null,null,'Newsletter',
				[null,'Contact / Email','index.php?cat=252&listmail',null,'Newsletter'],
				[null,'Newsletter','index.php?cat=251&listletter',null,'Newsletter'],
			],							
		";
		}	
		
		if ($autorisation_session_check[7]=="1"){
			$menu_buffer.=  "
			[null,'Bandeau de pub','index.php?cat=256&list',null,'BDPUB'],							
		";
		}	
				
		if (in_array("1",$autorisation_session_check)){	
			$menu_buffer.=  "],
			_cmSplit,";
		}
		
	
//==================================================================================================
//	A PROPOS & DOCUMENTATION
//==================================================================================================
 
$menu_buffer.= "
	
	[null,'?',null,null,'Aide',
		[null,'Documentation','index.php?help',null,'Aide'],
		[null,'A propos','index.php?apropos',null,'info'],
	],
						  							  			  			
];
cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
";
print $menu_buffer;
?>