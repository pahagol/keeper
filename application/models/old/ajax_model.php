<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Eye View Design CMS module Ajax Model
 *
 * PHP version 5
 *
 * @category  CodeIgniter
 * @package   EVD CMS
 * @author    Frederico Carvalho
 * @copyright 2008 Mentes 100Limites
 * @version   0.1
 */
class Ajax_model extends CI_Model {

    /**
     * Instanciar o CI
     */
    function __construct() {
        parent::__construct();
    }

    public function get_stats($params) {
        //Select table name
        if (!empty($params['proj']))
            $proj = $params['proj'];else
            $proj = config_item("default_project_id");
        $table_name = "p{$proj}_day_stats";

        //Build contents query
        if (!empty($params['date'])) {
            if (empty($params['date_to']))
                $params['date_to'] = $params['date'];
            $date = $this->db->escape($params['date']);
            $date_to = $this->db->escape($params['date_to']);

            $this->db->where("date BETWEEN $date and $date_to");
        }
        $this->db->select("date, clicks, registers, active_regs, earnings")->from($table_name)->
                where("user_id", $this->user->id);
        $this->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();
        //echo $this->db->last_query();
        //Build count query
        if (!empty($params['date'])) {
            $this->db->where("date BETWEEN $date and $date_to");
        }
        $this->db->select('count(*) as record_count,sum(clicks) clicks,sum(registers) registers,sum(active_regs) active_regs,sum(earnings) earnings')
                ->from($table_name)->where("user_id", $this->user->id);
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        //Get Record Count
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, $row->clicks, $row->registers, $row->active_regs, $row->earnings);

