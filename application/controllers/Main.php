<?php
	/**
	*
	*/
	if(!defined('BASEPATH'))
		exit('No direct script acceess allowed');

	class Main extends CI_Controller
	{
		function __construct()
		{
            parent::__construct();

           // error_reporting(1);
  	    }
  	    
		public function index()
		{
			$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
			$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
			$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"))
			;
			$data['totaloutcome']=$this->db->get('in_outcome_tbl');
			// $data['content']='admin_login';
			$this->load->view('admin/admin_login',$data);
		}
		function create_collateral_form()
		{

			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				// $data["mydays"]=date('t');

				// $data["mymonth"]=date('m');
				// $month=$this->db->query("SELECT * FROM voucher_tbl WHERE month=MONTH(CURDATE())")->num_rows();
				// if($month>=1){
				// 	$voucher=$this->db->query("SELECT vr_no FROM collateral_tbl Order by vr_no DESC LIMIT 1")->row();
				// 	$data["vrno"]=$voucher+1;
				// }
				// else{

				// 	$data["vrno"]=1;
				// }

				$monthNum =date('m');
				$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));/*get month name*/
				$data['monthname']=$monthName;


				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$data["totalvoucher"]=$this->db->select('*,count(voucher) AS total')

									->get_where('collateral_tbl',array('entry_date'=>date('Y-m-d')))->row();

				$data["totalloanamt"]=$this->db->select('*,SUM(loan_amt) AS loan_total')
									->get_where('collateral_tbl',array('entry_date'=>date('Y-m-d')))->row();
				/*daily total*/

				$data["mtotalvoucher"]=$this->db->query("SELECT count(voucher) as total FROM collateral_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();

				$data["mtotalloanamt"]=$this->db->query("SELECT SUM(loan_amt) AS loan_total FROM collateral_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();



				/*monthly total*/
				$query=$this->db->get('collateral_tbl');
				$query->num_rows();

				if($query->num_rows()==0){
					$data['id']=0;
				}
				else{
					$query=$this->db->query("SELECT * FROM collateral_tbl order by id desc limit 1")->row();
					$data['id']=$query->id;

					}

				$data['addresslist']=$this->Main_model->getaddress();
				$data['citemslist']=$this->Main_model->getcitems();
				$data['content']='create_collateral';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}

		}
		/*collateral edit form*/
		function collateraledit_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$monthNum =date('m');
				$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));/*get month name*/
				$data['monthname']=$monthName;


				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data["totalvoucher"]=$this->db->select('*,count(voucher) AS total')

									->get_where('collateral_tbl',array('entry_date'=>date('Y-m-d')))->row();

				$data["totalloanamt"]=$this->db->select('*,SUM(loan_amt) AS loan_total')
									->get_where('collateral_tbl',array('entry_date'=>date('Y-m-d')))->row();
				/*daily total*/

				$data["mtotalvoucher"]=$this->db->query("SELECT count(voucher) as total FROM collateral_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();

				$data["mtotalloanamt"]=$this->db->query("SELECT SUM(loan_amt) AS loan_total FROM collateral_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();

				$data['addresslist']=$this->Main_model->getaddress();
				$id= $this->uri->segment(3);
				$data['collateraldata']=$this->db->get_where('collateral_tbl',array('id'=>$id))->row();
				$data['content']='edit_collateral';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function collateral_insert()
		{

			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$entryby=$this->input->post('entryby');
				$entrydate=date("Y-m-d",strtotime($this->input->post("entrydate")));
				// $monthdate=date("m",strtotime($this->input->post('entrydate')));

				$vrtype=$this->input->post('vrtype');
				$vrno=$this->input->post('vrno');
				$customername=$this->input->post('customername');

				$address=$this->input->post('address');
				$collateral=$this->input->post('collateral');
				$collateral_qty=$this->input->post('collateral_qty');
				$kyat=$this->input->post('kyat');
				$pe=$this->input->post('pe');
				$ywe=$this->input->post('ywe');

				$loan=$this->input->post('loan');
				$loan_parsed =str_replace(',', '', $loan);

				$coll="";
		    	for($i=0;$i<count($collateral);$i++)
		    	{
		    		$coll .= $collateral[$i].",". $collateral_qty[$i]."]";
		    	}

				$data=array(
					'entry_date'=> $entrydate,
					'entry_by'=> $entryby,

					'vr_type'=> $vrtype,
					'vr_no'=> $vrno,
					'voucher'=> $vrtype.$vrno,
					'customer_name'=> $customername,
					'address'=> $address,
					'collateral'=> $coll,
					// 'collateral_qty'=> $collateral_qty,
					'kyat'=> $kyat,
					'pe'=> $pe,
					'ywe'=> $ywe,
					'loan_amt'=> $loan_parsed,
					'status'=> 1
				);
				$this->Main_model->insert("collateral_tbl",$data);

				$stockdata=array(
					'entry_date'=> $entrydate,
					'entry_by'=> $entryby,
					'voucher'=> $vrtype.$vrno,
					'customer_name'=> $customername,
					'address'=> $address,
					'loan_amt'=> $loan_parsed,
					'stock_item'=> $coll,
					'kyat'=> $kyat,
					'pe'=> $pe,
					'ywe'=> $ywe
					);
				$this->Main_model->insert("collateral_stock_tbl",$stockdata);
				// parse_str("zero=0");
				// $voucherdata=array(
				// 	'month'=>$monthdate,
				// 	'vr_no'=>$zero.$vrno
				// );
				// $this->Main_model->insert("voucher_tbl",$voucherdata);
				redirect('Main/create_collateral_form');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function collateral_edit()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$id=$this->input->post('id');
				$entrydate=date("Y-m-d",strtotime($this->input->post("entrydate")));
				$srno=$this->input->post('srno');
				$vrtype=$this->input->post('vrtype');
				$vrno=$this->input->post('vrno');
				$customername=$this->input->post('customername');

				$address=$this->input->post('address');
				$collateral=$this->input->post('collateral');
				$collateral_qty=$this->input->post('collateral_qty');
				$kyat=$this->input->post('kyat');
				$pe=$this->input->post('pe');
				$ywe=$this->input->post('ywe');
				$loan=$this->input->post('loan');
				$loan_parsed =str_replace(',', '', $loan);

				$coll="";
		    	for($i=0;$i<count($collateral);$i++)
		    	{
		    		$coll .= $collateral[$i].",". $collateral_qty[$i]."]";
		    	}

				$data=array(
					'entry_date'=> $entrydate,
					'entry_by'=> $entryby,

					'vr_type'=> $vrtype,
					'vr_no'=> $vrno,
					'voucher'=> $vrtype.$vrno,
					'customer_name'=> $customername,
					'address'=> $address,
					'collateral'=> $coll,
					// 'collateral_qty'=> $collateral_qty,
					'kyat'=> $kyat,
					'pe'=> $pe,
					'ywe'=> $ywe,
					'loan_amt'=> $loan_parsed,
					'status'=> 1
				);

				$this->db->where('id',$id);
				$this->db->update("collateral_tbl",$data);

				$stockdata=array(
					'entry_date'=> $entrydate,
					'entry_by'=> $entryby,
					'voucher'=> $vrtype.$vrno,
					'customer_name'=> $customername,
					'address'=> $address,
					'loan_amt'=> $loan_parsed,
					'stock_item'=> $coll,
					'kyat'=> $kyat,
					'pe'=> $pe,
					'ywe'=> $ywe
					);
				// $this->Main_model->insert("collateral_stock_tbl",$stockdata);
				$this->db->where('id',$id);
				$this->db->update("collateral_stock_tbl",$stockdata);
				redirect('Main/data_list/collateral_list/');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function collateral_delete()
		{
		    $id= $this->uri->segment(3);

    		$this->Main_model->delete("collateral_tbl",'id',$id);

    		$this->Main_model->delete("collateral_stock_tbl",'id',$id);
    		redirect('Main/data_list/collateral_list/');
		}
		function multiple_collateral_delete()
		{
			$id = $this->input->post('collateraldelete');
    		// echo $id;
		    $id=implode(",",$id);
		    // print_r($id);exit;
		    if($id==""){
		    	echo "Please Select Item!";
		    }
		    else{
		    	$data['lists']=$this->db->query("DELETE FROM collateral_tbl where id IN (".$id.")");
		    	redirect('Main/data_list/collateral_list/');
		    }

		}
		function multiple_redeem_delete()
		{
			$id = $this->input->post('redeemdelete');
    		// echo $id;
		    $id=implode(",",$id);
		    // print_r($id);exit;
		    if($id==""){
		    	echo "Please Select Item!";
		    }
		    else{
		    	$data['lists']=$this->db->query("DELETE FROM redeem_tbl where id IN (".$id.")");
		    	redirect('Main/data_list/redeemList/');
		    }
		}
		function multiple_stock_delete()
		{
			$id = $this->input->post('allstockdelete');
    		// echo $id;
		    $id=implode(",",$id);
		    // print_r($id);exit;
		    $data['lists']=$this->db->query("DELETE FROM collateral_stock_tbl where id IN (".$id.")");
		    redirect('Main/data_list/stockList/');
		}
		function multiple_budget_delete()
		{
			$id = $this->input->post('budgetdelete');
    		// echo $id;
		    $id=implode(",",$id);
		    // print_r($id);exit;
		    $data['lists']=$this->db->query("DELETE FROM in_outcome_tbl where id IN (".$id.")");
		    redirect('Main/data_list/outcomeLists/');
		}
		function multiple_unabletoredeem_delete()
		{
			$id = $this->input->post('unabletoredeemdelete');
			$id=implode(",",$id);
		    // print_r($id);exit;
		    $data['lists']=$this->db->query("DELETE FROM collateral_stock_tbl where unabletoredeem='0' and id IN (".$id.")");
		    redirect('Main/data_list/outcomeLists/');
		}
		function checkvoucher()
		{
			$vrno=$this->input->post("vrno");

			$this->db->where("voucher",$vrno);
			$query = $this->db->get("collateral_tbl")->row();
			$result=$query->voucher;
			echo $result;
		}
		function checkredeemvoucher()
		{
			$voucher=$this->input->post("voucher");

			$this->db->where("voucher",$voucher);
			$query = $this->db->get("redeem_tbl")->row();
			$result=$query->voucher;
			echo $result;
		}
		function collateral_list()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');


				$data['collateralList']=$this->db->get('collateral_tbl');

				$data['content']='collateral_list';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function dailyremain_list()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');


				$data['dailyremainlist']=$this->db->get('collateral_tbl');

				$data['content']='daily_remainList';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function create_citem_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$data['content']='create_citems';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function create_address_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$data['content']='create_address';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function create_category_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$data['content']='create_category';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function edit_address_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$id=$this->uri->segment(3);

				$data["addresslist"]=$this->db->query("SELECT * FROM location_tbl WHERE id='$id'")->row();

				$data['content']='edit_address';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function edit_category_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$id=$this->uri->segment(3);

				$data["categorylist"]=$this->db->query("SELECT * FROM category_tbl WHERE id='$id'")->row();

				$data['content']='edit_category';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function edit_citems_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$id=$this->uri->segment(3);

				$data["citemslist"]=$this->db->query("SELECT * FROM citems_tbl WHERE id='$id'")->row();

				$data['content']='edit_citems';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function create_outcome_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$data["totalincome"]=$this->db->select('*,SUM(income_amt) AS incometotal')
														->get_where('in_outcome_tbl',array('entry_date'=>date('Y-m-d')))->row();
				$data["totaloutcome"]=$this->db->select('*,SUM(outcome_amt) AS outcometotal')
																								->get_where('in_outcome_tbl',array('entry_date'=>date('Y-m-d')))->row();

				$data['categorylist']=$this->Main_model->getcategory();
				$data['content']='create_outcome';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function address_insert()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$entrydate=date("Y-m-d");
				$address=$this->input->post('address');

				$data=array(
					'entry_date'=> $entrydate,
					'address'=> $address
				);
				$this->Main_model->insert("location_tbl",$data);

				redirect('Main/create_address_form');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function category_insert()
		{
				if($this->session->userdata("username") && $this->session->userdata("password"))
				{
					$entrydate=date("Y-m-d");
					$sign=$this->input->post('sign');
					$category=$this->input->post('category');

					$data=array(
						'created_at'=> $entrydate,
						'sign'=> $sign,
						'category'=> $category
					);
					$this->Main_model->insert("category_tbl",$data);

					redirect('Main/create_category_form');
				}
				else
				{
					$data["message"]="Login to access this page";
					redirect('Main/admin_form');
				}
		}
		function citems_insert()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$entrydate=date("Y-m-d");
				$citems=$this->input->post('citems');

				$data=array(
					'entry_date'=> $entrydate,
					'citems'=> $citems
				);
				$this->Main_model->insert("citems_tbl",$data);

				redirect('Main/create_citem_form');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function address_edit()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$id=$this->input->post('id');
				$entrydate=date("Y-m-d");
				$address=$this->input->post('address');

				$data=array(
					'address'=> $address
				);
				// $this->Main_model->insert("location_tbl",$data);
				$this->db->where('id',$id);
				$this->db->update("location_tbl",$data);

				redirect('Main/data_list/addressList');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function budget_edit()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$id=$this->input->post('id');
				$sign=$this->input->post('sign');
				$category=$this->input->post('category');
				$outcome_amt=$this->input->post('outcome_amt');
				$income_amt=$this->input->post('income_amt');

				if($income_amt==""){
					$data=array(
						'sign'=> $sign,
						'category'=> $category,
						'outcome_amt'=> $outcome_amt
					);
				}
				else{
					$data=array(
						'sign'=> $sign,
						'category'=> $category,						
						'income_amt'=> $income_amt
					);
				}
				
				// $this->Main_model->insert("location_tbl",$data);
				$this->db->where('id',$id);
				$this->db->update("in_outcome_tbl",$data);

				redirect('Main/data_list/outcomeList');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function category_edit()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$id=$this->input->post('id');
				$sign=$this->input->post('sign');
				$category=$this->input->post('category');			
				
					$data=array(
						'sign'=> $sign,
						'category'=> $category
					);			
				
				// $this->Main_model->insert("location_tbl",$data);
				$this->db->where('id',$id);
				$this->db->update("category_tbl",$data);

				redirect('Main/data_list/category');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function citems_edit()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$id=$this->input->post('id');
				$entrydate=date("Y-m-d");
				$citems=$this->input->post('citems');

				$data=array(
					'citems'=> $citems
				);

				$this->db->where('id',$id);
				$this->db->update("citems_tbl",$data);

				redirect('Main/data_list/citemsList');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function address_delete()
		{
		    $id= $this->uri->segment(3);

    		$this->Main_model->delete("location_tbl",'id',$id);


    		redirect('Main/data_list/addressList/');
		}
		function category_delete()
		{
		    $id= $this->uri->segment(3);

    		$this->Main_model->delete("category_tbl",'id',$id);


    		redirect('Main/data_list/category/');
		}
		function budget_delete()
		{
		    $id= $this->uri->segment(3);

    		$this->Main_model->delete("in_outcome_tbl",'id',$id);


    		redirect('Main/data_list/outcomeList');
		}
		function unabletoredeem_delete()
		{
		    $id= $this->uri->segment(3);

    		$this->Main_model->delete("collateral_stock_tbl",'id',$id);

    		redirect('Main/data_list/unabletoredeemList/');
		}

		function income_outcome_insert()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$entrydate=date("Y-m-d",strtotime($this->input->post('entrydate')));
				$sign=$this->input->post('sign');
				$category=$this->input->post('category');
				$income_amt=$this->input->post('income_amt');
				$inamt_parsed =str_replace(',', '', $income_amt);
				$outcome_amt=$this->input->post('outcome_amt');
				$outamt_parsed =str_replace(',', '', $outcome_amt);
				for($i=0;$i<count($category);$i++)
            {
							if($income_amt==""){
								$arr=array(
										'entry_date'=> $entrydate,
										'sign'=>$sign[$i],
										'category'=> $category[$i],
										// 'income_amt'=> $income_amt[$i],
										'outcome_amt'=> $outamt_parsed[$i]
										);
								$this->db->insert("in_outcome_tbl",$arr);
							}
							else{
								$arr=array(
										'entry_date'=> $entrydate,
										'sign'=>$sign[$i],
										'category'=> $category[$i],
										'income_amt'=> $inamt_parsed[$i]
										// 'outcome_amt'=> $outcome_amt[$i]`	`
                    );
                $this->db->insert("in_outcome_tbl",$arr);
							}

            }
				redirect('Main/create_outcome_form');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function create_redeem_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$monthNum =date('m');
				$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));/*get month name*/
				$data['monthname']=$monthName;


				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');


				$user=$this->session->userdata('username');

				$query=$this->db->get('collateral_tbl');
				$query->num_rows();
				if($query->num_rows()==0){
					$data['id']=0;
				}
				else{
					$query=$this->db->query("SELECT * FROM redeem_tbl order by id desc limit 1")->row();
					$data['id']=$query->id;

					}

				$data['voucherlist']=$this->Main_model->getfvoucherno();
				$data['getvrtype']=$this->db->query("SELECT SUBSTR(vr_type, 4) FROM collateral_tbl As vrtype")->row();
				$data['collateraldata']=$this->db->get("collateral_tbl",$data)->row();

				$data["totalvoucher"]=$this->db->select('*,count(voucher) AS total')

									->get_where('redeem_tbl',array('entry_date'=>date('Y-m-d')))->row();
				$data["totalloanamt"]=$this->db->select('*,SUM(getmoney) AS loan_total')
									->get_where('redeem_tbl',array('entry_date'=>date('Y-m-d')))->row();
				$data["totalnetamt"]=$this->db->select('*,SUM(realget_money) AS nettotal')
														->get_where('redeem_tbl',array('entry_date'=>date('Y-m-d')))->row();
				$data["totalcalcuamt"]=$this->db->select('*,SUM(calculate_money) AS calcutotal')
																								->get_where('redeem_tbl',array('entry_date'=>date('Y-m-d')))->row();
				$data["totalbalanceamt"]=$this->db->select('*,SUM(balance_money) AS balancetotal')->get_where('redeem_tbl',array('entry_date'=>date('Y-m-d')))->row();
				$data["mtotalvoucher"]=$this->db->query("SELECT count(voucher) as total FROM redeem_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();

				$data["mtotalloanamt"]=$this->db->query("SELECT SUM(getmoney) AS loan_total FROM redeem_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();

				$data["mtotalnetamt"]=$this->db->query("SELECT SUM(realget_money) AS nettotal FROM redeem_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();

				$data["mtotalcalculate"]=$this->db->query("SELECT SUM(calculate_money) AS calcutotal FROM redeem_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();

				$data["mtotalbalance"]=$this->db->query("SELECT SUM(balance_money) AS balancetotal FROM redeem_tbl WHERE MONTH(entry_date)=MONTH(CURDATE())")->row();

				$data['content']='create_redeem';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function budgetedit_form()
		{
			echo "dsfs";
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');

				$user=$this->session->userdata('username');

				$id= $this->uri->segment(3);
				$data["budgetdata"]=$this->db->query("SELECT * from in_outcome_tbl WHERE id='$id'")->row();
				$data['content']='edit_budget';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function redeemedit_form()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');


				$user=$this->session->userdata('username');

				$query=$this->db->get('collateral_tbl');
				$query->num_rows();
				if($query->num_rows()==0){
					$data['id']=0;
				}
				else{
					$query=$this->db->query("SELECT * FROM redeem_tbl order by id desc limit 1")->row();
					$data['id']=$query->id;

					}

				$data['voucherlist']=$this->Main_model->getfvoucherno();
				$id= $this->uri->segment(3);
				$data["redeemdata"]=$this->db->select('redeem_tbl.*,collateral_tbl.status as status')
										 ->join('collateral_tbl', 'redeem_tbl.voucher=collateral_tbl.voucher', 'left')
										->get_where('redeem_tbl',array('redeem_tbl.id'=>$id))->row();
				$data['content']='edit_redeem';
				$this->load->view('template',$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function redeem_insert()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{

				$entryby=$this->input->post('entryby');
				$entrydate=date("Y-m-d",strtotime($this->input->post('entrydate')));
				$voucher=$this->input->post('voucher');
				// echo $voucher;exit;
				$customername=$this->input->post('name');
				$address=$this->input->post('address');
				$getmoney=$this->input->post('getmoney');
				$totalmonth=$this->input->post('totalmonth');
				$rate=$this->input->post('rate');
				$calculatemoney=$this->input->post('calculatemoney');
				$realgetmoney=$this->input->post('realgetmoney');
				$balance=$this->input->post('balance');

				// $vrtype =mb_substr("ကခ01",0,2,'UTF-8');

				$data=array(
					'entry_date'=> $entrydate,
					'entry_by'=> $entryby,
					'voucher'=> $voucher,
					'customer_name'=> $customername,
					'address'=> $address,
					'vr_type'=>mb_substr($voucher,0,2,'UTF-8'),
					'getmoney'=> $getmoney,
					'total_month'=> $totalmonth,
					'rate'=> $rate,
					'calculate_money'=> $calculatemoney,
					'realget_money'=> $realgetmoney,
					'balance_money'=> $balance
				);
				$this->Main_model->insert("redeem_tbl",$data);

				$statusdata=array(
					'status'=> 0,
				);
				$this->db->where('voucher',$voucher);
				$this->db->update("collateral_tbl",$statusdata);

				$this->db->where('voucher',$voucher);
				$this->db->delete("collateral_stock_tbl");

				redirect('Main/create_redeem_form');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function redeem_edit()
		{
			if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$id=$this->input->post('id');
				$entryby=$this->input->post('entryby');
				$entrydate=date("Y-m-d",strtotime($this->input->post('entrydate')));
				$name=$this->input->post('name');
				$address=$this->input->post('address');
				$voucher=$this->input->post('voucher');
				$getmoney=$this->input->post('getmoney');
				$totalmonth=$this->input->post('totalmonth');
				$rate=$this->input->post('rate');
				$calculatemoney=$this->input->post('calculatemoney');
				$realgetmoney=$this->input->post('realgetmoney');
				$balance=$this->input->post('balance');

				$data=array(
					'entry_date'=> $entrydate,
					// 'entry_by'=> $entryby,
					'voucher'=> $voucher,
					'getmoney'=> $getmoney,
					'customer_name'=> $name,
					'address'=> $address,
					'total_month'=> $totalmonth,
					'rate'=> $rate,
					'calculate_money'=> $calculatemoney,
					'realget_money'=> $realgetmoney,
					'balance_money'=> $balance
				);
				// $this->Main_model->insert("redeem_tbl",$data);
				$this->db->where('id',$id);
				$this->db->update("redeem_tbl",$data);

				$statusdata=array(
					'status'=> 0,
				);
				$this->db->where('voucher',$voucher);
				$this->db->update("collateral_tbl",$statusdata);

				$this->db->where('voucher',$voucher);
				$this->db->delete("collateral_stock_tbl");

				redirect('Main/data_list/redeemList');
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}
		function redeem_delete()
		{
		    $id= $this->uri->segment(3);

    		$this->Main_model->delete("redeem_tbl",'id',$id);


    		redirect('Main/data_list/redeemList/');
		}
		function data_list()
		{
		if($this->session->userdata("username") && $this->session->userdata("password"))
			{
				$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
				$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
				$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
				$data['totaloutcome']=$this->db->get('in_outcome_tbl');


				$table=$this->uri->segment(3);

				switch ($table) {
					case 'redeemList':
					$data["dailyremainlist"]=$this->db->query("SELECT * FROM redeem_tbl LIMIT 100");
					$data["loantotalamt"]=$this->db->query("SELECT redeem_tbl.*,SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl")->row();
					break;

					case 'collateral_list':
					$data["collateralList"]=$this->db->query("SELECT * FROM collateral_tbl order by length(vr_no),vr_no asc LIMIT 10");
					// $this->db->query("SELECT * FROM collateral_tbl order by length(vr_no),vr_no asc");
					$data["loantotalamt"]=$this->db->query("SELECT collateral_tbl.*,SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl")->row();
					break;

					case 'stockList':
					$data["stockList"]=$this->db->query("SELECT * FROM collateral_stock_tbl LEFT JOIN collateral_tbl ON collateral_tbl.voucher=collateral_stock_tbl.voucher order by length(collateral_tbl.vr_no),collateral_tbl.vr_no");
					$data["loantotalamt"]=$this->db->query("SELECT collateral_stock_tbl.*,SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl")->row();
					break;

					case 'unabletoredeemList':
					$this->db->select("collateral_tbl.*,collateral_stock_tbl.*,collateral_stock_tbl.voucher as stvoucher");
        			$this->db->join("collateral_tbl","collateral_stock_tbl.voucher=collateral_tbl.voucher");
       				$data["unabletoredeemList"]=$this->db->get_where("collateral_stock_tbl",array('collateral_stock_tbl.unabletoredeem'=>'0'));
       				
       				$data["loantotalamt"]=$this->db->query("SELECT collateral_stock_tbl.*,SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE unabletoredeem='0'")->row();
					break;

					case 'addressList':
					$data["addresslist"]=$this->db->query("SELECT * FROM location_tbl");

					case 'collateral_addressList':
					$data["collateral_address"]=$this->db->query("SELECT *,count(address) as total FROM collateral_tbl GROUP BY address order by total desc");


					case 'outcomeList':
					$data["income_outcomelist"]=$this->db->query("SELECT * FROM in_outcome_tbl order by income_amt desc");
					$data["total_amt"]=$this->db->query("SELECT SUM(income_amt) AS incomeamt,SUM(outcome_amt) AS outcomeamt FROM in_outcome_tbl")->row();
					break;

					case 'ledgerList':
					$data["collateraldata"]=$this->db->query("SELECT *,MONTH(entry_date) as mdate,YEAR(entry_date) as ydate FROM collateral_tbl GROUP BY vr_type");

					// $data["cdata"]=$this->db->query("SELECT *,MONTH(entry_date) as mdate,YEAR(entry_date) as ydate FROM collateral_tbl GROUP BY vr_type")->row();

					$data["redeemdata"]=$this->db->query("SELECT SUM(getmoney) as loantotal ,MONTH(entry_date) as mdate,YEAR(entry_date) as ydate FROM redeem_tbl GROUP BY MONTH(entry_date)+'-'+YEAR(entry_date)");
					// $data["ledgercollist"]=$this->db->query("SELECT *,SUM(loan_amt) as totalloan FROM collateral_tbl  GROUP BY vr_type");
					// $data["total_amt"]=$this->db->query("SELECT *,SUM(getmoney) as loantotal FROM redeem_tbl GROUP BY vr_type");

					// $data["mtotalloanamt"]=$this->db->select('*,SUM(loan_amt) AS mloan_total')
					// ->group_by('vr_type')
					// ->get('collateral_tbl');

					$data["ctotalamt"]=$this->db->query("SELECT SUM(loan_amt) as ctotal FROM collateral_tbl GROUP BY vr_type");

					$data["balancetotal"]=$this->db->query("SELECT SUM(loan_amt) as btotal FROM collateral_tbl GROUP BY vr_type");
					break;

					case 'ledgerList2':
					$data["collateraldata"]=$this->db->query("SELECT *,MONTH(entry_date) as mdate,YEAR(entry_date) as ydate FROM collateral_tbl GROUP BY vr_type");

					$data["cdata"]=$this->db->query("SELECT *,MONTH(entry_date) as mdate,YEAR(entry_date) as ydate FROM collateral_tbl GROUP BY vr_type")->row();

					$data["redeemdata"]=$this->db->query("SELECT count(voucher) as loantotal ,MONTH(entry_date) as mdate,YEAR(entry_date) as ydate FROM redeem_tbl GROUP BY MONTH(entry_date)+'-'+YEAR(entry_date)");
					// $data["ledgercollist"]=$this->db->query("SELECT *,SUM(loan_amt) as totalloan FROM collateral_tbl  GROUP BY vr_type");
					$data["total_amt"]=$this->db->query("SELECT *,count(voucher) as loantotal FROM redeem_tbl GROUP BY vr_type");

					$data["mtotalloanamt"]=$this->db->select('*,SUM(loan_amt) AS mloan_total')
					->group_by('vr_type')
					->get('collateral_tbl');

					$data["ctotalamt"]=$this->db->query("SELECT count(voucher) as ctotal FROM collateral_tbl GROUP BY vr_type");

					$data["balancetotal"]=$this->db->query("SELECT count(voucher) as btotal FROM collateral_tbl GROUP BY vr_type");
					break;

					case 'category':
					$data["categorylist"]=$this->db->query("SELECT * FROM category_tbl");

					case 'citemsList':
					$data["citemslist"]=$this->db->query("SELECT * FROM citems_tbl");
					break;
				}
				$data['content']=$table;
				$this->load->view("template",$data);
			}
			else
			{
				$data["message"]="Login to access this page";
				redirect('Main/admin_form');
			}
		}



		/*login form*/
		public function admin_form()
		{
			$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
			$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
			$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>0));
			$data['totaloutcome']=$this->db->get('in_outcome_tbl');

			$this->load->view('admin/admin_login',$data);
		}
		public function admin_login()
		{
			ob_start();
			$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
			$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
			$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>0));
			$data['totaloutcome']=$this->db->get('in_outcome_tbl');

			$username=$this->input->post("username");
			$password=md5($this->input->post("password"));
			$this->db->select('*');
			$this->db->from('admin');
			$this->db->where(array('userName'=>$username,'password'=>$password));
			$query=$this->db->get();

			if($query->num_rows()==1)
			{

				$user=$query->row();
				$userdata=array('id'=>$user->id,'username'=>$user->userName,'password'=>$user->password);
				$this->session->set_userdata($userdata);

				redirect("Main/create_collateral_form/","refresh");
			}
			else
			{
				$this->load->view('admin/admin_login',$data);
				?> <script>
					arert("User name and password do not match")
				</script><?php
			}
		}
		/*end login form*/
		function update_unabletoredeem()
		{
				$voucher=$this->input->post('voucher');

				$data=array(
			    "unabletoredeem" =>"0"
				);
				$this->db->where('voucher',$voucher);
				$this->db->update("collateral_stock_tbl",$data);

				$this->db->where('voucher',$voucher);
				$this->db->update("collateral_tbl",$data);



		}
		function list_print()
		{
			$data['collateralList']=$this->db->get('collateral_tbl');

			// $this->fontdata = array(
			//     "zawgyi-one" => array(
			// 	'R' => "ZawgyiOne.ttf",
			// 	),
			// 	);
			$pdf_view=$this->load->view('listprint',$data, true);
	    	$pdfFilePath = 'list.pdf';
	    	$this->load->library('m_pdf');
	    	$this->m_pdf->pdf->WriteHTML($pdf_view);
	    	$this->m_pdf->pdf->Output($pdfFilePath, "I");
		}
function inoutbudget_search()
{
	if($this->input->post('submit')==true)
	{
		$startdate=$this->input->post("startdate");
		$enddate=$this->input->post("enddate");

		$userdata=array(

	    'startdate'=>$startdate,
	    'enddate'=>$enddate
	    );
	$this->session->set_userdata($userdata);
	}
	else{

		$startdate=$this->session->userdata("startdate");
		$enddate=$this->session->userdata("enddate");
	}

	if(empty($startdate) && empty($enddate))
	{
		$query=$this->db->query("SELECT * FROM in_outcome_tbl order by income_amt desc");
		$data["total_amt"]=$this->db->query("SELECT SUM(in_outcome_tbl.income_amt) as incomeamt,SUM(in_outcome_tbl.outcome_amt) as outcomeamt FROM in_outcome_tbl")->row();
	}
	elseif(!empty($startdate) && empty($enddate))
	{
		$query=$this->db->query("SELECT * FROM in_outcome_tbl WHERE entry_date='$startdate' order by income_amt desc");
		$data["total_amt"]=$this->db->query("SELECT SUM(in_outcome_tbl.income_amt) as incomeamt,SUM(in_outcome_tbl.outcome_amt) as outcomeamt FROM in_outcome_tbl WHERE entry_date='$startdate'")->row();
	}
	elseif(empty($startdate) && !empty($enddate))
	{
		$query=$this->db->query("SELECT * FROM in_outcome_tbl WHERE entry_date='$enddate' order by income_amt desc");
		$data["total_amt"]=$this->db->query("SELECT SUM(in_outcome_tbl.income_amt) as incomeamt,SUM(in_outcome_tbl.outcome_amt) as outcomeamt FROM in_outcome_tbl WHERE entry_date='$enddate'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate))
	{
		$query=$this->db->query("SELECT * FROM in_outcome_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' order by income_amt desc");
		$data["total_amt"]=$this->db->query("SELECT SUM(in_outcome_tbl.income_amt) as incomeamt,SUM(in_outcome_tbl.outcome_amt) as outcomeamt FROM in_outcome_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'")->row();
	}
	if($query->num_rows()>=1)
		{
		$data["message"]="";
		$data["lists"]=$query;

		$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
		$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
		$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
		$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		// $data["collateralList"]=$this->db->query("SELECT * FROM collateral_tbl");
		// $data["loantotalamt"]=$this->db->query("SELECT collateral_tbl.*,SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl")->row();

		$data["content"]="outcome_list_search";
		$this->load->view("template",$data);
		}
		else{
			$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
			$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
			$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
			$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		    $data["message"]="No Data Found!";
		    $data["content"]="nodata";
		    $this->load->view("template",$data);
		}
}
function collateraladdress_search()
{
	if($this->input->post('submit')==true)
	{
		$startdate=date("Y-m-d",strtotime($this->input->post('startdate')));
		$enddate=date("Y-m-d",strtotime($this->input->post('enddate')));


		$userdata=array(

	    'startdate'=>$startdate,
	    'enddate'=>$enddate
	    );
	$this->session->set_userdata($userdata);
	}
	else{

		$startdate=$this->session->userdata("startdate");
		$enddate=$this->session->userdata("enddate");
	}
	echo $startdate;echo "<br/>";
echo $enddate;exit;
	if(empty($startdate) && empty($enddate))
	{
		$query=$this->db->query("SELECT *,count(address) as total FROM collateral_tbl GROUP BY address order by total desc");		
	}
	elseif(!empty($startdate) && empty($enddate))
	{
		$query=$this->db->query("SELECT *,count(address) as total FROM collateral_tbl WHERE entry_date='$startdate' GROUP BY address order by total desc");		
	}
	elseif(empty($startdate) && !empty($enddate))
	{
		$query=$this->db->query("SELECT *,count(address) as total FROM collateral_tbl WHERE entry_date='$enddate' GROUP BY address order by total desc");		
	}
	elseif(!empty($startdate) && !empty($enddate))
	{
		$query=$this->db->query("SELECT *,count(address) as total FROM collateral_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' GROUP BY address order by total desc");		
	}
	echo $this->db->last_query();exit;
	if($query->num_rows()>=1)
		{
		$data["message"]="";
		$data["lists"]=$query;

		$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
		$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
		$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
		$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		// $data["collateralList"]=$this->db->query("SELECT * FROM collateral_tbl");
		// $data["loantotalamt"]=$this->db->query("SELECT collateral_tbl.*,SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl")->row();

		$data["content"]="collateral_address_search";
		$this->load->view("template",$data);
		}
		else{
			$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
			$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
			$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
			$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		    $data["message"]="No Data Found!";
		    $data["content"]="nodata";
		    $this->load->view("template",$data);
		}
}
function collateral_search()
{
	if($this->input->post('submit')==true)
	{
		$startdate=$this->input->post("startdate");
		$enddate=$this->input->post("enddate");
		$name=$this->input->post("name");
		$address=$this->input->post("address");
		$loan_amt=$this->input->post("loan_amt");
		$voucher=$this->input->post("voucher");

		$userdata=array(

	    'startdate'=>$startdate,
	    'enddate'=>$enddate,
	    'name'=>$name,
	    'address'=>$address,
	    'loan_amt'=>$loan_amt,
	    'voucher'=>$voucher
	    );
	$this->session->set_userdata($userdata);
	}
	else{

		$startdate=$this->session->userdata("startdate");
		$enddate=$this->session->userdata("enddate");
		$name=$this->session->userdata("name");
		$address=$this->session->userdata("address");
		$loan_amt=$this->session->userdata("loan_amt");
		$voucher=$this->session->userdata("voucher");
	}

	if(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE address='$address' order by length(vr_no),vr_no asc");

		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE address='$address' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE address='$address' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' order by length(vr_no),vr_no asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
	// echo $query->num_rows();exit;
	if($query->num_rows()>=1)
		{
		$data["message"]="";
		$data["lists"]=$query;

		$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
		$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
		$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
		$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		// $data["collateralList"]=$this->db->query("SELECT * FROM collateral_tbl");
		// $data["loantotalamt"]=$this->db->query("SELECT collateral_tbl.*,SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl")->row();

		$data["content"]="collateral_list_search";
		$this->load->view("template",$data);
		}
		else{
			$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
		$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
		$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
		$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		    $data["message"]="No Data Found!";
		    $data["content"]="nodata";
		    $this->load->view("template",$data);
		}
	/**/
}
function collateralstock_search()
{
	if($this->input->post('submit')==true)
	{
		$startdate=$this->input->post("startdate");
		$enddate=$this->input->post("enddate");
		$name=$this->input->post("name");
		$address=$this->input->post("address");
		$loan_amt=$this->input->post("loan_amt");
		$voucher=$this->input->post("voucher");

		$userdata=array(

	    'startdate'=>$startdate,
	    'enddate'=>$enddate,
	    'name'=>$name,
	    'address'=>$address,
	    'loan_amt'=>$loan_amt,
	    'voucher'=>$voucher
	    );
	$this->session->set_userdata($userdata);
	}
	else{

		$startdate=$this->session->userdata("startdate");
		$enddate=$this->session->userdata("enddate");
		$name=$this->session->userdata("name");
		$address=$this->session->userdata("address");
		$loan_amt=$this->session->userdata("loan_amt");
		$voucher=$this->session->userdata("voucher");
	}

	if(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' order by voucher asc");

		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE loan_amt='$loan_amt' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE voucher LIKE '%$voucher%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND address='$address' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND address='$address' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND address='$address' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND loan_amt='$loan_amt' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address' order by voucher asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' order by voucher asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by voucher asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
	// echo $query->num_rows();exit;
	if($query->num_rows()>=1)
		{
		$data["message"]="";
		$data["lists"]=$query;

		$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
		$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
		$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
		$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		// $data["collateralList"]=$this->db->query("SELECT * FROM collateral_tbl");
		// $data["loantotalamt"]=$this->db->query("SELECT collateral_tbl.*,SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl")->row();

		$data["content"]="stockList_search";
		$this->load->view("template",$data);
		}
		else{
			$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
			$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
			$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
			$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		    $data["message"]="No Data Found!";
		    $data["content"]="nodata";
		    $this->load->view("template",$data);
		}
	/**/
}
function redeem_search()
{
	if($this->input->post('submit')==true)
	{
		$startdate=$this->input->post("startdate");
		$enddate=$this->input->post("enddate");
		$name=$this->input->post("name");
		$address=$this->input->post("address");
		$loan_amt=$this->input->post("loan_amt");
		$voucher=$this->input->post("voucher");

		$userdata=array(

	    'startdate'=>$startdate,
	    'enddate'=>$enddate,
	    'name'=>$name,
	    'address'=>$address,
	    'loan_amt'=>$loan_amt,
	    'voucher'=>$voucher
	    );
	$this->session->set_userdata($userdata);
	}
	else{

		$startdate=$this->session->userdata("startdate");
		$enddate=$this->session->userdata("enddate");
		$name=$this->session->userdata("name");
		$address=$this->session->userdata("address");
		$loan_amt=$this->session->userdata("loan_amt");
		$voucher=$this->session->userdata("voucher");
	}

	if(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl")->row();
		$data["loantotalamt"]=$this->db->query("SELECT redeem_tbl.*,SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl")->row();

	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE entry_date='$startdate'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE entry_date='$enddate'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE customer_name LIKE '%$name%'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE address='$address' order by length(vr_no),vr_no asc");

		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE address='$address'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE getmoney='$loan_amt'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE voucher LIKE '%$voucher%'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND getmoney='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND getmoney='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE address='$address' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE address='$address' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND getmoney='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND getmoney='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND getmoney='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND getmoney='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' order by length(vr_no),vr_no asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
	// echo $query->num_rows();exit;
	if($query->num_rows()>=1)
		{
		$data["message"]="";
		$data["lists"]=$query;

		$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
		$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
		$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
		$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		// $data["collateralList"]=$this->db->query("SELECT * FROM collateral_tbl");
		// $data["loantotalamt"]=$this->db->query("SELECT collateral_tbl.*,SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl")->row();

		$data["content"]="redeemList_search";
		$this->load->view("template",$data);
		}
		else{
		    $data["message"]="No Data Found!";
		    $data["content"]="nodata";
		    $this->load->view("template",$data);
		}
	/**/
}
function ledger_search()
{
	if($this->input->post('submit')==true)
	{
			$startdate=$this->input->post('startdate');
			$enddate=$this->input->post('enddate');

				$userdata=array(
				'startdate'=>$startdate,
				'enddate'=>$enddate
				);
				$this->session->set_userdata($userdata);
		}
	else{
		$startdate=$this->session->userdata("startdate");
		$enddate=$this->session->userdata("enddate");
	}

	$data["collateraldata"]=$this->db->query("SELECT *,MONTH(entry_date) as mdate,YEAR(entry_date) as ydate FROM collateral_tbl WHERE entry_date BETWEEN MONTH($startdate)+'-'+YEAR($startdate) AND MONTH($enddate)+'-'+YEAR($enddate) GROUP BY vr_type");

	$data["redeemdata"]=$this->db->query("SELECT SUM(getmoney) as loantotal ,MONTH(entry_date) as mdate,YEAR(entry_date) as ydate FROM redeem_tbl WHERE entry_date BETWEEN MONTH($startdate)+'-'+YEAR($startdate) AND MONTH($enddate)+'-'+YEAR($enddate) GROUP BY MONTH(entry_date)+'-'+YEAR(entry_date)");

	$data["ctotalamt"]=$this->db->query("SELECT SUM(loan_amt) as ctotal FROM collateral_tbl WHERE entry_date BETWEEN MONTH($startdate)+'-'+YEAR($startdate) AND MONTH($enddate)+'-'+YEAR($enddate) GROUP BY vr_type");

	$data["balancetotal"]=$this->db->query("SELECT SUM(loan_amt) as btotal FROM collateral_tbl WHERE entry_date BETWEEN MONTH($startdate)+'-'+YEAR($startdate) AND MONTH($enddate)+'-'+YEAR($enddate) GROUP BY vr_type");

	$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
	$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
	$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
	$data['totaloutcome']=$this->db->get('in_outcome_tbl');

	$data["content"]="yearlyledger_search";
	$this->load->view("template",$data);
}
function unabletoredeem_search()
{
	if($this->input->post('submit')==true)
			{
				$startdate=$this->input->post("startdate");
				$enddate=$this->input->post("enddate");
				$name=$this->input->post("name");
				$address=$this->input->post("address");
				$loan_amt=$this->input->post("loan_amt");
				$voucher=$this->input->post("voucher");

				$userdata=array(

			    'startdate'=>$startdate,
			    'enddate'=>$enddate,
			    'name'=>$name,
			    'address'=>$address,
			    'loan_amt'=>$loan_amt,
			    'voucher'=>$voucher
			    );
			$this->session->set_userdata($userdata);
			}
			else{

				$startdate=$this->session->userdata("startdate");
				$enddate=$this->session->userdata("enddate");
				$name=$this->session->userdata("name");
				$address=$this->session->userdata("address");
				$loan_amt=$this->session->userdata("loan_amt");
				$voucher=$this->session->userdata("voucher");
			}

			if(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND  unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND unabletoredeem='0'");

				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(!empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				 $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'");
				 $data["collateralList"]=$query;
				 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
			{
				 $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' AND unabletoredeem='0'");
				 $data["collateralList"]=$query;
				 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
	// echo $query->num_rows();exit;
	if($query->num_rows()>=1)
		{
		$data["message"]="";
		$data["lists"]=$query;

		$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
		$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
		$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
		$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		// $data["collateralList"]=$this->db->query("SELECT * FROM collateral_tbl");
		// -- $data["loantotalamt"]=$this->db->query("SELECT collateral_stock_tbl.*,SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE unabletoredeem='0'")->row();


		$data["content"]="unabletoredeemList_search";
		$this->load->view("template",$data);
		}
		else{
			$data['totalstocklist']=$this->db->get('collateral_stock_tbl');
			$data['stocklist']=$this->db->limit(5)->get('collateral_stock_tbl');
			$data['unabletoredeem']=$this->db->get_where('collateral_stock_tbl',array('unabletoredeem'=>"0"));
			$data['totaloutcome']=$this->db->get('in_outcome_tbl');

		    $data["message"]="No Data Found!";
		    $data["content"]="nodata";
		    $this->load->view("template",$data);
		}
	/**/
}
function searchsingle()
{
	$check=true;

	if($check==true)
	{
	 $value=trim($this->input->post("search"));
	 $colunm=$this->input->post("colunm");
	 $ostartdate=$this->input->post('startdate');
	 $oenddate=$this->input->post('enddate');
	 $startdate=date("Y-m-d",strtotime($this->input->post('startdate')));
	 $enddate=date("Y-m-d",strtotime($this->input->post('enddate')));

	 // echo $startdate; exit;

	 $this->session->unset_userdata('search_value');
	 $this->session->unset_userdata('search_colunm');
	 $this->session->unset_userdata('startdate');
	 $this->session->unset_userdata('enddate');

	$data=array("search_value"=>$value,
	            "search_colunm"=>$colunm,
	            "startdate"=>$ostartdate,
	            "enddate"=>$oenddate);

	$this->session->set_userdata($data);

	$table=$this->uri->segment(3);

	if(empty($value) && empty($ostartdate) && empty($oenddate))
	{
		switch ($table) {

		default:
		  $query=$this->db->order_by("id","DESC")->get($table);
		  break;
		}
	}

	elseif(!empty($value) && empty($ostartdate) && empty($oenddate))
	{

		switch ($table) {

		case 'collateral_list':
		  $query=$this->db->query("SELECT * FROM collateral_tbl WHERE $colunm LIKE '%$value%'");
		break;

		case 'stockList':
		  $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE $colunm LIKE '%$value%'");
		break;

		case 'redeemList':
		  $query=$this->db->query("SELECT * FROM redeem_tbl WHERE $colunm LIKE '%$value%'");
		break;

		case 'unabletoredeemList':
		  $query=$this->db->query("SELECT * FROM collateral_tbl WHERE $colunm LIKE '%$value%' AND unabletoredeem='0'");
		break;

		}

	}

	elseif(!empty($value) && !empty($ostartdate) && empty($oenddate))
	{
	    if($table=="collateral_list")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_tbl WHERE $colunm LIKE '%$value%' and entry_date='$startdate'");
	    }
	    elseif($table=="stockList")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE $colunm LIKE '%$value%' and entry_date='$startdate'");
	    }
	    elseif($table=="redeemList")
	    {
	      $query=$this->db->query("SELECT * FROM redeem_tbl WHERE $colunm LIKE '%$value%' and entry_date='$startdate'");
	    }
	    elseif($table=="unabletoredeemList")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_tbl WHERE $colunm LIKE '%$value%' and entry_date='$startdate' and unabletoredeem='0'");
	    }

	}

	elseif(!empty($value) && empty($ostartdate) && !empty($oenddate))
	{

	  if($table=="collateral_list")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_tbl WHERE $colunm LIKE '%$value%' and entry_date='$enddate'");
	    }
      elseif($table=="stockList")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE $colunm LIKE '%$value%' and entry_date='$enddate'");
	    }
	  elseif($table=="redeemList")
	    {
	      $query=$this->db->query("SELECT * FROM redeem_tbl WHERE $colunm LIKE '%$value%' and entry_date='$enddate'");
	    }
	  elseif($table=="unabletoredeemList")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_tbl WHERE $colunm LIKE '%$value%' and entry_date='$enddate' and unabletoredeem='0'");
	    }

	}
	elseif(empty($value) && !empty($ostartdate) && empty($oenddate))
	{
		if($table=="collateral_list")
	    {
	        $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate'");
		}
		elseif($table=="stockList")
	    {
	        $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate'");
		}
		elseif($table=="redeemList")
	    {
	        $query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate'");
		}
	    elseif($table=="unabletoredeemList")
	    {
	        $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' and unabletoredeem='0'");
		}
	}
	elseif(empty($value) && empty($ostartdate) && !empty($oenddate))
	{

	  if($table=="collateral_list")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate'");
	    }
	  elseif($table=="stockList")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate'");
	    }
	  elseif($table=="redeemList")
	    {
	      $query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate'");
	    }
	  elseif($table=="unabletoredeemList")
	    {
	      $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' and unabletoredeem='0'");
	    }
	}

	elseif(empty($value) && !empty($ostartdate) && !empty($oenddate))
	{
		switch ($table) {

		case 'collateral_list':
		    $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'");
		break;

		case 'stockList':
		    $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'");
		break;

		case 'redeemList':
		    $query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'");
		break;

		case 'unabletoredeemList':
		    $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' and unabletoredeem='0'");
		break;

		}

	}
	elseif(!empty($value) && !empty($ostartdate) && !empty($oenddate))
	{

		switch ($table) {
		case 'collateral_list':

		  $query=$this->db->query("SELECT * FROM collateral_tbl WHERE $colunm LIKE '%$value%' AND date BETWEEN '$startdate' AND '$enddate' ORDER BY collateral_tbl.date,collateral_tbl.id");
		break;

		case 'stockList':

		  $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE $colunm LIKE '%$value%' AND date BETWEEN '$startdate' AND '$enddate' ORDER BY collateral_stock_tbl.date,collateral_stock_tbl.id");
		break;

		case 'redeemList':

		  $query=$this->db->query("SELECT * FROM redeem_tbl WHERE $colunm LIKE '%$value%' AND date BETWEEN '$startdate' AND '$enddate' ORDER BY redeem_tbl.date,redeem_tbl.id");
		break;

		case 'unabletoredeemList':

		  $query=$this->db->query("SELECT * FROM collateral_tbl WHERE $colunm LIKE '%$value%' AND date BETWEEN '$startdate' AND '$enddate' AND unabletoredeem='0' ORDER BY collateral_tbl.date,collateral_tbl.id");
		break;

		default:
		$query=$this->db->query("SELECT * FROM $table WHERE $colunm
		LIKE '%$value%' AND date BETWEEN '$startdate' AND '$enddate'");
		break;

		}
	}
	// echo $this->db->last_query();exit;
	if($query->num_rows()>=1)
	{
	$data["message"]="";
	$data["lists"]=$query;
	$data["content"]=$table;
	$this->load->view($table."_search",$data);

	}
}
	}
	function searchinoutbudget_print()
	{
		if($this->input->post('submit')==true)
		{
			$startdate=$this->input->post("startdate");
			$enddate=$this->input->post("enddate");

			$userdata=array(

		    'startdate'=>$startdate,
		    'enddate'=>$enddate
		    );
		$this->session->set_userdata($userdata);
		}
		else{

			$startdate=$this->session->userdata("startdate");
			$enddate=$this->session->userdata("enddate");
		}

		if(empty($startdate) && empty($enddate))
		{
			$query=$this->db->query("SELECT * FROM in_outcome_tbl order by income_amt desc");
			$data["total_amt"]=$this->db->query("SELECT SUM(in_outcome_tbl.income_amt) as incomeamt,SUM(in_outcome_tbl.outcome_amt) as outcomeamt FROM in_outcome_tbl")->row();
		}
		elseif(!empty($startdate) && empty($enddate))
		{
			$query=$this->db->query("SELECT * FROM in_outcome_tbl WHERE entry_date='$startdate' order by income_amt desc");
			$data["total_amt"]=$this->db->query("SELECT SUM(in_outcome_tbl.income_amt) as incomeamt,SUM(in_outcome_tbl.outcome_amt) as outcomeamt FROM in_outcome_tbl WHERE entry_date='$startdate'")->row();
		}
		elseif(empty($startdate) && !empty($enddate))
		{
			$query=$this->db->query("SELECT * FROM in_outcome_tbl WHERE entry_date='$enddate' order by income_amt desc");
			$data["total_amt"]=$this->db->query("SELECT SUM(in_outcome_tbl.income_amt) as incomeamt,SUM(in_outcome_tbl.outcome_amt) as outcomeamt FROM in_outcome_tbl WHERE entry_date='$enddate'")->row();
		}
		elseif(!empty($startdate) && !empty($enddate))
		{
			$query=$this->db->query("SELECT * FROM in_outcome_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' order by income_amt desc");
			$data["total_amt"]=$this->db->query("SELECT SUM(in_outcome_tbl.income_amt) as incomeamt,SUM(in_outcome_tbl.outcome_amt) as outcomeamt FROM in_outcome_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'")->row();
		}
		if($query->num_rows()>=1)
			{

				$data["printlists"]=$query;

				$pdf_view=$this->load->view("outcome_list_searchprint",$data, true);
		    	$pdfFilePath = 'collateral_list.pdf';
		    	$this->load->library('m_pdf');
		    	$this->m_pdf->pdf->WriteHTML($pdf_view);
		    	$this->m_pdf->pdf->Output($pdfFilePath, "I");
			}
			else{

			}
	}
	function searchcollateral_print()
		{

			if($this->input->post('submit')==true)
		{
			$startdate=$this->input->post("startdate");
			$enddate=$this->input->post("enddate");
			$name=$this->input->post("name");
			$address=$this->input->post("address");
			$loan_amt=$this->input->post("loan_amt");
			$voucher=$this->input->post("voucher");

			$userdata=array(

		    'startdate'=>$startdate,
		    'enddate'=>$enddate,
		    'name'=>$name,
		    'address'=>$address,
		    'loan_amt'=>$loan_amt,
		    'voucher'=>$voucher
		    );
		$this->session->set_userdata($userdata);
		}
		else{

			$startdate=$this->session->userdata("startdate");
			$enddate=$this->session->userdata("enddate");
			$name=$this->session->userdata("name");
			$address=$this->session->userdata("address");
			$loan_amt=$this->session->userdata("loan_amt");
			$voucher=$this->session->userdata("voucher");
		}

		if(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE address='$address' order by length(vr_no),vr_no asc");

		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE address='$address' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE address='$address' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' order by length(vr_no),vr_no asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
		// echo $this->db->last_query();exit;

			if($query->num_rows()>=1)
			{

				$data["printlists"]=$query;
				// $data["content"]="";
				// $this->load->view($table."_searchprint",$data);

				$pdf_view=$this->load->view("collateral_list_searchprint",$data, true);
		    	$pdfFilePath = 'collateral_list.pdf';
		    	$this->load->library('m_pdf');
		    	$this->m_pdf->pdf->WriteHTML($pdf_view);
		    	$this->m_pdf->pdf->Output($pdfFilePath, "I");
			}
			else{

			}


	}
	function searchstock_print()
		{

			if($this->input->post('submit')==true)
			{
				$startdate=$this->input->post("startdate");
				$enddate=$this->input->post("enddate");
				$name=$this->input->post("name");
				$address=$this->input->post("address");
				$loan_amt=$this->input->post("loan_amt");
				$voucher=$this->input->post("voucher");

				$userdata=array(

			    'startdate'=>$startdate,
			    'enddate'=>$enddate,
			    'name'=>$name,
			    'address'=>$address,
			    'loan_amt'=>$loan_amt,
			    'voucher'=>$voucher
			    );
			$this->session->set_userdata($userdata);
			}
			else{

				$startdate=$this->session->userdata("startdate");
				$enddate=$this->session->userdata("enddate");
				$name=$this->session->userdata("name");
				$address=$this->session->userdata("address");
				$loan_amt=$this->session->userdata("loan_amt");
				$voucher=$this->session->userdata("voucher");
			}

			if(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_tbl WHERE entry_date='$enddate'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' order by length(voucher),voucher asc");

				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE loan_amt='$loan_amt' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE loan_amt='$loan_amt'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE voucher LIKE '%$voucher%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE voucher LIKE '%$voucher%'")->row();
			}
			/**/
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND address='$address' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND address='$address'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%'")->row();
			}
			/**/
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND address='$address' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND address='$address'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
			}
			/**/
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND address='$address' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND address='$address'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
			}
			/**/
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
			}
			/**/
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND AND loan_amt='$loan_amt' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND loan_amt='$loan_amt'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
			}
			/**/
			elseif(!empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				 $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address' order by length(voucher),voucher asc");
				 $data["collateralList"]=$query;
				 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
			{
				 $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' order by length(voucher),voucher asc");
				 $data["collateralList"]=$query;
				 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(voucher),voucher asc");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
			}
		// echo $this->db->last_query();exit;

			if($query->num_rows()>=1)
			{

				$data["printlists"]=$query;
				// $data["content"]="";
				// $this->load->view($table."_searchprint",$data);

				$pdf_view=$this->load->view("stockList_searchprint",$data, true);
		    	$pdfFilePath = 'collateral_list.pdf';
		    	$this->load->library('m_pdf');
		    	$this->m_pdf->pdf->WriteHTML($pdf_view);
		    	$this->m_pdf->pdf->Output($pdfFilePath, "I");
			}
			else{

			}


	}
	function searchredeem_print()
	{
		if($this->input->post('submit')==true)
		{
			$startdate=$this->input->post("startdate");
			$enddate=$this->input->post("enddate");
			$name=$this->input->post("name");
			$address=$this->input->post("address");
			$loan_amt=$this->input->post("loan_amt");
			$voucher=$this->input->post("voucher");

			$userdata=array(

		    'startdate'=>$startdate,
		    'enddate'=>$enddate,
		    'name'=>$name,
		    'address'=>$address,
		    'loan_amt'=>$loan_amt,
		    'voucher'=>$voucher
		    );
		$this->session->set_userdata($userdata);
		}
		else{

			$startdate=$this->session->userdata("startdate");
			$enddate=$this->session->userdata("enddate");
			$name=$this->session->userdata("name");
			$address=$this->session->userdata("address");
			$loan_amt=$this->session->userdata("loan_amt");
			$voucher=$this->session->userdata("voucher");
		}

		if(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl")->row();
		$data["loantotalamt"]=$this->db->query("SELECT redeem_tbl.*,SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl")->row();

	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE entry_date='$startdate'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE entry_date='$enddate'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE customer_name LIKE '%$name%'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE address='$address' order by length(vr_no),vr_no asc");

		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE address='$address'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE getmoney='$loan_amt'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		// $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal FROM redeem_tbl WHERE voucher LIKE '%$voucher%'")->row();
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND getmoney='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND getmoney='$loan_amt'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE address='$address' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE address='$address' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%'")->row();
	}
	/**/
	elseif(!empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND getmoney='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND getmoney='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND getmoney='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND getmoney='$loan_amt' AND address='$address'")->row();
	}
	elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
	{
		 $query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' order by length(vr_no),vr_no asc");
		 $data["collateralList"]=$query;
		 $data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address'")->row();
	}
	elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address'")->row();
	}
	elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
	{
		$query=$this->db->query("SELECT * FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' order by length(vr_no),vr_no asc");
		$data["collateralList"]=$query;
		$data["loantotalamt"]=$this->db->query("SELECT SUM(redeem_tbl.getmoney) as loantotal,SUM(redeem_tbl.realget_money) as netamt,SUM(redeem_tbl.balance_money) as balanceamt,SUM(redeem_tbl.calculate_money) as calculateamt FROM redeem_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND getmoney='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%'")->row();
	}
		if($query->num_rows()>=1)
			{

				$data["printlists"]=$query;
				// $data["content"]="";
				// $this->load->view($table."_searchprint",$data);

				$pdf_view=$this->load->view("redeemList_searchprint",$data, true);
		    	$pdfFilePath = 'အေရြးစာရင္းမ်ား.pdf';
		    	$this->load->library('m_pdf');
		    	$this->m_pdf->pdf->WriteHTML($pdf_view);
		    	$this->m_pdf->pdf->Output($pdfFilePath, "I");
			}
			else{

			}
	}
	function searchunabletoredeem_print()
		{

			if($this->input->post('submit')==true)
			{
				$startdate=$this->input->post("startdate");
				$enddate=$this->input->post("enddate");
				$name=$this->input->post("name");
				$address=$this->input->post("address");
				$loan_amt=$this->input->post("loan_amt");
				$voucher=$this->input->post("voucher");

				$userdata=array(

			    'startdate'=>$startdate,
			    'enddate'=>$enddate,
			    'name'=>$name,
			    'address'=>$address,
			    'loan_amt'=>$loan_amt,
			    'voucher'=>$voucher
			    );
			$this->session->set_userdata($userdata);
			}
			else{

				$startdate=$this->session->userdata("startdate");
				$enddate=$this->session->userdata("enddate");
				$name=$this->session->userdata("name");
				$address=$this->session->userdata("address");
				$loan_amt=$this->session->userdata("loan_amt");
				$voucher=$this->session->userdata("voucher");
			}

			if(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND  unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND unabletoredeem='0'");

				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date BETWEEN '$startdate' AND '$enddate' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && empty($name) && empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && !empty($name) && empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE address='$address' AND customer_name LIKE '%$name%' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
			/**/
			elseif(!empty($startdate) && empty($enddate) && !empty($name) && !empty($address) && empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND customer_name LIKE '%$name%' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				 $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'");
				 $data["collateralList"]=$query;
				 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(empty($startdate) && !empty($enddate) && empty($name) && !empty($address) && empty($loan_amt) && !empty($voucher))
			{
				 $query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' AND unabletoredeem='0'");
				 $data["collateralList"]=$query;
				 $data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$enddate' AND voucher LIKE '%$voucher%' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND unabletoredeem='0'")->row();
			}
			elseif(!empty($startdate) && !empty($enddate) && !empty($name) && !empty($address) && !empty($loan_amt) && !empty($voucher))
			{
				$query=$this->db->query("SELECT * FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'");
				$data["collateralList"]=$query;
				$data["loantotalamt"]=$this->db->query("SELECT SUM(collateral_stock_tbl.loan_amt) as loantotal FROM collateral_stock_tbl WHERE entry_date='$startdate' AND entry_date='$enddate' AND customer_name LIKE '%$name%' AND loan_amt='$loan_amt' AND address='$address' AND voucher LIKE '%$voucher%' AND unabletoredeem='0'")->row();
			}
		// echo $this->db->last_query();exit;

			if($query->num_rows()>=1)
			{

				$data["printlists"]=$query;
				// $data["content"]="";
				// $this->load->view($table."_searchprint",$data);

				$pdf_view=$this->load->view("unabletoredeemList_searchprint",$data, true);
		    	$pdfFilePath = 'collateral_list.pdf';
		    	$this->load->library('m_pdf');
		    	$this->m_pdf->pdf->WriteHTML($pdf_view);
		    	$this->m_pdf->pdf->Output($pdfFilePath, "I");
			}
			else{

			}


	}
	function searchloanamt()
	{
		$voucher=$this->input->post("voucher");
		$this->db->where("voucher",$voucher);
		$query = $this->db->get("collateral_tbl")->row_array();

		$result=json_encode($query);

		echo $result;
	}
	function searchcategory()
	{
		$sign=$this->input->post("sign");
		$this->db->where("sign",$sign);
		$query = $this->db->get("category_tbl")->row_array();

		$result=json_encode($query);

		echo $result;
	}	// incomecategorysearch

	function outsearchcategory()
	{
		$sign=$this->input->post("sign");
		$this->db->where("sign",$sign);
		$query = $this->db->get("category_tbl")->row_array();

		$result=json_encode($query);

		echo $result;
	}	// outcome catergory search
	function earchbudgetscategory()
	{
		$sign=$this->input->post("sign");
		
		$this->db->select("category");
		$this->db->order_by("category");
		$this->db->where("sign",$sign);
		$query = $this->db->get("category_tbl")->row();
		// $result=$query->num_rows();
		// echo $result;exit;	
		echo $query->category;
		
	}	// outcome catergory search
	/*logout*/
	function logout()
	{
		session_destroy();
		redirect('Main/admin_login',"refresh");
	}
	/**/
	}
?>
