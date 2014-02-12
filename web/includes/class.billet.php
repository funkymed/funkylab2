<?php
/* =================================================================================================
 * G�n�rateur de contenu
 * Par Cyril Pereira
 * cyril.pereira@gmail.com
 * =================================================================================================*/

class Generate_Contenu{

//==================================================================================================
//	PAGE
//==================================================================================================	

  static function createpage($pageid,$option){
    if(isset($_SESSION['langue'])){
      $langue=$_SESSION['langue'];
    }else{
      $langue="";
    }
    $restypepage=mysql_query("SELECT * FROM billet WHERE id='$pageid' ");
    $rowtypepage = mysql_fetch_array($restypepage);
    $nom=stripslashes($rowtypepage['nom']);
    $zone=$rowtypepage['zone'];
    $comment=stripslashes($rowtypepage['option_element']);
    $auteur=$rowtypepage['auteur'];
    $nom=recupeall($auteur,"admin","nom");
    $prenom=recupeall($auteur,"admin","prenom");
    $email=recupeall($auteur,"admin","email");
    $auteur="<a href='mailto:$email'>".$nom." ".$prenom."</a>";
    $datesql=$rowtypepage['date_debut'];
    $dateurl=explode("-",$datesql);
    $datedebut="<a href='index.php?calendrier&month=".$dateurl[1]."&year=".$dateurl[0]."&day=".$dateurl[2]."'>".decodedate($datesql)."</a>";
    $nomvarzone="zone";
    $zone1="";
    $zone2="";
    $zone3="";

    //==================================================================================================
    //	VERIFICATION SI LA PAGE EST VISIBLE
    //==================================================================================================

    if ($rowtypepage['visible']==true){
      $dateorverride=Generate_Contenu::check_date_special($pageid);

      //==================================================================================================
      //	VERIFICATION DE LA DATE OVERRIDE
      //==================================================================================================

      if ($dateorverride!=false){

        //==================================================================================================
        //	CREATION DU CONTENU
        //==================================================================================================

        $reselement=mysql_query("SELECT * FROM billet WHERE parent=$pageid AND zone!='0' ORDER by zone,ordre ");
        while ($rowelement = mysql_fetch_array($reselement)){
          $zoneset="zone".$rowelement['zone'];

          switch($rowelement['type']){
            case "imagepage":
              $$zoneset.=Generate_Contenu::imagedisplay($rowelement);
              break;
            case "video":
              $$zoneset.=Generate_Contenu::videodisplay($rowelement);
              break;
            case "texte":
              $$zoneset.=Generate_Contenu::textedisplay($rowelement);
              break;
            case "link":
              $$zoneset.=Generate_Contenu::linkurl($rowelement);
              break;
            case "html":
              $$zoneset.=Generate_Contenu::htmldisplay($rowelement);
              break;
            case "file":
              $$zoneset.=Generate_Contenu::downloadfile($rowelement);
              break;
            case "gallerie":
              $$zoneset.=Generate_Contenu::displaygalerie($rowelement);
              break;
            case "rss":
              $$zoneset.=Generate_Contenu::displayrss($rowelement);
              break;
            case "audio":
              $$zoneset.=Generate_Contenu::displayaudio($rowelement);
              break;
          }
        }

        //==================================================================================================
        //	OPTION D ELEMENT DU BILLET
        //==================================================================================================

        switch($option){

          case "lien":
            for($XX=1;$XX<=3;$XX++){
              $zoneset="zone".$XX;
              $$zoneset="";
              $comment="inactif";
            }
            $zone="1b";
            break;

          case "resume":
            for($XX=1;$XX<=3;$XX++){
              $zoneset="zone".$XX;
              $$zoneset=strip_tags($$zoneset);
              $$zoneset="<p align='justify'>".Generate_Contenu::reduitletext($$zoneset,200)."</p>";
            }
            $zone="1b";
            break;

          default:
            break;
        }

        //==================================================================================================
        //	CREATION DE LA PAGE EN FONCTION DU NOMBRE DE ZONE
        //==================================================================================================
        $option_array=explode(",",$comment);

        if (count($option_array)<3){
          $option_array=$comment.",1,1";
          $option_array=explode(",",$option_array);
        }

        switch($zone){
          case "1b":
            $buffer=Generate_Contenu::page1b($option_array[1],$option_array[2]);
            break;
          case "1b2m":
            $buffer=Generate_Contenu::page1b2m($option_array[1],$option_array[2]);
            break;
          case "1m2b":
            $buffer=Generate_Contenu::page1m2b($option_array[1],$option_array[2]);
            break;
          case "1m2m3m":
            $buffer=Generate_Contenu::page1m2m3m($option_array[1],$option_array[2]);
            break;
          default:
            $buffer=Generate_Contenu::page1b($option_array[1],$option_array[2]);
            break;
        }

        //==================================================================================================
        //	MODIFICATION DU TEMPLATE
        //==================================================================================================
        $nomTITRE=stripslashes($rowtypepage['nom']);
        $query="SELECT nom FROM billet WHERE parent='$pageid' AND option_element='$langue'";
        $resTITRE=mysql_query($query);
        while ($rowTITRE = mysql_fetch_array($resTITRE)){
          $nomTITRE=stripslashes($rowTITRE['nom']);
        }

        $buffer="<div id='contenu'>".$buffer."</div>";
        $buffer=str_ireplace("#TITRE#","<a href='index.php?billet=".$rowtypepage['id']."'>".$nomTITRE."</a>",$buffer);
        $buffer=str_ireplace("#CONTENU1#",$zone1,$buffer);
        $buffer=str_ireplace("#CONTENU2#",$zone2,$buffer);
        $buffer=str_ireplace("#CONTENU3#",$zone3,$buffer);

        //==================================================================================================
        //	FORMATAGE STANDARD
        //==================================================================================================
        if($option_array[3]==true){
          $varid="id=\"tablecontenu\"";
        }else{
          $varid="";
        }

        $buffer="
				<table $varid cellspacing='5'>
					<tr>
						<td align='center' valign='top'>
							$buffer
						</td>
					</tr>
				</table>";


        //==================================================================================================
        //	PATCH CALENDRIER
        //==================================================================================================

        $querystring=explode("&",$_SERVER['QUERY_STRING']);
        if ($querystring[0]=="calendrier"){
          $option=true;
        }

        //==================================================================================================
        //	AFFICHAGE DES INFORMATIONS DES COMMENTAIRES SI ACTIF
        //==================================================================================================

        $commenttxt="le $datedebut par $auteur<br/>";
        if ($option_array[0]=="actif"){
          /********* Compte les commentaires ****************/
          $query="SELECT * FROM comments WHERE billetid='$pageid'";
          $resCOM=mysql_query($query);
          $nb=0;
          while ($rowCOM = mysql_fetch_array($resCOM)){	$nb+=1;	}
          if ($nb>=2){	$pluriel="s";	}else{	$pluriel="";	}
          if ($option==""){
            $buffer.="\r<br/><div id='commentaire'><br/><br/>";
            $buffer.=Generate_Contenu::WRITEcomment($pageid);
            $query="SELECT * FROM comments WHERE billetid='$pageid' ORDER BY id DESC";
            $resCOM=mysql_query($query);
            while ($rowCOM = mysql_fetch_array($resCOM)){
              $commentdate=explode(" ",$rowCOM['commentdate']);
              $pseudo=stripslashes($rowCOM['pseudo']);
              $email=stripslashes($rowCOM['email']);
              $text=stripslashes($rowCOM['text']);

              if ($rowCOM['ip_utilisateur']=="admin"){
                $classadmin="nomdateADMIN";
              }else{
                $classadmin="nomdate";
              }

              $buffer.="
							<fieldset>
								<legend>#$nb</legend>
									<p align='justify' class='textdisp'>$text</p>
									<p align='right' class='$classadmin'>par <a href='mailto:$email'>$pseudo</a> le ".decodedate($commentdate[0])." � ".$commentdate[1]."</p>
							</fieldset>
							<br/>
							";
              $nb--;
            }
            $buffer.="</center></div><br/>";

          }else{
            $commenttxt.="<a href='index.php?billet=".$rowtypepage['id']."'>$nb commentaire$pluriel</a>";
          }
        }else{
          $buffer.="<br/>";
        }

        $buffer=str_ireplace("#INFO#",$commenttxt,$buffer);
        $buffer.="<br/>";

        return($buffer);
      }
    }
  }

//==================================================================================================
//	VERIFICATION DE L EXISTANCE D UNE DATE SPECIAL SI OUI CHANGEMENT DE PRIORITE
//==================================================================================================	
  static function check_date_special($pageid){

    $datedujour=date("Y-m-d");

    $resDATE=mysql_query("SELECT * FROM billet WHERE type='date' AND parent='$pageid'");
    $rowDATE = mysql_fetch_array($resDATE);

    if ($rowDATE){
      $debut=$rowDATE['date_debut'];
      $fin=$rowDATE['date_fin'];

      switch($rowDATE['option_element']){
        case "limite":
          if (($debut<=$datedujour)&&($fin>=$datedujour)){
            return($debut);
          }else{
            return(false);
          }
          break;

        case "start":
          if ($debut<=$datedujour){
            return($debut);
          }else{
            return(false);
          }
          break;
        default :
          if ($debut<=$datedujour){
            return($debut);
          }else{
            return(false);
          }
          break;
      }

    }else{
      return(true);
    }

  }
//==================================================================================================
//	ECRITURE COMMENTAIRE
//==================================================================================================	

