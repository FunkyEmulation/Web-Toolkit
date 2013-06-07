<?php
if(isset($_SESSION['level']) and isset($_GET['page']) and $_SESSION['level'] >= $level_admin and isset($_GET['id']) and is_numeric($_GET['id']))
{ 
	$query = 'SELECT * FROM accounts WHERE guid = "'.$_GET['id'].'"';
	$retour = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($retour);
	if(isset($_GET['act']))
	{
		$_POST['account'] = mysql_real_escape_string($_POST['account']);
		$_POST['pass'] = mysql_real_escape_string($_POST['pass']);
		$_POST['pseudo'] = mysql_real_escape_string($_POST['pseudo']);
		$_POST['question'] = mysql_real_escape_string($_POST['question']);
		$_POST['reponse'] = mysql_real_escape_string($_POST['reponse']);
		$_POST['level'] = mysql_real_escape_string($_POST['level']);
		$_POST['points'] = mysql_real_escape_string($_POST['points']);
		if($_POST['level'] >= $_SESSION['level'])
		{
			$_POST['level'] = $_SESSION['level'];
		}
		$query = 'UPDATE accounts SET 
			account = "'.$_POST['account'].'", 
			pass = "'.$_POST['pass'].'", 
			pseudo = "'.$_POST['pseudo'].'", 
			question = "'.$_POST['question'].'", 
			reponse = "'.$_POST['reponse'].'", 
			level = "'.$_POST['level'].'", 
			points = "'.$_POST['points'].'"
				WHERE guid = "'.$_GET['id'].'"';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=compte_admin&id='.$_GET['id']);
	}
	?>
<p class="titrePage">Modifier un compte :</p>
<div class="clean"></div><br>
<center>
	<form action="index.php?page=mod_compte_admin&id=<?php echo $_GET['id'];?>&act=1" method="post">
		Nom de compte :<br>
		<input type="text" value="<?php echo $donnees['account'];?>" name="account" /><br>
		Mot de passe :<br>
		<input type="text" value="<?php echo $donnees['pass'];?>" name="pass" /><br>
		Pseudo :<br>
		<input type="text" value="<?php echo $donnees['pseudo'];?>" name="pseudo" /><br>
		Question :<br>
		<input type="text" value="<?php echo $donnees['question'];?>" name="question" /><br>
		Réponse :<br>
		<input type="text" value="<?php echo $donnees['reponse'];?>" name="reponse" /><br>
		Level :<br>
		<input type="text" value="<?php echo $donnees['level'];?>" name="level" /><br>
		Points :<br>
		<input type="text" value="<?php echo $donnees['points'];?>" name="points" /><br>
		<input type="image" src="images/bouttons/envoyer.png" />
	</form>
</center>
<?php }?>