<?php
if(isset($_GET['page']))
{?>

<p class="titrePage">Informations sur le Game Play</p>
<div class="clean"></div>
<h3>Présentation :</h3><br>
<?php echo $serv_name;?> est un serveur <?php echo $type;?> crée par <?php echo $crea;?>. <?php echo $raison_crea;?><br><br>
<h3>Rates :</h3><br>

<table>
	<tr><td>-Rate d'xp : </td><td><font color=red><?php echo $rate_pvm;?></font></td></tr>
	<tr><td>-Rate de pvp : </td><td><font color=red><?php echo $rate_pvp;?></font></td></tr>
	<tr><td>-Rate de drop : </td><td><font color=red><?php echo $rate_drop;?></font></td></tr>
	<tr><td>-Rate de Kamas : </td><td><font color=red><?php echo $rate_kamas;?></font></td></tr>
</table>

<?php if($show_tech){?>
<p class="titrePage">Informations Techniques</p>
<div class="clean"></div><br>

<table>
	<tr><td>Infrastructure : </td><td><font color=red><?php echo $infrastructure;?></font></td></tr>
	<tr><td>Mémoire vive (que ram) : </td><td><font color=red><?php echo $ram.' '.$grandeur_ram;?></font></td></tr>
	<tr><td>Mémoire vive total (ram + swap) : </td><td><font color=red><?php echo $ram_total.' '.$grandeur_ram;?></font></td></tr>
	<tr><td>Disque dur : </td><td><font color=red><?php echo $HDD.' '.$grandeur_HDD;?></font></td></tr>
	<tr><td>Processeur : </td><td><font color=red><?php echo $processeur;?></font></td></tr>
	<tr><td>Bande passante : </td><td><font color=red><?php echo $BP.' '.$grandeur_BP;?></font></td></tr>
	<tr><td>Hébergeur : </td><td><font color=red><?php echo $hebergeur;?></font></td></tr>
</table>
	
<?php }} ?>