  static function WRITEcomment($id){
    $buffercom= "
		<center>
		<form action='index.php' method='GET'>
			<fieldset>
				<legend>Ecrire un commentaire</legend>
				<table border=0 cellspacing='0' cellpadding='2'>
					<tr>
						<td>Votre nom</td><td><input name='add_pseudo' size=30/></td>
					</tr>
					<tr>
						<td>Votre email</td><td><input name='add_mail' size=30/></td>
					</tr>
					<tr>
						<td colspan=2>Votre commentaire<br>
						  	<textArea cols=80 rows=5 name='add_commentaire'></textArea>			
							<p align='center'><input  TYPE='submit' value='ENVOYER' class='btnbox' onmouseover=\"className='btnbox_OVER'\" onmouseout=\"className='btnbox'\"></p>
						</td>
					</tr>
				</table>
			</fieldset>
			<input type='hidden' name='newcomment'>
			<input type='hidden' name='billet' value='$id'>
		</form>
		<br/>
		";
    return($buffercom);
  }
//==================================================================================================
//	AUDIO
//==================================================================================================	

  function displayaudio($row){

    if (strstr($_SERVER['REQUEST_URI'],"admin/")){
      $rooturl="../../../";
    }else{
      $rooturl="";
    }

    $file=$row['texte'];
    $buffer="
		<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"280\" height=\"30\" id=\"mp3player\" align=\"middle\">
		<param name=\"allowScriptAccess\" value=\"sameDomain\" />
		<param name=\"movie\" value=\"".$rooturl."megabrowser/tool/audioPlayer.swf\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#ffffff\" />
		<param name=\"FlashVars\" value=\"&musicUrl=".$file."&autostart=false\" />
		<embed src=\"".$rooturl."megabrowser/tool/audioPlayer.swf\" flashvars=\"&musicUrl=".$file."&autostart=false\" quality=\"high\" wmode=\"transparent\" bgcolor=\"#ffffff\" width=\"280\" height=\"30\" name=\"mp3player\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
		</object>";

    if ($row['option_element']==true){
      $buffer.="<br/><a href=\"$file\" target=\"_top\">T�l�charger la musique</a><br/>";
    }

    return($buffer);

  }
//==================================================================================================
//	RSS
//==================================================================================================	

