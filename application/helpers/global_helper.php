<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * Is Authorized
 *
 * Checks whether user is authorized
 *
 * @access	public
 * @return	bool	
 */
function is_authorized() {
    //$CI=&get_instance();
    if (get_cookie("logged") == 1)
        return true;else
        return false;
}


function send_admin_mail($subj, $msg, $mailtype = 'text') {
    $CI = &get_instance();
    $CI->load->library("email");
	
	$config['mailtype'] = $mailtype;
	$CI->email->initialize($config);
    
	$CI->email->from('no-reply@2056.aratog.com', 'Apocalypse 2056 Partner System');
    $CI->email->to("support@2056.aratog.com");
    $CI->email->subject($subj);
    $CI->email->message($msg);
    $CI->email->send();
    //echo $CI->email->print_debugger();
}

function send_user_mail($email, $subj, $msg, $mailtype = 'text') {
    $CI = &get_instance();
    $CI->load->library("email");
	
	$config['mailtype'] = $mailtype;
	$CI->email->initialize($config);
    
    $CI->email->from('no-reply@2056.aratog.com', 'Apocalypse 2056 Partner System');
	$CI->email->to($email);
    $CI->email->subject($subj);
    $CI->email->message($msg);

    $CI->email->send();
    //echo $CI->email->print_debugger();
}

function build_excel_data($items) {
    error_reporting(E_ALL);
    $CI = &get_instance();
    $CI->load->library("PHPExcel", null, "excel");
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator("PHP")
            ->setLastModifiedBy("Affiliate")
            ->setTitle("Affiliate stats")
            ->setSubject("OAffiliate stats")
            ->setDescription("affiliate mlgame")
            ->setKeywords("mlgame affiliate")
            ->setCategory("Affiliate category");
    $objPHPExcel->getActiveSheet()->setTitle('Affiliate');


    //$col = 0;
    //foreach ($fields as $field) {
    //    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
    //    $col++;
    // }
    // Fetching the table data
    $row = 2;
    foreach ($items as $values) {
        $col = 0;
        foreach ($values as $val) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $val);
            $col++;
        }
        $row++;
    }


    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="affiliate.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
}

function getColLetter($i) {
    $COLS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $ct = ($i > 25) ? floor($i / 26) : 0;
    $ret = $COLS[$i % 26];
    while ($ct--)
        $ret .= $ret;
    return $ret;
}

function get_user_status_transalate($status) {
    $return = $status;
    switch ($status) {
        case "registered":
        case "active":
            $return = "Активен";
            break;
        case "new":
            $return = "Новый";
            break;
        case "inactive":
            $return = "Не активен";
            break;
        case "moderate":
            $return = "На модерации";
            break;
        case "banned":
        case "blocked":
            $return = "Заблокирован";
            break;
        case "not_confirmed":
            $return = "Не подтвержден";
            break;
    }
    return $return;
}

// ------------------------------------------------------------------------



/* End of file global_helper.php */
/* Location: ./system/helpers/global_helper.php */