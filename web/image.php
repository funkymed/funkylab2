<?php

if (isset($_GET['image'])){
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
	header('Content-Type: image/jpeg');
	header('Content-transfer-encoding: binary');
	
	$filename=explode("/",$_GET['image']);
	$filename=$filename[count($filename)-1];
	 
	header("Content-Disposition: attachment; filename=".$filename);

//==================================================================================================
//	CHARGE LE WATERMARK
//==================================================================================================
		
	$fileWATER=$_GET['watermark'];
	$sizeWATER = getimagesize($fileWATER);
	switch($sizeWATER[2]){
		case 1:
			$crnimage_nw=imagecreatefromgif($fileWATER);
			break;
		case 2:
			$crnimage_nw=imagecreatefromjpeg($fileWATER);
			break;
		case 3:
			$crnimage_nw=imagecreatefrompng($fileWATER);
			break;
	}	
	$crnimage_nw_w = $sizeWATER[0];
	$crnimage_nw_h = $sizeWATER[1];
	imagealphablending($crnimage_nw, true); 

//==================================================================================================
//	CHARGE L IMAGE A MARQUER
//==================================================================================================
	$file=$_GET['image'];
	$size = getimagesize($file);
	switch($size[2]){
		case 1:
			$image=imagecreatefromgif($file);
			break;
		case 2:
			$image=imagecreatefromjpeg($file);
			break;
		case 3:
			$image=imagecreatefrompng($file);
			break;
	}	
	$image_w = $size[0];
	$image_h =$size[1];
	
//==================================================================================================
//	PLACE LE WATERMARK
//==================================================================================================
	//if (testuseradmin()==true){	
		switch($_GET['pos']){
			case "topleft":
				imagecopy($image, $crnimage_nw,0,0,0,0,$crnimage_nw_w, $crnimage_nw_h);	
				break;
			case "topcenter":
				imagecopy($image, $crnimage_nw,($image_w-$crnimage_nw_w)/2,0,0,0,$crnimage_nw_w, $crnimage_nw_h);	
				break;
			case "topright":
				imagecopy($image, $crnimage_nw,$image_w-$crnimage_nw_w,0,0,0,$crnimage_nw_w, $crnimage_nw_h);	
				break;
			case "midleft":	
				imagecopy($image, $crnimage_nw, 0,($image_h-$crnimage_nw_h)/2,0, 0,$crnimage_nw_w, $crnimage_nw_h);	
				break;
			case "midcenter":
				imagecopy($image, $crnimage_nw, ($image_w-$crnimage_nw_w)/2,($image_h-$crnimage_nw_h)/2, 0, 0,$crnimage_nw_w, $crnimage_nw_h);	
				break;
			case "midright":
				imagecopy($image, $crnimage_nw, $image_w-$crnimage_nw_w,($image_h-$crnimage_nw_h)/2,0,0,$crnimage_nw_w, $crnimage_nw_h);		
				break;
			case "botomleft":
				imagecopy($image, $crnimage_nw,0,$image_h-$crnimage_nw_h,0,0,$crnimage_nw_w, $crnimage_nw_h);	
				break;
			case "botomcenter":
				imagecopy($image, $crnimage_nw, ($image_w-$crnimage_nw_w)/2,$image_h-$crnimage_nw_h,0,0,$crnimage_nw_w, $crnimage_nw_h);		
				break;
			case "botomright":
				imagecopy($image, $crnimage_nw, $image_w-$crnimage_nw_w,$image_h-$crnimage_nw_h,0,0,$crnimage_nw_w, $crnimage_nw_h);		
				break;
				
		}	
	//}
	
//==================================================================================================
//	AFFICHE LE RESULTAT
//==================================================================================================
	
	imagejpeg($image, '', 90);
	
//==================================================================================================
//	EFFACE L IMAGE
//==================================================================================================

	imagedestroy($image);
  	imagedestroy($crnimage_nw);
  	echo $image;
}


function testuseradmin(){
	if ($_SESSION["admin"]==1){
		return(true);
	}else{		
		return(false);
	}	
}

?> 