  function displayrss($row){
    $buffer="";
    $raw="";
    $nbreduit=500;
    $fluxRSS=$row['nom'];
    $file = @fopen($fluxRSS,"r");
    $option=$row['option_element'];
    $option=explode(",",$option);
    $nombre_limite =$option[0];


    if ($file) {
      while (!feof($file)) {
        $raw .= @fread($file,32000);
      }

      @fclose( $file );
      /*
      // TITRE BUG
      if(eregi("<item>(.*)</item>",$raw,$rawitems)){
          $items = explode("<item>", $rawitems[0]);
        eregi("<title>(.*)</title>",$items[0], $title);
        eregi("<link>(.*)</link>",$items[0], $link);

        $link=str_ireplace("<![CDATA[","",$link[1]);
        $link=str_ireplace("]]>","",$link);

        $title=str_ireplace("<![CDATA[","",$title[1]);
        $title=str_ireplace("]]>","",$title);

        $buffer.="<a href=\"".$link."\" target=\"_blank\">".utf8_decode(html_entity_decode($title))."</a><br/>";
      }
      */
      switch($option[1]){
        case "tiresans":
          $desc=false;
          $date=false;
          break;
        case "titreavec":
          $desc=false;
          $date=true;
          break;
        case "titredescsans":
          $desc=true;
          $date=false;
          break;
        case "titredescavec":
          $desc=true;
          $date=true;
          break;
      }

      if(eregi("<item>(.*)</item>",$raw,$rawitems)){
        $items = explode("<item>", $rawitems[0]);
        $nb = count($items);
        $maximum = (($nb-1) < $nombre_limite) ? ($nb-1) : $nombre_limite;
        for ($i=0;$i<$maximum;$i++) {
          eregi("<title>(.*)</title>",$items[$i+1], $title);
          eregi("<link>(.*)</link>",$items[$i+1], $link);
          eregi("<category>(.*)</category>",$items[$i+1], $category);

          if ($desc==true){
            eregi("<description>(.*)</description>",$items[$i+1], $description);
            $desc="<ul>".utf8_decode(html_entity_decode(reduitletext($description[1],$nbreduit)))."</ul>";
            $desc=str_ireplace("<![CDATA[","",$desc);
            $desc=str_ireplace("]]>","",$desc);
          }else{
            $desc="";
          }

          if ($date==true){
            eregi("<pubDate>(.*)</pubDate>",$items[$i+1], $pubDate);
            $time = strtotime($pubDate[1]);
            $pubDate= date('Y-m-d H:i:s', $time);
            $pubDate=explode(" ",$pubDate);
            $pubDate=datetiret($pubDate[0]);
            $date=$pubDate;
          }else{
            $date="";
          }

          $link=str_ireplace("<![CDATA[","",$link[1]);
          $link=str_ireplace("]]>","",$link);

          $title=str_ireplace("<![CDATA[","",$title[1]);
          $title=str_ireplace("]]>","",$title);

          $buffer.= "
					$date <a href=\"".$link."\" target=\"_blank\">".utf8_decode($title)."</a> (".$category[1].")<br>\n$desc";
        }
      }

    }else{
      $buffer="Votre hebergeur ne supporte pas cette fonction.";
    }

    return($buffer);
  }

//==================================================================================================
//	LINK
//==================================================================================================	

