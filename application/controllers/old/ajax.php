<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ajax_model');
        $this->load->library('flexigrid');
    }

    function stats($params = array()) {
        $valid_params = array("proj", "site", "date", "date_to", "excel");
        $params = elements($valid_params, $params);
        $params['proj'] = empty($params['proj']) ? config_item("default_project_id") : $params['proj'];

        $valid_fields = array('date', 'clicks', 'registers', 'active_regs', 'earnings');

        $this->flexigrid->validate_post('date', 'desc', $valid_fields);

        $records = $this->ajax_model->get_stats($params);

        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($i,
                $row->date,
                $row->clicks,
                "<a href='refs/all/proj/{$params['proj']}/reg_date/{$row->date}'>" . $row->registers . "</a>",
                "<a href='refs'>" . $row->active_regs . "</a>",
                "<a href='refs/by_date/proj/{$params['proj']}/date/{$row->date}'>" . $row->earnings . "</a>"
            );
            $i++;
        }
        //Print please
		if (!empty($params['excel'])){
			build_excel_data($record_items);
			redirect('stats');
		}	
		else
			$this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function refs($params = array()) {
	    $valid_params = array("reg_date", "proj", "show_active", "excel");
        $params = elements($valid_params, $params);

        $valid_fields = array('id', 'name', 'reg_date', 'spent', 'earned', 'inputted', 'profit', 'credit', 'level', 'reg_level', 'url');

        $this->flexigrid->validate_post('profit', 'desc', $valid_fields);

        $records = $this->ajax_model->get_refs($params);

        $this->output->set_header($this->config->item('json_header'));
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($row->id,
                "<a href='ref/view/{$row->id}'>" . $row->name . "</a>",
                substr($row->reg_date, 0, 10),
                $row->profit,
                $row->spent,
			//	$row->inputted,
            //    $row->earned,
            //    $row->credit,
                $row->level,
                $row->reg_level,
                empty($row->url) ? "NULL" : $row->url
            );
        }
        //Print please
		
	//	var_dump($params);
		
		if (!empty($params['excel'])){
			build_excel_data($record_items);
			redirect('refs');
		}	
		else
			$this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function finance($params = array()) {
        $valid_fields = array('sum', 'date', 'type', 'excel');
        $this->flexigrid->validate_post('date', 'desc', $valid_fields);
        $records = $this->ajax_model->get_finance();
        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $type = "Партнерка";
            if ($row->type == "loyalty")
                $type = "Лояльность";
            $record_items[] = array($i,
                substr($row->date, 0, 10),
                $row->sum,
                $type
            );
            $i++;
        }
		if (!empty($params['excel'])){
			build_excel_data($record_items);
			redirect('finance');
		}	
		else
			$this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function loyalty($params = array()) {
        $valid_fields = array('name', 'earnings', 'registers', 'parent_loyalty_percent', 'active_regs', 'active_regs_reg_day', 'excel');
        $this->flexigrid->validate_post('earnings', 'desc', $valid_fields);
        $records = $this->ajax_model->get_loyalty();
        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($i,
                $row->name,
                $row->earnings,
                $row->registers,
                $row->active_regs,
                $row->active_regs_reg_day,
                $row->parent_loyalty_percent,
                $row->my_earnings
            );
            $i++;
        }
		if (!empty($params['excel'])){
			build_excel_data($record_items);
			redirect('loyalty');
		}	
		else
			$this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function sites($params = array()) {
        $valid_fields = array('gs.url', 'gs.attendance', 'ps.clicks', 'ps.registers', 'ps.active_regs', 'ps.earnings', 'ps.active_regs_reg_day', 'excel');
        $this->flexigrid->validate_post('url', 'desc', $valid_fields);
        $records = $this->ajax_model->get_sites();
        $this->output->set_header($this->config->item('json_header'));
        $i = 0;
        $record_items = null;
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($i,
                "<a href='http://{$row->url}' target='_blank'>" . $row->url . "</a>",
                $row->attendance,
                $row->clicks,
                $row->registers,
                $row->active_regs_reg_day,
                $row->active_regs,
                $row->earnings
            );
            $i++;
        }
		if (!empty($params['excel'])){
			build_excel_data($record_items);
			redirect('sites');
		}	
		else
			$this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function refs_by_date($params = array()) {
        $valid_params = array("date", "proj");
        $params = elements($valid_params, $params);

        $valid_fields = array('id', 'name', 'reg_date', 'prds.query_date', 'prds.spent', 'prds.earned', 'prds.inputted', 'prds.day_profit', 'prds.credit', 'site');

        $this->flexigrid->validate_post('prds.query_date', 'desc', $valid_fields);

        $records = $this->ajax_model->get_refs_by_date($params);
        $record_items = null;
        $this->output->set_header($this->config->item('json_header'));
        foreach ($records['records']->result() as $row) {
            $record_items[] = array($row->id,
                "<a href='ref/view/{$row->id}'>" . $row->name . "</a>",
                $row->query_date,
                $row->reg_date,
                $row->day_profit,
                $row->inputted,
                $row->spent,
                $row->earned,
                $row->credit,
                empty($row->site) ? "NULL" : $row->site
            );
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function ref_day_stats($id) {
        $valid_fields = array("query_date", "spent", "earned", "inputed", "ref_paid", "ref_to_pay", "t1.level", "day_sum", "day_profit", "day_profit_mlgame", "credit");
        $this->flexigrid->validate_post('query_date', 'desc', $valid_fields);

        $records = $this->ajax_model->get_referal_day_stats($id);

        $this->output->set_header($this->config->item('json_header'));

        $prev_credit = 0;
        $cred_change = array();
        foreach ($records['records_credit']->result() as $row) {
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
                $row->credit ." {$cred_change[$row->query_date]}"
            );
            $i++;
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items, $records['sums']));
    }

    function _remap($method, $params = array()) {
        $skip_methods = array("admin_frauds", "admin_referal_day_stats", "ref_day_stats");
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