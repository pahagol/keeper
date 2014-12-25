<?php
class Flexigrid extends CI_Controller {

	function index()
	{
	   $this->load->helper('flexigrid');
		//ver lib
		
		/*
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */
		$colModel['date'] = array('Date',40,TRUE,'center',2);
		$colModel['clicks'] = array('Clicks',40,TRUE,'center',0);
		$colModel['regs'] = array('Regs',180,TRUE,'left',0);
		$colModel['active'] = array('Active',120,TRUE,'left',0);
		$colModel['earnings'] = array('Earnings',130, TRUE,'left',0);
		
		
		/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 'auto',
		'height' => 400,
		'rp' => 15,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'Stats',
		'showTableToggleBtn' => true
		);
		
		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
		//$buttons[] = array('Delete','delete','test');
		//$buttons[] = array('separator');
		//$buttons[] = array('Select All','add','test');
		//$buttons[] = array('DeSelect All','delete','test');
		//$buttons[] = array('separator');

		
		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		$grid_js = build_grid_js('flex1',site_url("/ajax"),$colModel,'id','asc',$gridParams);
		
		$data['js_grid'] = $grid_js;
		$data['version'] = "0.36";
		$data['download_file'] = "Flexigrid_CI_v0.36.rar";
		
		$this->load->view('flexigrid',$data);
	}
	
	function example () 
	{
		$data['version'] = "0.36";
		$data['download_file'] = "Flexigrid_CI_v0.36.rar";
		
		$this->load->view('example',$data);	
	}
}
?>