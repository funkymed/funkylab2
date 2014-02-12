<br/>
<TABLE border="0" class="loginbox" align="center" width="500" cellspacing="15" cellpadding="0">
  <TR>
    <TD width=200>
      <?php echo "<center><IMG SRC='",SKIN,"/images/security.png'></center>"; ?>
      <P>Bienvenue dans l'administration du site</P>
      <P>Utilisez un nom d'utilisateur et un mot de passe valides pour obtenir l'acc�s � la console d'administration.</P>
      <P>Si vous avez oubli� votre mot de passe cliquez <a href='index.php?losepass'>ici</A>
    </TD>
    <TD class='titrelogin'>
      <FORM ACTION='index.php' method='POST'>
        <fieldset>
          <legend>Connexion</legend>
          <?php
          if (isset($_POST['email'])){
            $email=$_POST['email'];
            if (verifmail($email)==true){
              $res=mysql_query("SELECT * FROM admin WHERE email='$email'");
              $row = mysql_fetch_array($res);

              if ($email==$row['email']){
                $nom=$row['nom'];
                $prenom=$row['prenom'];
                $newmdp=randomPWD();
                $newspass=md5($newmdp);
                $query="UPDATE admin SET pass='$newspass' WHERE email='$email'";
                $result=mysql_query($query);

                $subject = "Mot de passe oubli�";
                $message = "Vous venez de demander l'envois d'un nouveau mot de passe par l'administration du site.<BR><BR>\n\n
											
											Votre nouveau mot de pass : $newmdp<BR>\n
											A votre prochaine connexion nous vous invitons � le changer.<BR><BR>\n\n
											
											Cordialement,<BR><BR>\n\n
											
											L'Administrateur.
											";

                $name=$nom." ".$prenom;

                $test=mailmdp("Administrateur", "info@sdus.asso.fr",$name,$email,$subject, $message);
                echo $test;
                echo "un nouveau mot de passe � �t� envoy� � ",$_POST['email'];
                /*
                echo "Voici votre nouveau mot de passe : ";
                echo "<P align='center'>",$newmdp,"</P>";
                */
                echo "<P align='center'><a href='index.php'>Retour � l'�cran de connexion</A></P>";
              }else{
                echo "Cet email n'appartient pas � un utilisateur inscris";
              }

            }else{
              echo "Votre email contient des erreurs, veuillez le retaper, cliquez sur RETOUR";
            }
          }else{
            if (isset($_GET['losepass'])){
              echo "<P class='logintxt'>votre email</span><BR>
										<INPUT NAME='email' SIZE=20 height=10 class='loginput'>&nbsp;<INPUT TYPE='submit' VALUE='VALIDER' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
										</P>";
              echo "<P align='center'><a href='index.php'>Retour � l'�cran de connexion</A></P>";
            }else{
              echo "
										<table>
											<tr><td class='logintxt'>Login utilisateur&nbsp;</td><td><INPUT NAME='login' SIZE=20 height=10 class='loginput'></td></tr>
											<tr><td class='logintxt'>Mot de passe&nbsp;</td><td><INPUT type='password' NAME='pass' SIZE=20 height=10 class='loginput'></td></tr>
											<tr><td align='right' colspan=2><INPUT TYPE='submit' VALUE='VALIDER' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\"></td></tr>
										</table>										
										";
            }
          }
          ?>

        </fieldset>
    </td>
  </tr>
</table>

<?php

function mailmdp($fromname, $fromaddress, $toname, $toaddress, $subject, $message){
  $headers  = "MIME-Version: 1.0\n";
  $headers .= "Content-Type: text/html; charset=iso-8859-15\n";
  $headers .= "X-Priority: 3\n";
  $headers .= "X-MSMail-Priority: Normal\n";
  $headers .= "X-Mailer: php\n";
  $headers .= "From: \"".$fromname."\" <".$fromaddress.">\n";
  return mail($toaddress, $subject, $message, $headers);
}


?>
</TD>
</TR>
</TABLE>
</FORM>
</TD>
</TR>
</TABLE>