  function linkurl($row){
    $nom=stripslashes($row['nom']);
    $option=$row['option_element'];
    $url=$row['texte'];

    $buffer="<p align='left'><a href='$url' target='$option'>$nom</a></p>";
    return($buffer);
  }

//==================================================================================================
//	TEXTE
//==================================================================================================	

  static function textedisplay($row){
    if(isset($_SESSION['langue'])){
      $langue=$_SESSION['langue'];
    }else{
      $langue="";
    }

    $root=ROOT;
    $idtexte=$row['id'];
    $texte=$row['texte'];
    $texte=stripslashes($texte);

    $imagetop="";
    $imagebotom="";
    $titre="";

    switch($row['option_element']){
      case "left":
        $alignset="left";
        break;
      case "right":
        $alignset="right";
        break;
      case "centre":
        $alignset="center";
        break;
      case "justifie":
        $alignset="justify";
        break;
    }

    $restypepage=mysql_query("SELECT * FROM billet WHERE parent=$idtexte order by ordre");
    while ($rowtypetexte = mysql_fetch_array($restypepage)){

      switch($rowtypetexte['type']){
        case "image":

          $optinsup=explode("/",$rowtypetexte['zone']);
          $file=$rowtypetexte['texte'];

          $checkroot=explode("/",$_SERVER['REQUEST_URI']); //QUERY_STRING
          if (in_array("billet",$checkroot)){
            $file="../../../".$file;
            /*
            }else{
              $size = getimagesize($file);
              $file="image.php?image=".$file."&watermark=".WATERMARK_IMAGE."&pos=".WATERMARK_POS;
              */
          }

          $size = getimagesize($file);
          $width=ceil(($size[0]*$optinsup[0])/100);
          $height=ceil(($size[1]*$optinsup[0])/100);

          $taille="width='$width' height='$height' border=0 vspace='10' hspace='10'";

          if ($optinsup[1]=="on"){
            $fullsizeW=$size[0];
            $fullsizeH=$size[1];
            $fullscreen ="<a href=\"javascript:open_newpopup('".$root."view.php?pic=".$rowtypetexte['texte']."','viewimage',$fullsizeW,$fullsizeH,'no','no');\">";
          }

          switch($rowtypetexte['option_element']){
            case "align_haut_gauche":
              $imagetop="<img src='$file' align='left' $taille>";
              break;
            case "align_haut_centre":
              $imagetop="<img src='$file' align='BOTTOM ' $taille>";
              break;
            case "align_haut_droite":
              $imagetop="<img src='$file' align='right' $taille>";
              break;
            case "align_bas_gauche":
              $imagebotom="<img src='$file' align='left' $taille>";
              break;
            case "align_bas_centre":
              $imagebotom="<img src='$file' align='BOTTOM ' $taille>";
              break;
            case "align_bas_droite":
              $imagebotom="<img src='$file' align='right' $taille>";
              break;
          }

          if (isset($fullscreen)){
            $imagetop=$fullscreen.$imagetop."</A>";
            $imagebotom=$fullscreen.$imagebotom."</A>";
          }

          break;

        case "titre":
          $titreTXT=$rowtypetexte['nom'];
          $idtitre=$rowtypetexte['id'];
          $resTITRE=mysql_query("SELECT nom FROM billet WHERE parent='$idtitre' AND option_element='$langue'");
          while ($rowTITRE = mysql_fetch_array($resTITRE)){
            $titreTXT=$rowTITRE['nom'];
          }
          $titre.="<span class='titretexte'>".stripslashes($titreTXT)."</span>";
          break;
      }
    }
    $query="SELECT texte FROM billet WHERE parent='$idtexte' AND option_element='$langue'";
    $resTITRE=mysql_query($query);
    while ($rowTITRE = mysql_fetch_array($resTITRE)){
      $texte=$rowTITRE['texte'];
    }

    $buffer="<p align='$alignset'>".$titre."<BR>".$imagetop.$texte.$imagebotom."</p>";

    $buffer=str_ireplace("<p align='left'>","<p align='$alignset'>",$buffer);
    $buffer=str_ireplace("<p align='center'>","<p align='$alignset'>",$buffer);
    $buffer=str_ireplace("<p align='right'>","<p align='$alignset'>",$buffer);
    $buffer=str_ireplace("<p align='justify'>","<p align='$alignset'>",$buffer);
    $buffer=str_ireplace("<p>","<p align='$alignset'>",$buffer);
    $buffer=str_ireplace("<br>","<br/>\n",$buffer);
    $buffer=str_ireplace("<br/>","<br/>\n",$buffer);
    $buffer=str_ireplace("<br />","<br/>\n",$buffer);
    $buffer=str_ireplace("<strong>","<b>",$buffer);
    $buffer=str_ireplace("</strong>","</b>",$buffer);
    $buffer=str_ireplace("</p>","</p>\r",$buffer);

    return($buffer);
  }
//==================================================================================================
//	HTML
//==================================================================================================	

