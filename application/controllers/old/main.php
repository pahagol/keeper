<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index() {
		var_dump(1234234);die();
		
		//$this->output->enable_profiler();
		// if ($this->user->is_admin())
		//   redirect("admin");
		$this->load->model("project_model", "pm");
		$this->load->model("global_model", "gm");
		$u = $this->user->info();

		$data['user_projects'] = $this->pm->get_user_projects($this->user->id);
		//print_r($data['user_projects']);
		if ($data['user_projects']) {
			foreach ($data['user_projects'] as $p) {
				$this->pm->project = $p->id;
				$data['project_stats'][$p->id] = $this->pm->get_project_stats($this->user->id);
				$data['last_project_stats'][$p->id] = $this->pm->get_last_project_stats($this->user->id);
				$user[$p->id] = $this->pm->get_user_info($this->user->id);
				$data['percent'][$p->id] = $user[$p->id]->percent;
			}
		}
		//print_r($data['project_stats']);
		$skey_str = empty($u->skey) ? "" : "/{$u->skey}";
		$data['xml_link'] = site_url("report/view/{$u->id}" . $skey_str);
		$data['user'] = $u;
		$data['wmz_num']=$u->wmz_num;
		$data['count_invited'] = $this->gm->get_count_children($this->user->id);
		$data['loyalty_earned'] = $u->loyalty_earnings;
		$data['loyalty_to_pay'] = $u->loyalty_sum_to_pay;
		$data['all_projects'] = $this->pm->get_all_projects();
		$d['cont'] = $this->load->view('main_view', $data, true);
		$this->load->view("global_view", $d);
	}

	function connect($p_id) {
		//$this->load->model("project_model","pm");
		// $this->pm->project=$p_id;
		// $this->pm->add_user($this->user->id);
		if ($this->user->is_active()) {
			$this->load->library("project");
			$this->project->add_user($this->user->id);
		} else {
			$this->session->set_flashdata("msg", array("text" => "Невозможно подключиться к проекту. Аккаунт не активирован", "type" => "error"));
		}
		redirect("main");
	}

	function ajax_save() {
		$name = $this->input->post("name");
		$val = $this->input->post("val");
		$this->load->model("global_model","gm");
		if ($name=="skey" && !empty($val)) {
			$this->db->update("global_users", array("skey" => $val), array("id" => $this->user->id));
		}elseif($name=="wmz_num" && !empty($val)){
			$u=$this->user->info();
			$this->db->trans_start();
			$this->db->update("global_users",array("wmz_num"=>$val),array("id"=>$this->user->id));
			$this->gm->add_log($this->user->id, "change_user_wmz_num", $val,$u->wmz_num);
			$this->db->trans_complete();
			if($this->db->trans_status() === TRUE){
				send_admin_mail("MLGame Partner. Изменение Номера кошелька Партнера", "Партнер {$u->name} изменил кошелек на $val");
			}
		}
	}

}

/* End of file main.php */
/* Location: ./application/controllers/index.php */
