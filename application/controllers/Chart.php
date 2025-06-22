<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Chart extends CI_Controller {
 
    public function __construct() {
        parent::__construct();
        // load model
        $this->load->database();
        $this->load->helper(array('url','html','form'));
    }
    public function morris_area_line_chart() {
      // for area and line chart
      $dayQuery =  $this->db->query("SELECT  DAY(created_at) as y, COUNT(id) as a FROM users WHERE MONTH(created_at) = '" . date('m') . "'
        AND YEAR(created_at) = '" . date('Y') . "'
      GROUP BY DAY(created_at)"); 
 
      $record = $dayQuery->result();
 
      $data['chart_data'] = json_encode( $record );
      //print_r($data['chart_data']);die;
      $this->load->view('morris_line_area_chart',$data); 
    }
}
?>