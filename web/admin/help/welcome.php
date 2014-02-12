<?php 	session_start();
	include "../config/config.bdd.url.php";
	include "../config/root.config.php";
	/*
	@mysql_select_db(BASE_ADMIN) or die("Impossible de se connecter � la base de donn�es");
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

		<P class='wintitle'>AIDE</P>
		<BR>
		<P>Vous trouverez dans ces pages quelques aides qui j'�sp�re vous aiderons � trouver une solution a vos problemes dans l'admin.</P>

		<p  style="font:15pt,Arial,solid;"><u><b>SOMMAIRE</b></u></p>

    <?php

	$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=0 ORDER BY titre");
			echo "<ul>";
		    while ($row = mysql_fetch_array($res_modiftype)){
			    $id= $row['id'];
			    $titrehelp = $row['titre'];
			    echo "<a href='page.php?id=$id' target='frame' style=\"font:15pt,Arial,solid;\">$titrehelp</A><BR>\n";
			    echo"<ul>";
		    		childtype($id);
		    	echo "</ul><br/>";
	    	}
	    	echo "</ul>";


	function childtype($id){
		$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=$id ORDER BY titre");
		while ($row_child = mysql_fetch_array($res_modiftype)){
			$id= $row_child['id'];
			$titrehelp = stripslashes($row_child['titre']);
			if (trim(stripslashes($row_child['page']))==""){
			 echo "<a href='page.php?id=$id' target='frame'>$titrehelp</A><BR>\n";
			 echo "<ul>";
		    	childtype($id);
		    	echo "</ul>";
	    	}else{
				echo "<a href='page.php?id=$id' target='frame'>$titrehelp</A><BR>\n";
			}

		}
	}
?>
		<P>Si malgr�s tout vous ne trouvez pas de solution contactez moi : <a href='mailto:cyril.pereira@gmail.com'>cyril.pereira@gmail.com</A></P>

	</BODY>
</HTML>