<?php
if(isset($_GET['page']))
{
?>

<p class="titrePage">Nous rejoindre</p>
<div class="clean"></div><br><br>
<center><img src="./images/rejoin/config.png" /></center><br><br>
<p>Pour nous rejoindre, il suffit de telecharger le client dofus 1.29, puis la config et la mettre dans le dossier Dofus.</p>
<p class="titrePage">Téléchargements</p>
<div class="clean"></div><br>
<center><u>Config.xml</u><a href="<?php echo $url_config?>" target=_blank title="Config.xml"><br><img src="./images/rejoin/download.png" name="imageD" onmouseover="imageD.src='images/rejoin/download2.png'" onmouseout="imageD.src='images/rejoin/download.png'"/></a><br><br>
<u>Client dofus 1.29</u><a href="<?php echo $url_client?>" target=_blank title="Client"><br><img src="./images/rejoin/download.png" name="imageD1" onmouseover="imageD1.src='images/rejoin/download2.png'" onmouseout="imageD1.src='images/rejoin/download.png'" /></a><br><br></center>
<?php }?>