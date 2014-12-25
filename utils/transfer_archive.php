<?
$mlgame_arch_path="/var/www/usr0/data/www/mlgame.aratog.com/archiv";
$aff_arch_path="/var/www/usr0/data/www/aff.aratog.com/archive/1";

$files=scandir($mlgame_arch_path);
//print_r($files);
foreach($files as $f){
if($f=="." or $f=="..")continue;
	$date=substr($f,-14, 10);
	if(strstr($date,"2012-03")){
		$new_folder=$aff_arch_path.DIRECTORY_SEPARATOR.$date;
		if(!file_exists($new_folder)) mkdir($new_folder);echo $date." folder created   |||  \r ";
		$src=$mlgame_arch_path.DIRECTORY_SEPARATOR.$f;
		$dest=$new_folder.DIRECTORY_SEPARATOR.$f;
		if(!file_exists($dest)){copy($src,$dest);echo $f." file added  ||| \r";}
	}
}

?>