  static function htmldisplay($row){
    return(stripslashes($row['texte']));
  }
//==================================================================================================
//	FILE
//==================================================================================================	

  static function downloadfile($row){
    $nom=stripslashes($row['nom']);
    $file=$row['texte'];

    $buffer="<p align='center'><a href='".ROOT."$file'>Cliquez pour telecharger $nom</a></p>";

    return($buffer);
  }
//==================================================================================================
//	IMAGE DE PAGE
//==================================================================================================	
  static function imagedisplay($row){
    $root=ROOT;

    $optinsup=explode("/",$row['option_element']);
    $file=$row['texte'];

    $checkroot=explode("/",$_SERVER['REQUEST_URI']); //QUERY_STRING
    if (in_array("billet",$checkroot)){
      $file="../../../".$file;
    }

    $size = getimagesize($file);
    $width=ceil(($size[0]*$optinsup[0])/100);
    $height=ceil(($size[1]*$optinsup[0])/100);

    $taille="width='$width' height='$height' border=0 vspace='10' hspace='10'";

    $image="<img src='$file' $taille>";

    if ($optinsup[1]=="on"){
      $fullsizeW=$size[0];
      $fullsizeH=$size[1];
      $image ="<a href=\"javascript:open_newpopup('".$root."view.php?pic=".$row['texte']."','viewimage',$fullsizeW,$fullsizeH,'no','no');\">$image</a>";
    }

    $image="<p align=\"center\">".$image."</p>";

    return($image);


  }


//==================================================================================================
//	VIDEO
//==================================================================================================	

  static function videodisplay($row){
    $dirset=$row['texte'];
    $ext=strtolower(strrchr($dirset, '.'));
    switch(TRUE){
      case ($ext==".flv"):
        $viewer=Generate_Contenu::flvplayer($dirset);
        break;
      case ($ext==".mov"):
        $viewer=Generate_Contenu::quicktime(ROOT.$dirset);
        break;
      case ($ext==".avi" || $ext==".mpg" || $ext==".mpeg" || $ext==".mid" || $ext==".mp3" || $ext==".wav" || $ext==".wmv" ):
        $viewer=Generate_Contenu::wmvplayer(ROOT.$dirset);
        break;
    }
    if ($row['option_element']=="download"){
      $viewer.="<br/>\n<a href='".ROOT.$dirset."'>Telecharger la video</a>";
    }
    $viewer="<p align='center'>".$viewer."</p>\n";

    return($viewer);
  }

  static function flvplayer($dirset){
    $viewer= "
			<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"320\" height=\"260\" id=\"FLVPlayer\">
			<param name=\"movie\" value=\"".ROOT."/megabrowser/tool/FLVPlayer_Progressive.swf\" />
			<param name=\"salign\" value=\"lt\" />
			<param name=\"quality\" value=\"high\" />
			<param name=\"scale\" value=\"noscale\" />
			<param name=\"FlashVars\" value=\"&MM_ComponentVersion=1&skinName=".ROOT."/megabrowser/tool/Corona_Skin_3&streamName=".ROOT."/$dirset&autoPlay=false&autoRewind=false\" />
			<embed src=\"".ROOT."/megabrowser/tool/FLVPlayer_Progressive.swf\" flashvars=\"&MM_ComponentVersion=1&skinName=".ROOT."/megabrowser/tool/Corona_Skin_3&streamName=".ROOT."/$dirset&autoPlay=false&autoRewind=false\" quality=\"high\" scale=\"noscale\" width=\"320\" height=\"260\" name=\"FLVPlayer\" salign=\"LT\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
			</object>
		";
    return($viewer);
  }
  static function quicktime($video){
    $viewer= "
			<object classid=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" codebase=\"http://www.apple.com/qtactivex/qtplugin.cab\" width=\"320\" height=\"260\">
				<param name=\"src\" value=\"$video\" />
				<param name=\"controller\" value=\"true\" />
				<param name=\"autoplay\" value=\"false\" />
				<object type=\"video/quicktime\" data=\"$video\" width=\"320\" height=\"260\" >
					<param name=\"controller\" value=\"true\">
					<param name=\"autoplay\" value=\"false\">
					Installez le codec quicktime<BR>
					http://www.quicktime.com
				</object>
			</object>
		";
    return($viewer);
  }