        //Return all
        return $return;
    }

    public function get_refs($params) {
        //Select table name
        if (!empty($params['proj']))
            $p_id = intval($params['proj']);else
            $p_id = config_item("default_project_id");
        $table_name = "p{$p_id}_referals pr";

        //Build contents query
        if (!empty($params['reg_date']))
            $this->db->where("reg_date", $params['reg_date']);

        if (!empty($params['show_active']))
            $this->db->where("pr.level > 1");

        $this->db->select("pr.*,coalesce(prl.level,1) as reg_level,gs.url, gs.id as site_id", false);
        $this->db->from($table_name)->where("pr.user_id", $this->user->id);
        $this->db->join("p{$p_id}_ref_levels prl", "prl.ref_id = pr.id and prl.date=pr.reg_date", "left");
        $this->db->join("global_sites gs", "gs.id = pr.site_id", "left");
        $this->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();

	//	echo $this->db->last_query();
		
        //Build count query
        if (!empty($params['reg_date']))
            $this->db->where("reg_date", $params['reg_date']);
        $this->db->select('count(*) as record_count,sum(pr.profit) profit,sum(pr.inputted) inputted,sum(pr.spent) spent,sum(pr.earned) earned,sum(pr.credit) credit')
                ->from($table_name)->where("pr.user_id", $this->user->id);
        $this->db->join("p{$p_id}_ref_levels prl", "prl.ref_id = pr.id and prl.date=pr.reg_date", "left");
        $this->db->join("global_sites gs", "gs.id = pr.site_id", "left");
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, $row->profit, $row->spent, null, null, null);

        //Return all
        return $return;
    }

    function get_finance() {
        $table_name = "global_payouts gp";
        $this->db->from($table_name)->where("user_id", $this->user->id);
        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();

        $this->db->select("count(*) record_count,sum(sum) allsum");
        $this->db->from($table_name)->where("user_id", $this->user->id);
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, $row->allsum, null);

        return $return;
    }

    function get_loyalty() {
        $table_name = "global_users gu";
        $this->db->select("gu.*,round(earnings*parent_loyalty_percent/100,2) my_earnings", false)->from($table_name)->where("parent_id", $this->user->id);
        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();

        $this->db->select("count(*) record_count,sum(earnings) earnings,sum(registers) registers,round(sum(earnings*parent_loyalty_percent/100),2) my_earnings,sum(active_regs) active_regs,sum(active_regs_reg_day) active_regs_reg_day", false);
        $this->db->from($table_name)->where("parent_id", $this->user->id);
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, $row->earnings, $row->registers, $row->active_regs, $row->active_regs_reg_day, null, $row->my_earnings);

        return $return;
    }

    function get_sites($p_id = 1) {
        $table_name = "p{$p_id}_sites ps";
        $this->db->select("ps.*,gs.url,gs.attendance");
        $this->db->from($table_name)->join("global_sites gs", "gs.id = ps.site_id")->where("user_id", $this->user->id);
        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();

	//	echo $this->db->last_query();
		
        $this->db->from($table_name)->join("global_sites gs", "gs.id = ps.site_id")->where("user_id", $this->user->id);
        $this->db->select("count(*) record_count,sum(ps.clicks) clicks,sum(ps.registers) registers,sum(ps.active_regs) active_regs,sum(ps.active_regs_reg_day) active_regs_reg_day,sum(ps.earnings) earnings");
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, $row->clicks, $row->registers, $row->active_regs, $row->active_regs_reg_day, $row->earnings);

        return $return;
    }

    public function get_refs_by_date($params) {
        //Select table name
        if (!empty($params['proj']))
            $p_id = intval($params['proj']);else
            $p_id = config_item("default_project_id");
        $table_name = "p{$p_id}_ref_day_stats prds";

        //Build contents query
        if (!empty($params['date']))
            $this->db->where("prds.query_date", $params['date']);

        $this->db->select("prds.*,pr.name,gs.url as site, gs.id as site_id,reg_date");
        $this->db->from($table_name)->where("pr.user_id", $this->user->id);
        $this->db->join("p{$p_id}_referals pr", "pr.id = prds.referal_id");
        $this->db->join("global_sites gs", "gs.id = pr.site_id", "left");
        $this->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();

        //Build count query
        if (!empty($params['date']))
            $this->db->where("prds.query_date", $params['date']);
        $this->db->select('count(*) as record_count,sum(prds.day_profit) day_profit,sum(prds.inputted) inputted,sum(prds.spent) spent,sum(prds.earned) earned,sum(prds.credit) credit')
                ->from($table_name)->where("pr.user_id", $this->user->id);
        $this->db->join("p{$p_id}_referals pr", "pr.id = prds.referal_id");
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();

        //Get Record Count
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, null, $row->day_profit, $row->inputted, $row->spent, $row->earned, $row->credit, null);

        //Return all
        return $return;
    }

    function get_referal_day_stats($referal_id) {
        /* params */
        $table = "p1_ref_day_stats prds";
        $referal_id = intval($referal_id);

        /* main_query */
        $this->db->select("prds.query_date,prds.spent,prds.earned,prds.inputted,prds.ref_paid,prds.ref_to_pay,t1.level level,prds.day_sum,prds.day_profit,prds.day_profit_mlgame,prds.credit");
        $this->db->from($table);
        $this->db->join("p1_ref_levels t1", "t1.ref_id=prds.referal_id and t1.date=prds.query_date", "left");
        //$this->db->join("(select max(date) date,ref_id from p1_ref_levels group by ref_id) t2", "t2.ref_id=t1.ref_id and t1.date=t2.date", "left");
        //$this->db->where("t2.date<=prds.query_date", null, false);
        $this->db->where("referal_id", $referal_id);
        $this->db->join("p1_referals pr", "pr.id=prds.referal_id");
        $this->db->where("pr.user_id", $this->user->id);
        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();
        //echo $this->db->last_query();

        /* credit change */
        $this->db->select("query_date,credit")->from($table)
                ->join("p1_ref_levels t1", "t1.ref_id=prds.referal_id and t1.date=prds.query_date", "left")
                ->where("referal_id", $referal_id)
                ->order_by("query_date", "asc");
        $return['records_credit'] = $this->db->get();

        /* records count */
        $this->db->select("count(*) as record_count,sum(prds.spent) spent,sum(prds.earned) earned,sum(prds.inputted) inputted,sum(prds.ref_paid) ref_paid,sum(prds.ref_to_pay) ref_to_pay,sum(prds.day_sum) day_sum,sum(prds.day_profit) day_profit,sum(prds.day_profit_mlgame) day_profit_mlgame,sum(prds.credit) credit");
        $this->db->from($table);
        $this->db->join("p1_ref_levels t1", "t1.ref_id=prds.referal_id and t1.date=prds.query_date", "left");
        //$this->db->join("(select max(date) date,ref_id from p1_ref_levels group by ref_id) t2", "t2.ref_id=t1.ref_id and t1.date=t2.date", "left");
        //$this->db->where("t2.date<=prds.query_date", null, false);
        $this->db->where("referal_id", $referal_id);
        $this->db->join("p1_referals pr", "pr.id=prds.referal_id");
        $this->db->where("pr.user_id", $this->user->id);
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, $row->spent, $row->earned, $row->inputted, $row->ref_paid, $row->ref_to_pay, null, $row->day_sum, $row->day_profit, $row->day_profit_mlgame, $row->credit);

        return $return;
    }

    function get_admin_stats($params) {
        /* params */
        $table_name = "p1_day_stats pds";
        $date = empty($params['date']) ? null : $this->db->escape($params['date']);
        $date_to = empty($params['date_to']) ? $date : $this->db->escape($params['date_to']);
        $user_id = empty($params['user_id']) ? null : intval($params['user_id']);
        /* end of params */

        /* main query */
        if ($date && $date_to) {
            $this->db->where("pds.date BETWEEN $date AND $date_to");
        }
        if ($user_id) {
            $this->db->select("gu.name name, pds.user_id user_id");
            $this->db->join("global_users gu", "gu.id = pds.user_id");
            $this->db->where("user_id", $params['user_id']);
        }
        $this->db->select("pds.date date,sum(pds.clicks) clicks,sum(pds.registers) registers,sum(pds.active_regs) active_regs, sum(pds.earnings) earnings, sum(pds.earnings_mlgame) earnings_mlgame");
        $this->db->group_by("pds.date")->from($table_name);
        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();
   //     echo $this->db->last_query();
        /* end of main query */

        /* records count */
        if ($date && $date_to) {
            $this->db->where("pds.date BETWEEN $date AND $date_to");
        }
        if ($user_id) {
            $this->db->where("user_id", $params['user_id']);
            $this->db->join("global_users gu", "gu.id = pds.user_id");
        }
        $this->db->select("count(distinct(date)) as record_count,sum(pds.clicks) clicks,sum(pds.registers) registers,sum(pds.active_regs) active_regs,sum(pds.earnings) earnings, sum(pds.earnings_mlgame) earnings_mlgame");
        $this->db->from($table_name);
        $this->flexigrid->build_query(false);
        $record_count = $this->db->get();
        $row = $record_count->row();
        $return['record_count'] = $row->record_count;
        //$return['sums'] = array(null, null, $row->clicks, $row->registers, $row->active_regs, $row->earnings, $row->earnings_mlgame);
		$return['sums'] = array(null, null, $row->clicks, $row->registers, $row->active_regs, $row->earnings, $row->earnings_mlgame);
        /* end of records count */
        return $return;
    }

    function get_admin_stats_by_partner() {
        //Select table name
        $table_name = "p1_users pu";
        //$arr = array("ds.earnings", "ds.clicks", "ds.registers", "ds.active_regs");
        //if (in_array($this->flexigrid->post_info['sortname'], $arr)) {
        //  $this->flexigrid->post_info['sortname'] = substr($this->flexigrid->post_info['sortname'], 3);
        // }
        //Build contents query
        $q = $this->db->get_where("global_projects", array("id" => 1));
        $payout_delay = $q->row()->payout_delay;
        $date = date("Y-m-d", time() - $payout_delay * 24 * 60 * 60);

        $this->db->select("pu.user_id user_id,name,ref_code,pu.sum_to_pay sum_to_pay,(pu.sum_to_pay-last_earnings) delayed_sum_to_pay,pu.percent percent,gu.reg_date reg_date,ps.cnt_sites cnt_sites,sum(pu.clicks) clicks,sum(pu.registers) registers,sum(pu.active_regs) active_regs,sum(pu.earnings) earnings");
        $this->db->from($table_name)->join("global_users gu", "pu.user_id = gu.id");
        $this->db->join("(select user_id,count(*) as cnt_sites from global_sites group by user_id) ps", "ps.user_id=pu.user_id");
        $this->db->join("(select sum(earnings) last_earnings,user_id from p1_day_stats where date>='{$date}' group by user_id) t2", "t2.user_id=gu.id");
        $this->db->group_by("pu.user_id");
        $this->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();
        //echo $this->db->last_query();
        // if ($this->flexigrid->post_info['page'] == "33")
        //if ($this->flexigrid->post_info['sortname'] != "earnings")
        //  echo $this->db->last_query();
        //Build count query
        //$arr = array("earnings", "clicks", "registers", "active_regs");
        //if (in_array($this->flexigrid->post_info['sortname'], $arr)) {
        ///  $this->flexigrid->post_info['sortname'] = "ds." . $this->flexigrid->post_info['sortname'];
        //}
        $this->db->select("count(*) as record_count,sum(pu.clicks) clicks,sum(pu.registers) registers,sum(pu.active_regs) active_regs,sum(pu.earnings) earnings,sum(pu.sum_to_pay-last_earnings) delayed_sum_to_pay,sum(ps.cnt_sites) cnt_sites,sum(pu.sum_to_pay) sum_to_pay");
        $this->db->from($table_name)->join("global_users gu", "pu.user_id = gu.id");
        $this->db->join("(select user_id,count(*) as cnt_sites from global_sites group by user_id) ps", "ps.user_id=pu.user_id");
        $this->db->join("(select sum(earnings) last_earnings,user_id from p1_day_stats where date>='{$date}' group by user_id) t2", "t2.user_id=gu.id");
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        //echo $this->db->last_query();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, null . null, null, $row->earnings, null, $row->sum_to_pay, $row->delayed_sum_to_pay, $row->clicks, $row->registers, null, $row->active_regs, $row->cnt_sites);
        return $return;
    }

    function get_admin_stats_by_date_partner($params) {
        //Select table name
        $table_name = "p1_day_stats ds";
        $date = $this->db->escape($params['date']);
        $date_to = $this->db->escape($params['date_to']);

        $this->db->where("date BETWEEN {$date} AND {$date_to}");
        if (!empty($params['earnings_not_null']))
            $this->db->where("ds.earnings > 0");
        //Build contents query
        //$arr = array("ds.earnings", "ds.clicks", "ds.registers", "ds.active_regs");
        //if (in_array($this->flexigrid->post_info['sortname'], $arr)) {
        //  $this->flexigrid->post_info['sortname'] = substr($this->flexigrid->post_info['sortname'], 3);
        //}
        $this->db->select("user_id")->select("name")->select("ds.date")->join("global_users gu", "ds.user_id = gu.id")->select_sum("ds.clicks")->select_sum("ds.registers")->
                select_sum("ds.active_regs")->select_sum("ds.earnings")->from($table_name)->group_by("ds.user_id");
        $this->flexigrid->build_query();
        //Get contents
        $return['records'] = $this->db->get();
        //echo $this->db->last_query();
        ///$arr = array("earnings", "clicks", "registers", "active_regs");
        //if (in_array($this->flexigrid->post_info['sortname'], $arr)) {
        //   $this->flexigrid->post_info['sortname'] = "ds." . $this->flexigrid->post_info['sortname'];
        // }
        $this->db->where("date BETWEEN {$date} AND {$date_to}");
        if (!empty($params['earnings_not_null']))
            $this->db->where("ds.earnings > 0");

        $this->db->select("count(*) as record_count,sum(ds.clicks) clicks,sum(ds.earnings) earnings,sum(ds.registers) registers,sum(ds.active_regs) active_regs")
                ->join("global_users gu", "ds.user_id = gu.id")->from($table_name); //->group_by("ds.user_id");
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, $row->clicks, $row->registers, $row->active_regs, $row->earnings);
        //Return all
        return $return;
    }

    function get_admin_stats_by_referal($params) {
        //Select table name
        $table_name = "p1_referals pr";

        //Build contents query
        //if ($params['date'])
        //  $this->db->where("reg_date", $params['date']);
        if ($params['user_id'])
            $this->db->where("user_id", $params['user_id']);
        if (!empty($params['show_active']))
            $this->db->where("level > 1");
        $this->db->select("pr.id id,pr.name name,DATE(pr.reg_date) reg_date,pr.user_id user_id,gu.name user_name,pr.spent spent,pr.earned earned,pr.inputted inputted,pr.credit credit,pr.profit profit,pr.level level");
        $this->db->from($table_name);
        $this->db->join("global_users gu", "gu.id=pr.user_id");
        //$this->db->select("p1_referals.*,DATE(p1_referals.reg_date) as reg_date,gu.name as user_name");
        $this->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();
        //echo $this->db->last_query();
        //Build count query
        //if ($params['date'])
        //  $this->db->where("reg_date", $params['date']);
        if ($params['user_id'])
            $this->db->where("user_id", $params['user_id']);
        if (!empty($params['show_active']))
            $this->db->where("level > 1");
        $this->db->join("global_users gu", "gu.id=pr.user_id");
        $this->db->select('count(*) as record_count,sum(spent) spent,sum(earned) earned,sum(inputted) inputted,sum(credit) credit,sum(ref_paid) ref_paid')
                ->from($table_name);
        $this->flexigrid->build_query(false);
        $record_count = $this->db->get();
        $row = $record_count->row();

        //Get Record Count
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, null, null, $row->spent, /*$row->earned, $row->inputted, $row->credit,*/ $row->ref_paid, null);

        //Return all
        return $return;
    }

    function get_admin_stats_by_date_referal($params) {
        //Select table name
        $table_name = "p1_ref_day_stats ds";
        $date = $this->db->escape($params['date']);
        $date_to = $this->db->escape($params['date_to']);

        $this->db->where("ds.query_date BETWEEN {$date} AND {$date_to}");
        //Build contents query
        if ($params['user_id'])
            $this->db->where("pr.user_id", $params['user_id']);
        $this->db->from($table_name)->join("p1_referals pr", "pr.id = ds.referal_id")->group_by("pr.id")->join("global_sites gs", "gs.id = pr.site_id", "left");
        $this->db->select("pr.id, pr.name, pr.reg_date")->select_sum("ds.spent")->select_sum("ds.earned")->select("ds.credit")->select_sum("ds.day_profit")->select_sum("ds.inputted")->select("url");

        $this->flexigrid->build_query();
        //Get contents
        $return['records'] = $this->db->get();
     //   echo $this->db->last_query();

		/*SELECT `pr`.`id`, `pr`.`name`, `pr`.`reg_date`, 
		 * SUM(`ds`.`spent`) AS spent, 
		 * SUM(`ds`.`earned`) AS earned, `ds`.`credit`, 
		 * SUM(`ds`.`day_profit`) AS day_profit, SUM(`ds`.`inputted`) AS inputted, `url`
FROM (`p1_ref_day_stats` ds)
JOIN `p1_referals` pr ON `pr`.`id` = `ds`.`referal_id`
LEFT JOIN `global_sites` gs ON `gs`.`id` = `pr`.`site_id`
WHERE `ds`.`query_date` BETWEEN '2012-08-01' AND '2012-08-19'
GROUP BY `pr`.`id`
ORDER BY `ds`.`day_profit` desc
LIMIT 15
		 */

        $this->db->where("ds.query_date BETWEEN {$date} AND {$date_to}");
        if ($params['user_id'])
            $this->db->where("pr.user_id", $params['user_id']);
        $this->db->from($table_name)->join("p1_referals pr", "pr.id = ds.referal_id")->join("global_sites gs", "gs.id = pr.site_id", "left");
        //$this->db->from($table_name)->join("p1_referals pr", "pr.id = ds.referal_id");
        $this->db->select("count(*) as record_count,sum(ds.spent) spent,sum(ds.earned) earned,sum(ds.credit) credit,sum(ds.day_profit) day_profit,sum(ds.inputted) inputted");
        $this->flexigrid->build_query(false);
        $record_count = $this->db->get();
        //echo $this->db->last_query();

        $row = $record_count->row();

        //Get Record Count
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, $row->spent, $row->earned, $row->inputted, $row->day_profit, $row->credit, null);

        //Return all
        return $return;
    }

    function get_admin_stats_by_level_referal($params) {
        if (!empty($params['date']))
            $date = $this->db->escape($params['date']);else
            $date = null; //return false;
        if (!empty($params['reg_date']))
            $reg_date = $params['reg_date'];else
            $reg_date = null;

        $table_name = "p1_referals pr";
        if ($date)
            $prll_str = "prll";else
            $prll_str = "pr";
        $this->db->select("pr.id user_id,pr.name, COALESCE(`{$prll_str}`.`level`, '1') as curlevel,COALESCE(`prl`.`level`, '1') as reglevel,gs.url", FALSE);
        $this->db->from($table_name);
        $this->db->join("p1_ref_levels prl", "prl.ref_id = pr.id and prl.date = pr.reg_date", "left");
        //if ($date)
        //  $this->db->join("(SELECT t1.ref_id,t1.date,t2.level FROM (SELECT ref_id, MAX( DATE ) AS date FROM p1_ref_levels WHERE date<={$date} GROUP BY ref_id )t1 JOIN p1_ref_levels t2 ON t1.ref_id = t2.ref_id and t1.date=t2.date) as prll", "prll.ref_id = pr.id", "left");
        //$this->db->join("(select ref_id,level,max(date) date from p1_ref_levels group by ref_id) as prll","prll.ref_id = pr.id","left");
        if ($date) {
            $this->db->join("p1_ref_levels prll", "prll.ref_id=pr.id and prll.date = {$date}");
            //$this->db->join("p1_ref_levels prll", "prll.ref_id=pr.id");
            //$this->db->join("(select ref_id,MAX(date) date from p1_ref_levels where date<={$date} group by ref_id) b", "b.ref_id=pr.id and b.date=prll.date");
        }
        if ($reg_date)
            $this->db->where("reg_date", $reg_date);
        if (!empty($params['user_id']))
            $this->db->where("pr.user_id", $params['user_id']);
        $this->db->join("global_sites gs", "gs.id = pr.site_id", "left");
        $this->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();
        //echo $this->db->last_query();
        //Build count query
        $this->db->select("count(*) as record_count,pr.id user_id,pr.name, COALESCE(`{$prll_str}`.`level`, '1') as curlevel, COALESCE(`prl`.`level`, '1') as reglevel,gs.url", FALSE);
        $this->db->from($table_name);
        $this->db->join("p1_ref_levels prl", "prl.ref_id = pr.id and prl.date = pr.reg_date", "left");
        if ($date) {
            $this->db->join("p1_ref_levels prll", "prll.ref_id=pr.id and prll.date = {$date}");
            //$this->db->join("p1_ref_levels prll", "prll.ref_id=pr.id");
            //$this->db->join("(select ref_id,MAX(date) date from p1_ref_levels where date<={$date} group by ref_id) b", "b.ref_id=pr.id and b.date=prll.date");
        }
        if ($reg_date)
            $this->db->where("reg_date", $reg_date);
        if (!empty($params['user_id']))
            $this->db->where("pr.user_id", $params['user_id']);
        $this->db->join("global_sites gs", "gs.id = pr.site_id", "left");
        $this->flexigrid->build_query(false);
        $record_count = $this->db->get();
        //echo $this->db->last_query();

        $row = $record_count->row();

        //Get Record Count
        $return['record_count'] = $row->record_count;

        //Return all
        return $return;
    }

    function get_admin_sites($params) {
        $status_arr = array("active", "banned");
        $user_id = intval($params['user_id']);
        $proj = intval($params['proj']);
        $state = in_array($params['state'], $status_arr) ? $params['state'] : null;
        $table_name = "global_sites gs";

        $this->db->from($table_name)->join("global_users gu", "gu.id = gs.user_id");
        $this->db->join("p1_sites ps", "ps.site_id = gs.id");
        if ($state)
            $this->db->where("gs.state", $state);
        if ($user_id)
            $this->db->where("gu.id", $user_id);

        $this->db->select("gs.id,url,gs.clicks as clicks,gs.registers as registers,gs.earnings as earnings,ps.active_regs,gs.attendance as attendance,gs.state as state,gu.name as name,gu.id as user_id,ps.reason_banned");
        $this->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();



        if ($state)
            $this->db->where("gs.state", $params['state']);
        if ($user_id)
            $this->db->where("gu.id", $user_id);
        $this->db->from($table_name)->join("global_users gu", "gu.id = gs.user_id");
        $this->db->join("p1_sites ps", "ps.site_id = gs.id");
        $this->db->select("count(*) as record_count,sum(gs.clicks) clicks,sum(gs.registers) registers,sum(gs.earnings) earnings,sum(ps.active_regs) active_regs");
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, $row->clicks, $row->registers, $row->active_regs, $row->earnings, null, null, null, null, null);

        //Return all
        return $return;
    }

    function get_admin_project_users($params) {
        /* validating */
        $p_id = intval($params['id']);
        $states_arr = array("registered", "moderate", "not_confirmed", "blocked","new");
        $state = in_array($params['state'], $states_arr) ? $params['state'] : null;
        if (!in_array($p_id, config_item("projects")))
            return false;
        /*         * ********** */

        $table = "p{$p_id}_users pu";

        $this->db->select("pu.*,gu.state,name,count(ps.site_id) as count_sites");
        $this->db->from($table)->join("global_users gu", "gu.id = pu.user_id");
        $this->db->join("global_sites gs", "gs.user_id=pu.user_id", "left");
        $this->db->join("p{$p_id}_sites ps", "ps.site_id=gs.id", "left");
        if ($state)
            $this->db->where("gu.state", $state);
        $this->db->group_by("pu.user_id");

        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();


        $this->db->select("count(*) record_count,sum(pu.clicks) clicks,sum(pu.registers) registers,sum(pu.active_regs) active_regs,sum(pu.earnings) earnings,sum(pu.earnings_mlgame) earnings_mlgame,sum(pu.sum_to_pay) sum_to_pay");
        $this->db->from($table)->join("global_users gu", "gu.id = pu.user_id");
        //$this->db->join("global_sites gs", "gs.user_id=pu.user_id", "left");
        //$this->db->join("p{$p_id}_sites ps", "ps.site_id=gs.id", "left");
        if ($state)
            $this->db->where("gu.state", $state);
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        //echo $this->db->last_query();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, $row->clicks, $row->registers, $row->active_regs, $row->earnings, $row->earnings_mlgame, $row->sum_to_pay, null, null, null);

        return $return;
    }

	function get_admin_banners($params) {
        $table = "p1_banners";

        $this->db->select("*");
        $this->db->from($table);
        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();

        //echo $this->db->last_query();
        $return['record_count'] = $return['records']->num_rows();
        

        return $return;
    }
	
	function get_admin_banner_by_id($id) {
		return $this->db->from("p1_banners")->where("id = $id")->get()->row();
	}
	
	function set_admin_banner_active($id) {
		if (!empty($id)){
			$this->db->set('active', '!`active`', false);
			$this->db->where('id', $id);
			$this->db->update('p1_banners');
		//	echo $this->db->last_query();
		}	
	}
	
    function get_admin_frauds() {


        $table = "p1_chargebacks pc";

        $this->db->select("pc.*,gu.name,pr.id,gu.id user_id");
        $this->db->join("global_users gu", "gu.id = pc.user_id");
        //$this->db->join("global_sites gs", "gs.id = pc.site_id");
        $this->db->join("p1_referals pr", "pr.name = pc.ref_name");
        $this->db->from($table);

        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();

        //$this->db->select("pc.*,gu.name,gs.url,pr.name");
        $this->db->join("global_users gu", "gu.id = pc.user_id");
        //$this->db->join("global_sites gs", "gs.id = pc.site_id");
        $this->db->join("p1_referals pr", "pr.name = pc.ref_name");
        $this->db->from($table);
        $this->db->select("count(*) record_count,sum(sum) allsum");

        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, null, $row->allsum, null);

        return $return;
    }

    function get_admin_referal_day_stats($referal_id) {
        /* params */
        $table = "p1_ref_day_stats prds";
        $referal_id = intval($referal_id);

        /* main_query */
        $this->db->select("query_date,spent,earned,inputted,ref_paid,ref_to_pay,t1.level level,day_sum,day_profit,day_profit_mlgame,credit");
        $this->db->from($table);
        $this->db->join("p1_ref_levels t1", "t1.ref_id=prds.referal_id and t1.date=prds.query_date", "left");
        //$this->db->join("(select max(date) date,ref_id from p1_ref_levels group by ref_id) t2", "t2.ref_id=t1.ref_id and t1.date=t2.date", "left");
        //$this->db->where("t2.date<=prds.query_date", null, false);
        $this->db->where("referal_id", $referal_id);
        $this->flexigrid->build_query();
        $return['records'] = $this->db->get();
        //echo $this->db->last_query();

        /* credit change */
        $this->db->select("query_date,credit")->from($table)
                ->join("p1_ref_levels t1", "t1.ref_id=prds.referal_id and t1.date=prds.query_date", "left")
                ->where("referal_id", $referal_id)
                ->order_by("query_date", "asc");
        $return['records_credit'] = $this->db->get();

        /* records count */
        $this->db->select("count(*) as record_count,sum(spent) spent,sum(earned) earned,sum(inputted) inputted,sum(ref_paid) ref_paid,sum(ref_to_pay) ref_to_pay,sum(day_sum) day_sum,sum(day_profit) day_profit,sum(day_profit_mlgame) day_profit_mlgame,sum(credit) credit");
        $this->db->from($table);
        $this->db->join("p1_ref_levels t1", "t1.ref_id=prds.referal_id and t1.date=prds.query_date", "left");
        //$this->db->join("(select max(date) date,ref_id from p1_ref_levels group by ref_id) t2", "t2.ref_id=t1.ref_id and t1.date=t2.date", "left");
        //$this->db->where("t2.date<=prds.query_date", null, false);
        $this->db->where("referal_id", $referal_id);
        $this->flexigrid->build_query(false);
        $row = $this->db->get()->row();
        $return['record_count'] = $row->record_count;
        $return['sums'] = array(null, $row->spent, $row->earned, $row->inputted, $row->ref_paid, $row->ref_to_pay, null, $row->day_sum, $row->day_profit, $row->day_profit_mlgame, $row->credit);

        return $return;
    }

}

?>