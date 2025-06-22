<!-- <h3><i class="fa fa-angle-right"></i> အေရြးစာရင္းသြင္းရန္</h3>          	 -->
         	
	<div class="col-md-12 mt">
		<!-- <div class="col-md-12"> -->
			<div class="pn" style="background:rgba(0,0,0,0.20);">	                	
	  		<div class="custom-check goleft mt">
	             <?=form_open('Main/redeem_edit/')?>
	             <input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
	             <!-- <input type="hidden" name="id" value="<?php echo $redeemdata->status; ?>"> -->
	             <input type="hidden" name="entryby" value="<?php echo $this->session->userdata('username'); ?>">
	             <div class="gray-border form-box">
	             	<div class="col-md-12 top-form-box">
	             		<div class="col-md-4 col-lg-4">
	             			<p class="center white-color left" style="margin-top:10px !important;"> ထည္႕သြင္းသူ - <?php echo $this->session->userdata('username'); ?></p>
	             		</div>
	             		<div class="col-md-4 col-lg-4">
	             			<h3 class="center white-color" style="margin-top:10px !important;"> <i class="fa fa-angle-right"></i>  အေရြးစာရင္း ၿပင္ဆင္ရန္ <i class="fa fa-angle-left"> </i></h3>
	             		</div>
	             		
	             		<div class="col-md-4 col-lg-4">
	             			<p class="center white-color right" style="margin-top:10px !important;"><i class="fa fa-calendar">  ယေန႕ ေန႕စြဲ - <?php echo date('d-m-Y')?></i></p>
	             		</div>
	             	</div><!-- top header -->
	             	<div class="col-md-12 toppadding_lg form-horizontal">	             		
	             		<div class="col-md-offset-2 col-md-4 col-lg-4">
	             			<div class="form-group">
									<label class="col-md-4 control-label">Date</label>
									
									<div class="col-md-8">		
										<input type="date" name="entrydate" class="form-control" value="<?php echo $redeemdata->entry_date; ?>">		
									</div>
							</div>
							<div class="form-group">
									<label class="col-md-4 control-label">VR နံပါတ္</label>

									<div class="col-md-8">		
										<input list="browsers" name="voucher" class="form-control" onchange=loanamtsearch(this.value) value="<?php echo $redeemdata->voucher; ?>">
						                <datalist id="browsers">
						                    <?php 
						                        foreach($voucherlist as $row)
						                    { 
						                      echo '
						                      <option value="'.$row->voucher.'">'.$row->voucher.'</option>
						                      ';
						                    }
						                    ?>
						                </datalist>		
									</div>
							</div>

							<div class="form-group">
									<label class="col-md-4 control-label">ၿပန္ရေငြေပါင္း</label>		
									<div class="col-md-8">		
										<input type="text" name="getmoney" class="form-control" id="getmoney" value="<?php echo $redeemdata->getmoney; ?>">		
									</div>									
							</div>
							<div class="form-group">
									<label class="col-md-4 control-label">အမည္</label>
									<div class="col-md-8">
										<input type="text" name="name" class="form-control" id="getname" value="<?php echo $redeemdata->customer_name; ?>">
									</div>
							</div>
							<div class="form-group">
									<label class="col-md-4 control-label">လိပ္စာ</label>
									<div class="col-md-8">
										<input type="text" name="address" class="form-control" id="getaddress" value="<?php echo $redeemdata->address; ?>">
									</div>
							</div>

											
	             		</div>

	             		<div class="col-md-4 col-lg-4">
	             			<div class="form-group">
								<label class="col-md-4 control-label">လေပါင္း</label>		
								<div class="col-md-8">		
									<input type="text" name="totalmonth" class="form-control" id="totalmonth" value="<?php echo $redeemdata->total_month; ?>">		
								</div>									
							</div>
	             			<div class="form-group">
								<label class="col-md-4 control-label">ႏႈန္း</label>		
								<div class="col-md-8">		
									<input type="text" name="rate" class="form-control" id="rate" value="<?php echo $redeemdata->rate; ?>">		
								</div>									
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">တြက္ခ်က္ရေငြ</label>		
								<div class="col-md-8">		
									<input type="text" name="calculatemoney" class="form-control" id="result" value="<?php echo $redeemdata->calculate_money; ?>">		
								</div>									
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">အမွန္ရေငြ</label>		
								<div class="col-md-8">		
									<input type="text" name="realgetmoney" class="form-control" id="realget_amt"  value="<?php echo $redeemdata->realget_money; ?>">		
								</div>									
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">အစြန္းထြက္</label>		
								<div class="col-md-8">		
									<input type="text" name="balance" class="form-control" id="balance_result" value="<?php echo $redeemdata->balance_money; ?>">		
								</div>									
							</div>	
	             		</div>

	             	</div> 
	             	<div class="col-md-offset-3 col-md-4 padding_md">
		             		<div class="right"><button type="submit" class="btn btn-success">Save</button></div>
		            </div>
					<?=form_close()?>
					</div>
			</div>
		</div><!--/ White-panel -->
		<!-- </div> --><!-- col md 12 -->
	</div><!-- row -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>
   $('#rate').keyup(function(){
       var getmoney;
       var totalmonth;
       var rate;
       getmoney = parseFloat($('#getmoney').val());
       totalmonth = parseFloat($('#totalmonth').val());
       rate = parseFloat($('#rate').val());
       
       var result = getmoney/100 * totalmonth * rate;

       $('#result').val(result.toFixed(2));
   });
</script>

<script>
   $('#realget_amt').keyup(function(){
       var result;
       var realget_amt;

       result = parseFloat($('#result').val());
       realget_amt = $('#realget_amt').val();

       var balance_result = result - realget_amt;
       $('#balance_result').val(balance_result.toFixed(0));
   });
</script>