  static function wmvplayer($video){
    $viewer= "
			<object id=\"NSPlay\" width=\"320\" height=\"260\" classid=\"clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" codebase=\"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701\" standby=\"Chargement...\" type=\"application/x-oleobject\" align=\"middle\">
			<PARAM NAME=\"AutoStart\" VALUE=\"False\">
			<PARAM NAME=\"FileName\" VALUE=\"$video\">
			<PARAM NAME=\"ShowControls\" VALUE=\"True\">
			<PARAM NAME=\"ShowStatusBar\" VALUE=\"False\">
			<EMBED type=\"application/x-mplayer2\"
				pluginspage=\"http://www.microsoft.com/Windows/MediaPlayer/\"
				SRC=\"$video\"
				name=\"MediaPlayer\"
				width=320
				height=260
				autostart=0
				showcontrols=1
				showdisplay=0
				ShowStatusBar=0>
			</EMBED>
			</object>
		";


    return($viewer);
  }

//==================================================================================================
//	FORMAT PAGE
//==================================================================================================

  static function page1b($signature="",$titre=""){
    $buffer="<table width=100% border=0 cellspacing='0' cellpadding='5'>";
    if ($titre==true){
      $buffer.="<tr><td class='titreTD'><div class='titre'>#TITRE#</div></td></tr>";
    }
    $buffer.="<tr><td class='contenu1'>#CONTENU1#</td></tr>";
    if ($signature==true){
      $buffer.="<tr><td class='infobillet'>#INFO#</td></tr>";
    }
    $buffer.="</table>";
    return($buffer);
  }

  static function page1b2m($signature="",$titre=""){
    $buffer="<table width=100% border=0 cellspacing='0' cellpadding='5'>";

    if ($titre==true){
      $buffer.="<tr><td colspan=\"2\" class='titreTD'><div class='titre'>#TITRE#</div></td></tr>";
    }

    $buffer.="<tr>
			<td width=\"75%\" class=\"contenu1\">#CONTENU1#</td>
			<td width=\"25%\" class=\"contenu2\">#CONTENU2#</td>
		</tr>";

    if ($signature==true){
      $buffer.="<tr><td colspan=\"2\" class='infobillet'>#INFO#</td></tr>";
    }
    $buffer.="</table>";
    return($buffer);
  }

  static function page1m2b($signature="",$titre=""){
    $buffer="<table width=100% border=0 cellspacing='0' cellpadding='5'>";

    if ($titre==true){
      $buffer.="<tr><td colspan=\"2\" class='titreTD'><div class='titre'>#TITRE#</div></td></tr>";
    }

    $buffer.="<tr>
			<td width=\"25%\" class=\"contenu1\">#CONTENU1#</td>
			<td width=\"75%\" class=\"contenu2\">#CONTENU2#</td>
		</tr>";

    if ($signature==true){
      $buffer.="<tr><td colspan=\"2\" class='infobillet'>#INFO#</td></tr>";
    }
    $buffer.="</table>";
    return($buffer);
  }

