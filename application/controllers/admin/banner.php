<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Banner extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->user->is_admin())
            redirect("/");
    }

    public function index() {
        $this->load->helper('flexigrid');
		
     //   $colModel['id'] = array('ID', 105, TRUE, 'center', 2);
        $colModel['name'] = array('Имя', 60, TRUE, 'center', 2);
		$colModel['file_name'] = array('Имя файла', 60, TRUE, 'center', 2);
        $colModel['active'] = array('Статус', 60, TRUE, 'center', 0);
        $colModel['width'] = array('Ширина', 60, TRUE, 'left', 0);
        $colModel['height'] = array('Высота', 60, TRUE, 'left', 0);
        $colModel['type'] = array('Тип', 80, TRUE, 'left', 0);
        
        $gridParams = array(
            'width' => 'auto',
            'height' => 'auto',
            'rp' => 15,
            'rpOptions' => '[10,15,20,25,40,100,1000,5000,10000]',
            'pagestat' => 'Показать: от {from} до {to} из {total} записей.',
            'blockOpacity' => 0.5,
            'title' => 'Баннера',
            'showTableToggleBtn' => true
        );

        $data['js_grid'] = build_grid_js('flex1', 
				site_url("/admin/ajax/admin_banner/"), 
				$colModel, 'date', 'desc', $gridParams);
        
        $d['cont'] = $this->load->view("admin/banner_view", $data, true);
        $this->load->view("global_view", $d);
    }
	
	public function add() {
	//	echo ini_get('upload_tmp_dir');
	//	@ini_set('upload_tmp_dir', '/var/www/usr0/data/mod-tmp');
		$config['upload_path'] = './banners/';
		$config['overwrite'] = true;
		$config['allowed_types'] = 'swf|gif|jpg|png';
		$config['max_size']	= '80';
		$config['max_width']  = '728';
		$config['max_height']  = '600';

		
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('upload', $config);
		$this->load->library('form_validation');

	//	$this->upload->initialize($config);
						
        $this->form_validation->set_rules('name', 'Имя баннера', 'trim|required');
        $this->form_validation->set_rules('wh', 'Ширина*Высота', 'required');
		
        $params['name']	= $this->input->post('name', true);
		$params['wh']	= $this->input->post('wh');
		$params['type']	= $this->input->post('type');
		
		if ($this->form_validation->run() == true){
			if ($this->input->post('submit')){
				if ( ! $this->upload->do_upload('banner'))
					$data['upload_error'] = $this->upload->display_errors();
				else{
					$data_file = $this->upload->data();
					
					// insert banner info
					$wh = explode('*', $params['wh']);
					$this->db->set('name', $params['name']);
					$this->db->set('width', $wh[0]);
					$this->db->set('height', $wh[1]);
					$this->db->set('type', $params['type']);
					$this->db->insert('p1_banners');
					$id = $this->db->insert_id();
					
					// rename file
					if (rename($data_file["full_path"], $data_file["file_path"].$id.$data_file["file_ext"])){
						// update file name
						$this->db->set('file_name', $id.$data_file["file_ext"]);
						$this->db->where('id', $id);
						$this->db->update('p1_banners');
					}
					
					redirect ('admin/banner');
					
				}
			}	
		}		
		
		$data['name'] = !empty($params['name']) ? $params['name'] : '';
		$data['wh']	  = !empty($params['wh']) ? $params['wh'] : '';
		$data['type'] = !empty($params['type']) ? $params['type'] : '';
		
		$data['whs'] = array(
			""			=> "Выберите размер",
			"468*60"	=> "468*60",
			"240*400"	=> "240*400",
			"200*200"	=> "200*200",
			"300*250"	=> "300*250",
			"160*600"	=> "160*600",
			"728*90"	=> "728*90", 
			"120*600"	=> "120*600",
			);
        
		$data['types'] = array(
			"swf"	=> "swf",
			"jpg"	=> "jpg",
			"gif"	=> "gif",
			"png"	=> "png",
			);
        	
        $d['cont'] = $this->load->view("admin/banner_add_view", $data, true);
        $this->load->view("global_view", $d);
    }
	
	public function active($id) {
		if (!empty($id)){
			$this->db->set('active', '!`active`', false);
			$this->db->where('id', $id);
			$this->db->update('p1_banners');
		}	
			
		redirect ('admin/banner');
	}
}	