<?php
function create_windows_content($titrewin,$contentwin,$templatewin,$valuemodif,$value){
	
		if (isset($_GET['page'])){
			$page=$_GET['page'];
		}else{
			$page=0;
		}
		
		include "modules/dragwin/fenetre_select_option.php";	
		
		$buffertexte=file_get_contents($templatewin);
		$buffertexte=str_ireplace("#SKINSITE#",ROOT."admin/".SKIN,$buffertexte);
		$buffertexte=str_ireplace("#CAT#",$_GET['cat'],$buffertexte);
		$buffertexte=str_ireplace("#TITREWIN#",$titrewin,$buffertexte);
		$buffertexte=str_ireplace("#COTENUWIN#",$contentwin."&nbsp;",$buffertexte);
		
		$divbuffer="
		<div name=\"wincss\" id=\"wincss\" style=\"
			position:absolute;
			left: 50%;
			top: 20%;
	     	width: 659px;
	     	margin-left: -330px; /* moiti� de la largeur */		
		\">
			$buffertexte
		</div>
		
		<script type=\"text/javascript\">
		<!--
		
		SET_DHTML(\"\", \"wincss\", \"anotherLayer+CURSOR_HAND\", \"lastImage\");

		//-->
		</script>	
		";
		
		return($divbuffer);
}

?>	