  static function page1m2m3m($signature="",$titre=""){
    $buffer="<table width=100% border=0 cellspacing='0' cellpadding='5'>";

    if ($titre==true){
      $buffer.="<tr><td colspan=\"3\" class='titreTD'><div class='titre'>#TITRE#</div></td></tr>";
    }

    $buffer.="<tr>
			<td width=\"33%\" class=\"contenu1\">#CONTENU1#</td>
			<td width=\"33%\" class=\"contenu2\">#CONTENU2#</td>
			<td width=\"33%\" class=\"contenu3\">#CONTENU3#</td>
		</tr>";

    if ($signature==true){
      $buffer.="<tr><td colspan=\"3\" class='infobillet'>#INFO#</td></tr>";
    }
    $buffer.="</table>";
    return($buffer);
  }

//==================================================================================================
//	GALERIE
//==================================================================================================
  static function displaygalerie($row){
    //print_r($row);
    $idmenu=$row['id'];
    $galerietype=$row['texte'];
    $nom=stripslashes($row['nom']);

    $option=explode(",",$row['option_element']);
    $nbparligne=$option[0];
    $affichage=$option[2];
    $option=$option[1];

    $buffer="";
    $restype=mysql_query("SELECT * FROM type_photo WHERE id=$galerietype");
    $rowtype = mysql_fetch_array($restype);
    $comment=$rowtype['commentaire'];
    $idgal=$rowtype['id'];
    if ((($affichage=="detail") || ($affichage=="lien")) && ($option!="random") ){
      $buffer.="<p><b><a href='index.php?galerie=$idgal'>$nom</a></b><br>$comment</p>";
    }
    $buffer.="<table width=100% border=0 cellspacing='0' cellpadding='0' class='tabgalerie'>\n<tr>";
    $count=0;
    if ($option=="random"){
      $resgal=@mysql_query("SELECT * FROM photo WHERE type=$galerietype ORDER BY RAND() LIMIT $nbparligne");
    }else{
      $resgal=@mysql_query("SELECT * FROM photo WHERE type=$galerietype order by nom");
    }
    /*
    if ($resgal=="1"){
      echo "<P>c bon</P>";
    }else{
      echo mysql_error()."<BR><BR>";
      echo "<P>ERREUR</P>";
    }
    */

    while ($rowgal = mysql_fetch_array($resgal)){
      $count+=1;
      $nomfile=stripslashes($rowgal['nom']);

      if ($nomfile==""){
        $nomfile="?";
      }

      $idfile=$rowgal['id'];
      $idgaltype=$rowgal['type'];
      $nomgal=Generate_Contenu::reduitletext($nomfile,12);

      $buffer.="
			<td valign='center' align='center'>
				<fieldset class='galerie' onmouseover=\"className='galerie_OVER'\" onmouseout=\"className='galerie'\">";

      if ($affichage=="detail"){
        $buffer.="<legend align='center'>$nomgal</legend>";
      }

      if ($affichage=="lien"){
        $buffer.="<a href='index.php?galerie=$idgaltype&file=$idfile'>$nomfile</a>";
      }

      if (($affichage=="icone") || ($affichage=="detail")){
        $buffer.=Generate_Contenu::fileformat($rowgal['file']);
      }

      if ($affichage=="detail"){
        $buffer.="<br/><a href='index.php?galerie=$idgaltype&file=$idfile'>detail</a>";
      }

      $buffer.="
				</fieldset>
			</td>";

      if ($count>=$nbparligne){
        $count=0;
        $buffer.="</tr></tr>";
      }
    }
    $buffer.="</tr></table>";
    return($buffer);
  }

  static function fileformat($file){
    $infourl=$file;
    $root=ROOT;
    $iconeW=64;
    $iconeH=64;
    $dirbuffer=explode("/",$file);
    $lengeht=strlen($dirbuffer[count($dirbuffer)-1]);

    $dirsetIMAGE=$root.substr($file, 0, strlen($file)-$lengeht);
    $dirset=substr($file, 0, strlen($file)-$lengeht);
    $file=$dirbuffer[count($dirbuffer)-1];

    $checkroot=explode("/",$_SERVER['REQUEST_URI']); //QUERY_STRING

    if (in_array("billet",$checkroot)){
      $dirset="../../../".$dirset;
      $infourl="../../../".$infourl;
    }

    $lenghtname=10;
    $buffer="";

    $ext=strtolower(strrchr($file, '.'));
    $filesizedisp=@number_format((@filesize($dirsetIMAGE.$file)/1024),2);

    $filesize=@filesize($dirsetIMAGE.$file)/1024;

    $fileurlencode=Generate_Contenu::codefilename(urlencode($file));

    switch(TRUE){

      /************ FICHIER IMAGE ******************/
      case ($ext==".jpg" || $ext==".jpeg" || $ext==".gif" || $ext==".png"):
        $size = getimagesize($infourl);
        $width=$size[0];
        $height=$size[1];
        $wpx=$size[0];
        $hpx=$size[1];

        $buffer.= "
				<a href=\"javascript:open_newpopup('".$root."view.php?pic=".$dirset.$fileurlencode."','viewimage',$wpx,$hpx,'no','no');\">		
				<IMG SRC='$dirsetIMAGE/thumb_".Generate_Contenu::decodefilename(urlencode($file))."' border=0 alt='$file' title='$file' $width px / $height px'><br>
				</a>";

        break;

      /************ FICHIER AUDIO ******************/
      case ($ext==".ogg" || $ext==".mp3" || $ext==".mod" || $ext==".xm" || $ext==".mid" || $ext==".s3m" || $ext==".wav"):
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\">
				<IMG SRC='$root/megabrowser/template/img/audioOGG.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;

      /************ FICHIER VIDEO ******************/

      case ($ext==".avi" || $ext==".mpg" || $ext==".mpeg" || $ext==".wmv"):
        $buffer.= "<a href=\"javascript:open_newpopup('".$root."view.php?pic=".$dirset.$fileurlencode."','viewimage',360,320,'no','no');\">
				<IMG SRC='$root/megabrowser/template/img/mediaplayer.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;
      case ($ext==".flv"):
        $buffer.= "<a href=\"javascript:open_newpopup('".$root."view.php?pic=".$dirset.$fileurlencode."','viewimage',360,320,'yes','yes');\"><IMG SRC='$root/megabrowser/template/img/videoFLV.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;
      case ($ext==".mov"):
        $buffer.= "<a href=\"javascript:open_newpopup('".$root."view.php?pic=".$dirset.$fileurlencode."','viewimage',360,320,'no','no');\"><IMG SRC='$root/megabrowser/template/img/videoMOV.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;
      case ($ext==".rm" || $ext==".ram"):
        $buffer.= "<a href=\"javascript:open_newpopup('".$root."view.php?pic=".$dirset.$fileurlencode."','viewimage',360,320,'no','no');\"><IMG SRC='$root/megabrowser/template/img/videoRM.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;

      /************ FICHIER TEXTE ******************/
      case ($ext==".txt"):
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'>
				<IMG SRC='$root/megabrowser/template/img/txt.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;
      case ($ext==".html" || $ext==".htm"):
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'>
				<IMG SRC='$root/megabrowser/template/img/html.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;
      case ($ext==".swf"):
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'>
				<IMG SRC='$root/megabrowser/template/img/swf.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;

      case ($ext==".doc"):
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'>
				<IMG SRC='$root/megabrowser/template/img/word.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;

      case ($ext==".xls"):
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'>
				<IMG SRC='$root/megabrowser/template/img/excel.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;

      case ($ext==".pdf"):
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'>
				<IMG SRC='$root/megabrowser/template/img/pdf.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>";
        break;

      /************ ZIP ******************/
      case ($ext==".zip"):
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'>
				<IMG SRC='$root/megabrowser/template/img/zip.png' width='$iconeW' height='$iconeH' border=0 alt='$file' title='$file'><br>
				</a>				
				";
        break;

      /************ INCONNU ******************/
      default:
        $buffer.= "<a href=\"".$dirset.$fileurlencode."\">
				".reduitletext($file,$lenghtname);
        break;
    }

    return($buffer);
  }

