<?php

class calendrier{
	
	static function display($mois_set,$annee_set,$day_set){
		$buffer="";

		$jourdanslemois=date("t", mktime (0,0,0,$mois_set,1,$annee_set));	
		$jourdudebut=date("l", mktime (0,0,0,$mois_set,1,$annee_set));	
		$numerodujour=date("j");
		$numerodumois=date("m");
		$dayFrancais=array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');
		$dayEnglish=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
		$mois=array('Janvier','F�vrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','D�cembre');
		
	//==================================================================================================
	//	Si le mois est 01 on prevois le mois precedent en 12 avec une ann�e de moins
	//	Si le mois est 12 on prevois le mois prochains en 01 avec une ann�e de plus
	//==================================================================================================

		$prevmonth=$mois_set-1;
		$nextmonth=$mois_set+1;
		$nextyear=$annee_set;
		$prevyear=$annee_set;
		
		if ($mois_set==12){
			$nextyear=$annee_set+1;
			$nextmonth=1;
		}
		
		if ($mois_set==1){
			$prevyear=$annee_set-1;
			$prevmonth=12;
		}	
			
	//==================================================================================================
	//	PATCH MOIS
	//==================================================================================================
	
	if (strlen($nextmonth)<=1){ $nextmonth="0".$nextmonth;}	
	if (strlen($prevmonth)<=1){ $prevmonth="0".$prevmonth;}
	if (strlen($mois_set)<=1){ $mois_set="0".$mois_set;}
			
	//==================================================================================================
	//	EN TETE DU CALENDRIER
	//==================================================================================================
				
		$buffer.="
		<table border=0 align='center' id='calendrier' cellspacing='0' cellpadding='2'>
			<TR>
				<TD colspan=7 align='center' class='cal_mois'>
					<a href='?calendrier&month=$prevmonth&year=$prevyear'><<</a>
					<a href='?calendrier&month=$mois_set&year=$annee_set' class='cal_mois'>".$mois[$mois_set-1]." $annee_set</a>
					<a href='?calendrier&month=$nextmonth&year=$nextyear'>>></a>
				</TD>
			</TR>
			<TR>
				";
				for ($Nday=0;$Nday<=6;$Nday++){		
					$buffer.= "<TD class='day'>&nbsp;".$dayFrancais[$Nday]."&nbsp;</TD>";
				}
				$buffer.="
			</TR>
			<TR>";
		$count=1;
		$countTR=0;
		
	//==================================================================================================
	//	ON TROUVE LE DEBUT DU CALENDRIER
	//==================================================================================================
		
		for ($Nday=1;$Nday<=6;$Nday++){	
			if ($dayEnglish[$Nday]==$jourdudebut){
					$buffer.= "<TD></TD>";		
					break;
			}else{
				$count+=1;
				$buffer.= "<TD></TD>";		
			}
		}
		
	//==================================================================================================
	//	ON AFFICHE LE CALENDRIER
	//==================================================================================================
		
		for ($Nday=1;$Nday<=$jourdanslemois;$Nday++){	
			
			if ($count==7){
				$buffer.="</TR><TR>";
				$countTR+=1;
				$count=0;
			}
			
			if (($Nday==$numerodujour) && ($mois_set==$numerodumois)){
				$class="class='cal_today'";
			}else{
				$class="class='cal_day'";
			}
			
			if (strlen($Nday)<=1){ $dayview="0".$Nday;}else{$dayview=$Nday;} // PATCH JOUR
			
			switch(true){
				case ($day_set==$Nday):
					$buffer.= "<TD class='cal_valide_OVER' align='center'>$Nday</TD>";	
					break;
				case (self::checkjour($Nday,$mois_set,$annee_set)==true):
					$buffer.= "
					<TD class=\"cal_valide\" onmouseover=\"className='cal_valide_OVER'\" onmouseout=\"className='cal_valide'\" valign=\"middle\" align=\"center\">
						<a href='index.php?calendrier&month=$mois_set&year=$annee_set&day=$dayview'>$Nday</A>
					</TD>";	
				break;
					default :
					$buffer.= "<TD $class align='center'>$Nday</TD>";	break;
			}
			
			$count+=1;	
			
		}
		
		if ($countTR==4){
			$buffer.="</TR><TR><TD colspan=7>&nbsp;</TD>";				
		}
		$buffer.= "</TR></TABLE>";	
		
		return($buffer);
	}	
	
	//==================================================================================================
	//	ON VERIFIS SI IL Y A AU MOINS UN BILLET DISPONIBLE DANS LE CALENDRIER
	//==================================================================================================

  static function checkjour($jour,$mois,$annee){
		if (strlen($jour)<=1){ $jour="0".$jour;}
		$datedebut=$annee."-".$mois."-".$jour;
		$today=date("Y-m-d");
		$query="SELECT date_debut FROM billet WHERE date_debut = '$datedebut' AND date_fin <= '$today' AND type='page'";
		$res=mysql_query($query);			
		$count=0;
		while ($row = mysql_fetch_array($res)){	$count++; }		
		if ($count>=1){return(true);}		
		if ($count==0){return(false);}		
		
	}	
	
}
?>