<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ajax_model');
        $this->load->library('flexigrid');
        if (!$this->user->is_admin())
            die();
    }

    function stats_admin($params) {


        $valid_fields = array('gu.name', 'pds.date', 'clicks', 'registers', 'active_regs', 'earnings', 'earnings_mlgame');
        $this->flexigrid->validate_post('pds.date', 'desc', $valid_fields);

        $records = $this->ajax_model->get_admin_stats($params);

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($i,
                empty($row->name) ? "Все" : "<a href='admin/userc/view/" . $row->user_id . "'>" . $row->name . "</a>",
                "<a href='admin/stats/by_date_partner/date/{$row->date}'>" . $row->date . "</a>",
                $row->clicks,
                "<a href='admin/stats/by_date_partner/date/{$row->date}/order/ds.registers'>" . $row->registers . "</a>",
                "<a href='admin/stats/by_date_partner/date/{$row->date}/order/ds.active_regs'>" . $row->active_regs . "</a>",
                "<a href='admin/stats/by_date_partner/date/{$row->date}/order/ds.earnings'>" . $row->earnings . "</a>",
				$row->earnings_mlgame
            );
            $i++;
        }
        if (!empty($params['excel'])) {
            build_excel_data($records['records']->result());
        } else {
            //Print please
            $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
        }
    }

    function stats_admin_by_partner() {


        $valid_fields = array('pu.user_id', 'name', 'pu.ref_code', 'gu.reg_date', 'pu.clicks', 'pu.registers', 'active_regs', 'earnings', 'pu.sum_to_pay', 'pu.sum_cur_to_pay', 'pu.percent', 'delayed_sum_to_pay');

        $this->flexigrid->validate_post('pu.earnings', 'desc', $valid_fields);

        $records = $this->ajax_model->get_admin_stats_by_partner();

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;

        $date = date("Y-m-d", time() - 30 * 24 * 60 * 60);
        $date = $this->db->escape($date);
        $q = $this->db->select("user_id")->select_sum("registers", "regs")->group_by("user_id")->get_where("p1_day_stats", "date >= $date");
        if ($q->num_rows()) {
            foreach ($q->result() as $r) {
                $regs_per_month[$r->user_id] = round(($r->regs) / 30);
            }
        }

        // echo $this->db->last_query();
        /**         * ***** * */
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $regs_pm = (empty($regs_per_month[$row->user_id])) ? 0 : $regs_per_month[$row->user_id];
            $record_items[] = array($i,
                $row->user_id,
                "<a href='admin/userc/view/{$row->user_id}'>" . $row->name . "</a>",
                $row->ref_code,
                date("Y-m-d", strtotime($row->reg_date)),
                $row->earnings,
                $row->percent,
                "<a href='admin/userc/view/{$row->user_id}'>" . $row->sum_to_pay . "</a>",
                "<a href=''>" . $row->delayed_sum_to_pay . "</a>",
                "<a href='admin/stats/by_referal/user_id/{$row->user_id}'>" . $row->clicks . "</a>",
                "<a href='admin/stats/by_referal/user_id/{$row->user_id}'>" . $row->registers . "</a>",
                "<a href='admin/stats/by_referal/user_id/{$row->user_id}'>" . $regs_pm . "</a>",
                "<a href='admin/stats/by_referal/user_id/{$row->user_id}'>" . $row->active_regs . "</a>",
                "<a href='admin/sites/view/user_id/{$row->user_id}'>" . $row->cnt_sites . "</a>"
            );
            $i++;
        }

        //if(empty($record_items)) echo "HELLOWORLD".$this->db->last_query();else
        //Print please
        if (!empty($params['excel'])) {
            build_excel_data($records['records']->result());
        } else {
            $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
        }
    }

    function stats_admin_by_date_partner($params) {
        $valid_params = array("date", "date_to", "order", "earnings_not_null");
        $params = elements($valid_params, $params);
        if (!in_array($params['order'], array("ds.registers,ds.active_regs")))
            $params['order'] = "ds.earnings";
        if (empty($params['date_to']))
            $params['date_to'] = $params['date'];


        $valid_fields = array('user_id', 'name', 'ds.clicks', 'ds.registers', 'ds.active_regs', 'ds.earnings');
        $this->flexigrid->validate_post($params['order'], 'desc', $valid_fields);

        $records = $this->ajax_model->get_admin_stats_by_date_partner($params);

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($i,
                $row->user_id,
                "<a href='admin/userc/view/{$row->user_id}'>" . $row->name . "</a>",
                $row->clicks,
                /*  "<a href='admin/stats/by_referal/$row->date/$row->user_id'>" . $row->registers . "</a>", */
                "<a href='admin/stats/by_level_referal/reg_date/{$row->date}'>" . $row->registers . "</a>",
                $row->active_regs,
                "<a href='admin/stats/by_date_referal/date/$row->date/user_id/$row->user_id'>" . $row->earnings . "</a>"
            );
            $i++;
        }
        //$sums=array(null,500,1234,null,1500,230.24);
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function sites_admin($params) {
        $valid_params = array("proj", "state", "user_id");
        $params = elements($valid_params, $params);



        $valid_fields = array('url', 'name', 'clicks', 'registers', 'earnings', 'attendance', 'state', 'ban_reason');

        $this->flexigrid->validate_post('earnings', 'desc', $valid_fields);

        $records = $this->ajax_model->get_admin_sites($params);

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $ban_link = "<a href='admin/sites/ban/id/{$row->id}'>Забанить</a>";
            if ($row->state == "banned") {
                $ban_link = "<a href='admin/sites/unban/id/{$row->id}'>Разбанить</a>";
            }
            $ref_link = anchor("forward/{$row->id}", site_url("forward/{$row->id}"), "target='_blank'"); //site_url("forward/{$row->id}");
            $url = "<a href='http://{$row->url}' target='_blank'>" . $row->url . "</a>";
            if ($row->url == "null") {
                $ref_link = "";
                $ban_link = "";
                $url = "null";
            }
            $record_items[] = array($i,
                $url,
                "<a href='admin/userc/view/{$row->user_id}'>" . $row->name . "</a>",
                $row->clicks,
                $row->registers,
                $row->active_regs,
                $row->earnings,
                $row->attendance,
                $row->state,
                $ban_link,
                $ref_link,
                $row->reason_banned
            );
            $i++;
        }

        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function stats_admin_by_referal($params) {

        $valid_params = array("user_id", "show_active");
        $params = elements($valid_params, $params);


        $valid_fields = array('pr.id', 'pr.reg_date', 'pr.name', 'user_id', 'user_name', 'spent', 'earned', 'inputted', 'credit', 'profit', 'level');

        $this->flexigrid->validate_post('id', 'asc', $valid_fields);

        $records = $this->ajax_model->get_admin_stats_by_referal($params);

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($row->id,
                $row->id,
                "<a href='admin/referal/view/{$row->id}'>" . $row->name . "</a>",
                $row->reg_date,
                "<a href='admin/userc/view/{$row->user_id}'>" . $row->user_name . "</a>",
                $row->spent,
            //    $row->earned,
            //    $row->inputted,
            //    $row->credit,
                $row->profit,
                $row->level
            );
            $i++;
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function stats_admin_by_date_referal($params) {
        $valid_params = array("date", "date_to", "order", "user_id");
        $params = elements($valid_params, $params);
        // if (!in_array($params['order'], array("registers,active_regs")))
        //   $params['order'] = "earnings";
        if (empty($params['date_to']))
            $params['date_to'] = $params['date'];


        $valid_fields = array('pr.id', 'pr.name', 'pr.reg_date', 'ds.spent', 'ds.earned', 'ds.inputted', 'ds.day_profit', 'ds.credit', 'url');
        $this->flexigrid->validate_post($params['order'], 'desc', $valid_fields);

        $records = $this->ajax_model->get_admin_stats_by_date_referal($params);

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($i,
                $row->id,
                "<a href='admin/referal/view/{$row->id}'>" . $row->name . "</a>",
                //$row->reg_date,
                $row->spent,
                $row->earned,
                $row->inputted,
                $row->day_profit,
                $row->credit,
                empty($row->url)?"null":$row->url
            );
            $i++;
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function stats_admin_by_level_referal($params) {
        $valid_params = array("date", "reg_date", "user_id");
        $params = elements($valid_params, $params);


        $valid_fields = array('name', 'curlevel', 'reglevel', 'url');
        $this->flexigrid->validate_post('name', 'desc', $valid_fields);

        $records = $this->ajax_model->get_admin_stats_by_level_referal($params);

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($i,
                "<a href='admin/referal/view/{$row->user_id}'>" . $row->name . "</a>",
                $row->curlevel,
                $row->reglevel,
                empty($row->url) ? "null" : $row->url
            );
            $i++;
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items));
    }

    function admin_project_users($params) {
        $valid_params = array("id", "state");
        $params = elements($valid_params, $params);
        $valid_fields = array('name', 'gu.state', 'pu.clicks', 'pu.registers', 'pu.active_regs', 'pu.earnings', 'pu.earnings_mlgame', 'pu.sum_to_pay', 'pu.percent', 'count_sites');
        $this->flexigrid->validate_post('name', 'asc', $valid_fields);
        $records = $this->ajax_model->get_admin_project_users($params);

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        //if($records['records']->num_rows()){
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $row_str = "";
            if ($row->state == "registered")
                $row_str = "<a href='admin/projectc/ban_partner/{$row->user_id}'>Заблокировать</a>";
            if ($row->state == "blocked")
                $row_str = "<a href='admin/projectc/unban_partner/{$row->user_id}'>Разблокировать</a>";
            if ($row->state == "moderate")
                $row_str = "Модерация";
            if ($row->state == "not_confirmed")
                $row_str = "Подтвреждение";

            
            $record_items[] = array($row->user_id,
                "<a href='admin/userc/view/{$row->user_id}'>" . $row->name . "</a>",
                get_user_status_transalate($row->state),
                $row->clicks,
                $row->registers,
                $row->active_regs,
                $row->earnings,
                $row->earnings_mlgame,
                $row->sum_to_pay,
                $row->percent,
                "<a href='admin/sites/view/user_id/{$row->user_id}'>" . $row->count_sites . "</a>",
                $row_str
            );
            $i++;
        }
        // }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }
	
	function admin_banner($params) {
        $valid_params = array("id", "state");
        $params = elements($valid_params, $params);
        $valid_fields = array('id', 'name', 'file_name', 'active', 'width', 'height', 'type');
        $this->flexigrid->validate_post('name', 'asc', $valid_fields);
	//	$records = $this->ajax_model->get_admin_project_users($params);
		$records = $this->ajax_model->get_admin_banners($params);
        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        //if($records['records']->num_rows()){
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            // $row_str = "<a class='active_banner' rel='{$row->id}' href='#'<!--href='admin/banner/active/{$row->id}'-->>".(($row->active == 1) ? "Виден" : "Скрыт")."</a>";
			$row_str = "<a class='active_banner' rel='{$row->id}' href='#'>".(($row->active == 1) ? "Виден" : "Скрыт")."</a>";
            
            $record_items[] = array(
				$row->id,
                $row->name,
				$row->file_name,
                $row_str,
				$row->width,
                $row->height,
                $row->type
            );
            $i++;
        }
        // }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items));
    }

	function banner_active($params)
	{
		$this->ajax_model->set_admin_banner_active($params['id']);
		$row = $this->ajax_model->get_admin_banner_by_id($params['id']);
		$output = (($row->active == 1) ? "Виден" : "Скрыт");
		$this->output->set_output($output);
	}
	
	function admin_frauds() {
        $valid_fields = array('pc.ref_name', 'gu.name', 'pc.date', 'pc.sum', 'pc.id');
        $this->flexigrid->validate_post('pc.date', 'desc', $valid_fields);

        $records = $this->ajax_model->get_admin_frauds();

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($i,
                "<a href='admin/userc/view/{$row->user_id}'>" . $row->name . "</a>",
                $row->date,
                $row->sum,
                "<a href='admin/referal/view/{$row->id}'>" . $row->ref_name . "</a>",
            );
            $i++;
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function admin_referal_day_stats($id) {
        $valid_fields = array("query_date", "spent", "earned", "inputed", "ref_paid", "ref_to_pay", "t1.level", "day_sum", "day_profit", "day_profit_mlgame", "credit");
        $this->flexigrid->validate_post('query_date', 'desc', $valid_fields);

        $records = $this->ajax_model->get_admin_referal_day_stats($id);

        $this->output->set_header($this->config->item('json_header'));


        $prev_credit = 0;
        $cred_change=array();
        foreach ($records['records_credit']->result()  as $row) {
            $change = round($row->credit - $prev_credit,2);
            if ($change >= 0) {
                $change = "+" . $change;
            }
            $cred_change[$row->query_date] = "(" . $change . ")";
            $prev_credit = $row->credit;
        }

        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            //print("credit:".$row->credit." prevcred:".$prev_credit."   ");
            $record_items[] = array($i,
                $row->query_date,
                $row->spent,
                $row->earned,
                $row->inputted,
                $row->ref_paid,
                $row->ref_to_pay,
                $row->level,
                $row->day_sum,
                $row->day_profit,
                $row->day_profit_mlgame,
                $row->credit . " {$cred_change[$row->query_date]}"
            );
            $i++;
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function _remap($method, $params = array()) {
        $skip_methods = array("admin_frauds", "admin_referal_day_stats");
        if (method_exists($this, $method)) {
            if (!in_array($method, $skip_methods)) {
                $arr = $this->uri->ruri_to_assoc();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $arr = array_merge($arr, $_POST);
                }
                return call_user_func(array($this, $method), $arr);
            } else {
                return call_user_func_array(array($this, $method), $params);
            }
        }
        show_404();
    }

}

?>