  static function reduitletext($buffer,$newlenght){
    if (strlen($buffer)>$newlenght){
      $buffer= substr($buffer, 0, $newlenght);
      $buffer=$buffer." (...)";
    }
    return($buffer);
  }

  static function codefilename($file){
    $file=str_ireplace("&", ";et", $file);
    $file=str_ireplace("%2C", ";virgule", $file);
    $file=str_ireplace("%26", ";et", $file);
    $file=str_ireplace("+", ";plus", $file);
    $file=str_ireplace("%82", ";chelou1", $file);
    $file=str_ireplace("%8A", ";chelou2", $file);
    return($file);
  }

  static function decodedirname($file){
    $file=str_ireplace(";et", "&", $file);
    $file=str_ireplace("%26", "&", $file);
    $file=str_ireplace(";plus", " ", $file);
    return($file);
  }

  static function decodefilename($file){
    $file=str_ireplace("+", "%20", $file);
    $file=str_ireplace(";plus", "%20", $file);
    $file=str_ireplace(";virgule","%2C",  $file);
    $file=str_ireplace("&", "%26", $file);
    $file=str_ireplace(";et", "&", $file);
    return($file);
  }

  static function decoderealfile($file){
    $file=str_ireplace("%20", " ", $file);
    $file=str_ireplace("%26", "&", $file);
    $file=str_ireplace("%2C", ",", $file);
    return($file);
  }

  static function display_file($id){

    $res=mysql_query("SELECT * FROM photo WHERE id = '".$id."'");
    $row = mysql_fetch_array($res);
    $idactuel = $row['id'];
    $nom = stripslashes($row['nom']);
    $file = $row['file'];
    $desc = stripslashes($row['description']);
    $type = "<a href=\"index.php?galerie=".$row['type']."\" target=\"_top\">".recupeall($row['type'],"type_photo","type")."</a>";
    $prix = $row['prix'];
    $tailleX = $row['tailleX'];
    $tailleY = $row['tailleY'];
    $mots = $row['motscle'];
    $date = $row['date'];
    $pays = $row['pays'];
    $reso = $row['resolution'];
    $plimus = $row['plimus'];

    $buffer="
		<table border=\"0\" width=\"100%\" id=\"contenu\" >
			<tr>
				<td class=\"galerie\" align=\"center\" colspan=\"2\">
					".Generate_Contenu::fileformat($file)."<br/>cliquez pour voir ou t�l�charger le fichier
				</td>
			</tr>
			<tr>
				<td valign=\"top\">
					<b>Nom :</b> $nom<br/>
					<b>Galerie : </b> $type<br/>
					<b>Description :</b> $desc<br/>
					<b>Mots clef :</b> $mots
				</td>
				<td width=\"50%\" valign=\"top\"><b>Date et lieu :</b> $date $pays<br/>
					<b>Largeur :</b> $tailleX <br/>
					<b>Hauteur :</b> $tailleY	<br/>
					<b>Resolution :</b> $reso<br/>
				</td>
			</tr>
		</table>		
		";

    return($buffer);


  }

}

?>