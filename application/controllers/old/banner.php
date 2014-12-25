<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Banner extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
		$this->load->model("project_model","pm");
        $this->load->library("project");
		
		$user = $this->pm->get_user_info($this->user->id);
		$params['ref'] = $user ? $user->ref_code : $this->input->get('ref'); 
		$data['ref_link_flash'] = urlencode($this->project->gen_redirect_url($params));
		$data['ref_link_image'] = $this->project->gen_redirect_url($params);
		
		$this->db->from('p1_banners');
		$this->db->where('active', 1);
		$banners = $this->db->get();
		
		$data['banner_flash'] = array();
		$data['banner_image'] = array();
		
		foreach ($banners->result() as $banner){
			if ($banner->type == 'swf')
				$data['banner_flash'][] = $banner;
			else
				$data['banner_image'][] = $banner;
		}	
		
        $this->load->view("banner_view", $data);
    }
	
	
}	