<?php
class perso
{
	private $base_stats = array();
	private $stuff_stats = array();
	private $total_stats = array();
	private $name;
	private $level;
	private $xp;
	private $xpUp;
	private $id;
	private $image;
	
	public function displayInfo()
	{
		echo '<div style="float:left;">'.$this->image.'</div><br><br>';
		echo '<h2><u>'.$this->name.'</u></h2><br><br>
		- Level : <font color=green>'.$this->level.'</font><br>
		- Xp : <font color=green>'.$this->xp.' / '.$this->xpUp.'</font><br><br><br><br><br>';
	}
	public function displayStats()
	{
		?>
		<h3>Stats : </h3><br>
		- PDV : <font color=pink><?php echo ($this->base_stats['vie'] + intval($this->level) * 5 + 50) .' + '.$this->stuff_stats['vie'].' = '.$this->total_stats['vie'];?></font><br>
		- Sagesse : <font color=purple><?php echo $this->base_stats['sagesse'].' + '.$this->stuff_stats['sagesse'].' = '.$this->total_stats['sagesse'];?></font><br>
		- Chance : <font color=blue><?php echo $this->base_stats['chance'].' + '.$this->stuff_stats['chance'].' = '.$this->total_stats['chance'];?></font><br>
		- Intelligence : <font color=red><?php echo $this->base_stats['intel'].' + '.$this->stuff_stats['intel'].' = '.$this->total_stats['intel'];?></font><br>
		- Force : <font color=brown><?php echo $this->base_stats['force'].' + '.$this->stuff_stats['force'].' = '.$this->total_stats['force'];?></font><br>
		- Agilité : <font color=green><?php echo $this->base_stats['agi'].' + '.$this->stuff_stats['agi'].' = '.$this->total_stats['agi'];?></font><br><br>
		<?php
	}
	public function delete()
	{
		
	}
	
	
	//fonctions d'initialisation
	public function __construct($donnees)
	{
		$this->id = $donnees['id'];
		$this->name = $donnees['name'];
		$this->level = $donnees['level'];
		$this->getXp();
		$this->getStats($donnees);
		$this->getImage();
	}
	private function getXp()
	{
		include('./inc/config.php');
		$query = 'SELECT perso FROM '.$db_static.'.experience WHERE lvl = "'.($level + 1).'"';
		$result = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result) > 0)
		{
			$donnees = mysql_fetch_array($result);
			$this->xpUp = $donnees['perso'];
		}else 
		{
			$this->xpUp = 'Undefined';
		}
	}
	private function getStats($donnees)
	{
		include 'compte/function.php';
		$item = calcStats($this->id);
		$pano = statsPano($this->id);
		
		$this->base_stats['vie'] = $donnees['vitalite'];
		$this->base_stats['force'] = $donnees['force'];
		$this->base_stats['agi'] = $donnees['agilite'];
		$this->base_stats['sagesse'] = $donnees['sagesse'];
		$this->base_stats['intel'] = $donnees['intelligence'];
		$this->base_stats['chance'] = $donnees['chance'];
		
		$this->stuff_stats['vie'] = $item['vie'] + $pano['vie'];
		$this->stuff_stats['force'] = $item['force'] + $pano['force'];
		$this->stuff_stats['intel'] = $item['intel'] + $pano['intel'];
		$this->stuff_stats['sagesse'] = $item['sagesse'] + $pano['sagesse'];
		$this->stuff_stats['agi'] = $item['agi'] + $pano['agi'];
		$this->stuff_stats['chance'] = $item['chance'] + $pano['chance'];
		
		$this->total_stats['vie'] = $this->base_stats['vie'] + $this->stuff_stats['vie'];
		$this->total_stats['force'] = $this->base_stats['force'] + $this->stuff_stats['force'];
		$this->total_stats['intel'] = $this->base_stats['intel'] + $this->stuff_stats['intel'];
		$this->total_stats['sagesse'] = $this->base_stats['sagesse'] + $this->stuff_stats['sagesse'];
		$this->total_stats['agi'] = $this->base_stats['agi'] + $this->stuff_stats['agi'];
		$this->total_stats['chance'] = $this->base_stats['chance'] + $this->stuff_stats['chance'];
	}
	private  function getImage()
	{
		include './inc/function.php';
		$this->image = imgPerso($this->id, 1);
	}
} 