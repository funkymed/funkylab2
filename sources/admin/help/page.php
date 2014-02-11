<? 	session_start();
	include "../config/config.bdd.url.php";
	include "../config/root.config.php";
	/*
	@mysql_select_db(BASE_ADMIN) or die("Impossible de se connecter à la base de données");
	if (isset($_SESSION['funkylab_id'])){
		$skinadmin=recupeall($_SESSION['funkylab_id'],"admin","skinadmin");
	}else{
		$skinadmin="funkylabv2";
	}
	
	define ("SKIN", "template/".$skinadmin);	
	*/
		echo "<HTML>\r";
		echo "<HEAD>\r";
		echo "\t\t<link rel=\"stylesheet\" href=\"../",SKIN,"/css/css.css\" type=\"text/css\">\n";
		echo "\t</HEAD>\r";					
?>
	<body style="
		font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	background: url();
	">

	<?
		if (isset($_GET['id'])){
	
		
			echo "<HEAD>\r";
			echo "\t\t<link rel=\"stylesheet\" href=\"../",SKIN,"/css/css.css\" type=\"text/css\">\n";
			echo "\t</HEAD>\r";
			
			
			echo"
			
	<body style=\"
	
		font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	background: url();
	\">
			
			";
			
			
			$id=$_GET['id'];
			$res_modiftype=mysql_query("SELECT * FROM help WHERE id=$id"); 
			$row = mysql_fetch_array($res_modiftype) ;
			$pagehelp = $row['page'];	
			$titrehelp = $row['titre'];
			echo "\t<BODY>\r";
			echo "\t\t<P class='wintitle'>$titrehelp</P>\r";	
			echo $pagehelp;
			
		}
		?>
		
	</BODY>
</HTML>