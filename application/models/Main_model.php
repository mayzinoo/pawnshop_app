<?php
/**
*
*/
class Main_model extends CI_Model
{
	public function __construct()
	{

	}
	// sum amt
	function get_byspecificdata($vdate,$vr_type)
	{
					$spdata=$this->db->query("SELECT SUM(getmoney) as loantotal FROM redeem_tbl WHERE  CONCAT(YEAR(entry_date),'-',MONTH(entry_date))='$vdate' AND vr_type='$vr_type'")->row();
					return $spdata;
	}
	function get_byspecifirdata($vr_type)
	{
					$spdata=$this->db->query("SELECT SUM(getmoney) as loantotal FROM redeem_tbl WHERE vr_type='$vr_type'")->row();
					return $spdata;
	}
	function get_byspecifiremaindata($vr_type)
	{
		$spdata=$this->db->query("SELECT SUM(loan_amt) as remaintotal FROM collateral_tbl WHERE vr_type='$vr_type'")->row();
		return $spdata;
	}
	//
	// count voucher
	function cget_byspecificdata($vdate,$vr_type)
	{
					$spdata=$this->db->query("SELECT count(voucher) as loantotal FROM redeem_tbl WHERE  CONCAT(YEAR(entry_date),'-',MONTH(entry_date))='$vdate' AND vr_type='$vr_type'")->row();
					return $spdata;
	}
	function cget_byspecifirdata($vr_type)
	{
					$spdata=$this->db->query("SELECT count(voucher) as loantotal FROM redeem_tbl WHERE vr_type='$vr_type'")->row();
					return $spdata;
	}
	function cget_byspecifiremaindata($vr_type)
	{
		$spdata=$this->db->query("SELECT count(voucher) as remaintotal FROM collateral_tbl WHERE vr_type='$vr_type'")->row();
		return $spdata;
	}
	//
	public function insert($tablename,$data)
	{
	 	$this->db->insert($tablename,$data);
	 	return true;
	}
	public function update($tablename,$data,$field,$value)
	{
		$this->db->where($field,$value);
	 	$this->db->update($tablename,$data);
	 	return true;
	}
	public function delete($tablename,$field,$value)
	{
		$this->db->where($field,$value);
		$this->db->delete($tablename);
	 	return true;
	}
	public function getData($tablename)
	{
		$result= $this->db->order_by('posted_date',"DESC")->get($tablename);
		return $result;
	}
    public function getcvformData($tablename)
	{
		$result= $this->db->order_by('applyDate',"DESC")->get($tablename);
		return $result;
	}
	public function getallcvData($tablename)
	{
		$result= $this->db->order_by('applyDate',"DESC")->get($tablename);
		return $result;
	}
	public function getLimitData($tablename,$field,$value)
	{
		$this->db->where($field,$value);
		$result = $this->db->get($tablename);
		return $result->row_array();
	}

    function img_upload($files,$folder)
	{
		ini_set('upload_max_filesize','30M');
		ini_set('post_max_size','30M');
		if(!$files){
				return false;
		}else
		{
			$path='./images/'.$folder.'/';
			$config['overwrite']=TRUE;
		 	$config['upload_path']=$path;
		 	$config['remove_spaces'] = TRUE;
		   	$config['allowed_types'] = 'jpg|png|jpeg';
			$this->load->library('upload', $config);
			if(!$this->upload->do_upload($files))
			{
				echo $this->upload->display_errors();
			}
			else
			{
				return true;
			}
		}
	}
    function cv_upload($files,$folder)
	{
		ini_set('upload_max_filesize','30M');
		ini_set('post_max_size','30M');
		if(!$files){
				return false;
		}else
		{
			$path='./files/'.$folder.'/';
			$config['overwrite']=TRUE;
		 	$config['upload_path']=$path;
		 	$config['remove_spaces'] = TRUE;
		   	$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
			$this->load->library('upload', $config);
			if(!$this->upload->do_upload($files))
			{
				$error = array('error' => $this->upload->display_errors());
	            return $error;
			}
			else
			{
				return true;
			}
		}
	}
	function getfvoucherno()
	{
		$query = $this->db->query('SELECT voucher FROM collateral_tbl');

        return $query->result();
	}
	function getaddress()
	{
		$query = $this->db->query('SELECT address FROM location_tbl');
        return $query->result();
	}
	function getcitems()
	{
		$query = $this->db->query('SELECT citems FROM citems_tbl');

        return $query->result();
	}
 function getcategory()
 {
	 $query = $this->db->query('SELECT category FROM category_tbl');
	 return $query->result();
 }
	function allcollateralslost()
	{
	    $config["base_url"]=base_url()."Main/data_list/collateral_list/";
	    $config['total_rows'] = $this->db->get("collateral_tbl")->num_rows();
	    $config['per_page'] = 50;
	    $config['uri_segment'] = 4;
	    $config['num_links'] = 5;
	    $config['full_tag_open'] = '<ul class="pagegi">';
	    $config['full_tag_close'] = '</ul>';
	    $config['num_tag_open'] = '<li>';
	    $config['num_tag_close'] = '</li>';
	    $config['cur_tag_open'] = '<li><a class="current">';
	    $config['cur_tag_close'] = '</a></li>';
	    $config['prev_tag_open'] = '<li>';
	    $config['prev_tag_close'] = '</li>';
	    $config['next_tag_open'] = '<li>';
	    $config['next_tag_close'] = '</li>';
	    $config['prev_link'] = '<< Prev';
	    $config['next_link'] = 'Next >>';

	    $this->pagination->initialize($config);
	    $this->db->order_by('length(vr_no)','vr_no','asc');
	    $query=$this->db->get('collateral_tbl',$config['per_page'],$this->uri->segment(4));

	    return $query;
	}


}
?>
