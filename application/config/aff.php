<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['projects']=array(1); //array of all project ids
$config['default_project_id']=1;  //mlgame
$config['default_percent']=40;   //default partner's percent
$config['invite_lifetime']=7200; //2h
$config['default_loyalty_percent']=3; //3%
$config['forward_cookie_lifetime']=86400; //24 h
$config['prx_cookie_prefix']="aratog_"; 
$config['reg_confirm_salt']="038y2a0B^(@9240m98r2($^*";
$config['project_logo_dir']="img".DIRECTORY_SEPARATOR."proj_logo";  //must be writeble for uploads

$config['noauth_controllers']=array("login","register","forward","set_referal","test","report","invite", "mytest", "help");
$config['projects_xml_archive_path']="archive";



//mlgame settings
$config['mlgame_secret_key']="sd2395xcvz46ns";
$config['mlgame_system_percent'] = 60;
$config['mlgame_xml_url']="http://export.2056.ru/arspartner/";

?>