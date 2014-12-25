<?

error_reporting(E_ALL);
ini_set("display_errors", 1);
/**
 * NOTE! 1st Partner doens't have referal code !!!
 * ****** */
/**
  fix referals assigned to another partner
 * */
$reports_dir = "/var/www/usr0/data/www/mlgame.aratog.com/archiv";
$mysql_user = "usr0";
$mysql_pass = "kEek4Sgj";
$mysql_db = "mlgame_aratog_com";

mysql_connect("localhost", $mysql_user, $mysql_pass) or die("can't connect mysql");
mysql_select_db($mysql_db) or die("can't select db");

//get partners
$res = mysql_query("select pp.*,ju.username from part_partners pp left join jos_users ju on pp.siteuserid=ju.id  where pp.refcode!=NULL or pp.refcode!='-' ");
while ($p = mysql_fetch_assoc($res)) { //go through each partner
    echo "<p>Partner: {$p['refcode']} | {$p['username']} | id: {$p['id']}</p>";
    $ref_code = $p['refcode'];
    //echo $ref_code;
    //get players from report
    $file = "$reports_dir" . DIRECTORY_SEPARATOR . "{$ref_code}-2012-03-04.xml";
    if (file_exists($file)) {
        $partner_obj = simplexml_load_file($file);
    } else {
        echo "file $file doesn't exist<br/>";
        print_r($p);
        echo "<br/>";
    }

    //create user ids arr from partner obj
    $users = array();
    foreach ($partner_obj->users->user as $user) {
        $users[] = (string) $user->name;
    }


    //get players from db 

    $res2 = mysql_query("select * from part_partner_referals where partid={$p['id']} and username is not NULL");
    while ($player = mysql_fetch_assoc($res2)) {
        $player_name = $player['username'];
        if (!in_array($player_name, $users)) {
            $refpaid = mysql_result(mysql_query("select sum(refpaid) from `part_partner_referals_day_stat` where refid={$player['id']}"), 0);
            echo "<span style='color:red;'>Not in Report: ";
            print_r($player);
            echo " | REFPAID: {$refpaid}</span><br/>";
        } else {
            $keys = array_keys($users, $player_name);
            if (count($keys) > 1)
                die("count > 1");
            unset($users[$keys[0]]);
        }
    }
    if (!empty($users)) {
        echo "<span style='color:blue;'>No players in DB:<br/>";
        print_r($users);
        echo "</span><br/>";
        $users_str=  implode("','", $users);
        
        $users_str=  "'".$users_str."'";
        echo $users_str;
        echo "<h4>Belongs to:</h4>";
        $query="select * from part_partner_referals where username in ({$users_str})";
        $q=mysql_query($query) or die($query." | ".mysql_error());
        while($u=mysql_fetch_assoc($q)){
            echo "<span style='color:green;'>";
            echo "{$u['username']} : partid{$u['partid']} ||| ";
            echo "</span>";
        }
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! fixing referals!
        //mysql_query("update part_partner_referals set partid={$p['id']},site_id=NULL where username in ({$users_str})");
    }
    
    //break;
}




//compare them
?>
