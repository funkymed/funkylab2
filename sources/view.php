<?
	include "admin/config/config.bdd.url.php";	
	require_once('config.php');
	require_once('admin/config/root.config.php');
	require_once('megabrowser/inc/func.global.php');
?>
<html>
	<head>
		<title>VIEWER</title>	
		<link rel="stylesheet" type="text/css" href="<? echo CURRENT_SKIN; ?>/css/theme.css">
	</head>
	<body bgcolor="#EEEEEE" TOPMARGIN=0 LEFTMARGIN=0 MARGINHEIGHT=0 MARGINWIDTH=0>
		
		<script language="JavaScript1.2">
		function closewin(){
			window.close();
		}
		</script>
		
		<TABLE border=0 height=100% width=100% cellspacing="0" cellpadding="0">
			<TR>
				<TD align='center' valign='middle' >
						<?
						if (isset($_GET['pic'])){
							$pic=$_GET['pic'];
							
							$pic=decodefilename($_GET['pic']);
							$pic=str_replace("///","/",  $pic);		
							$pic=str_replace("//","/",  $pic);
							$pic=str_replace("../","",  $pic);
							
							$ext=strtolower(strrchr($pic, '.'));
							
							switch(TRUE){						
								/************ FICHIER IMAGE ******************/
								case ($ext==".jpg" || $ext==".jpeg" || $ext==".gif" || $ext==".png"):
								
									if ((WATERMARK==true) &&(!isset($_GET['nowatermark']))){
										$image="<IMG SRC=\"image.php?image=$pic&watermark=".WATERMARK_IMAGE."&pos=".WATERMARK_POS."\" border=0>";
									}else{
										$image="<IMG SRC=\"$pic\" border=0>";
									}
								
									echo "<a href='javascript:closewin()'>$image</A>";
									break;
									
								case ($ext==".flv"):
									flvplayer($pic,$root);
									break;
									
								case ($ext==".mov" || $ext==".mpg" || $ext==".mpeg" || $ext==".mid" || $ext==".wav"):
									quicktime($root.$pic);
									break;
										
								case ($ext==".mp3"):
									playerAudio($pic,$root);
									break;
										
								case ($ext==".avi" || $ext==".wmv" ):
										wmvplayer($root.$pic);
										break;		
										
								case ($ext==".txt"):
									$buffer="";
									$dataFile = fopen($pic, "r");
									echo "<P align='left'>";
									if ( $dataFile ){
										while (!feof($dataFile)){
											$buffer .= fgets($dataFile, 4096);
										}
										fclose($dataFile);
									}else{
										die( "fopen failed for $filename" ) ;
									}
       								$buffer=str_replace("\r", "<BR>", $buffer);
       								echo "<TABLE align='center' cellspacing=\"0\" cellpadding=\"10\"><TR><TD>";
									echo $buffer;
									echo "</TD></TR></TABLE>";
									break;
								case ($ext==".zip"):
									include_once('megabrowser/inc/pclzip.lib.php');
									$zip = new PclZip(decoderealfile($pic));
									if (($list = $zip->listContent()) == 0) {
										die("Error : ".$zip->errorInfo(true));
									}
									for ($i=0; $i<sizeof($list); $i++) {
										for(reset($list[$i]); $key = key($list[$i]); next($list[$i])) {
											echo "File $i / [$key] = ".$list[$i][$key]."<br>";
										}
										echo "<br>";
									}
									echo "<P>Genéré avec <a href='http://www.phpconcept.net/pclzip' target='_blank'>PCLZIP</A></P>";
									break;
								default:						
									break;
							}
						}
						?>
				</TD>
			</TR>
			<TR>
				<TD align='center' height='20'>
					<a href='javascript:closewin()'>Fermer la fenetre</A>
				</TD>
			</TR>
		</TABLE>	
	</BODY>
</HTML>