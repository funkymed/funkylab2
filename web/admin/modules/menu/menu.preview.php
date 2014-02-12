<?php
	include "../../config/config.bdd.url.php";
	include "../../config/root.config.php";
	include "../../../includes/class.menu.php";
	
	$menuid=$_GET['menu'];
	
	
$buffer="
<html>
	<head>
		<title>menu</title>
		<link rel=\"stylesheet\" href=\"".SKINSITE."/css/theme.css\" type=\"text/css\">\n
		<link rel=\"stylesheet\" type=\"text/css\" href=\"".SKINSITE."/css/transmenuv.css\">
		<script language=\"javascript\" src=\"".ROOT."scripts/transmenu.js\"></script>			
		<script language=\"JavaScript1.2\">		
			function closewin(){
				window.close();
			}
		</script>			
	</head>
	<body>
	";
		
		$buffer.=ereg_replace("template/",ROOT."/template/",Generate_Menu::makemenuhtml($menuid));	
		$buffer.=ereg_replace("template/",ROOT."/template/",Generate_Menu::javascriptmenu($menuid));	
		
	$buffer.="
		<P align='center'><a href='javascript:closewin()'>fermer cette fen�tre</A></p>
	</body>
</html>";

print $buffer;
	
?>