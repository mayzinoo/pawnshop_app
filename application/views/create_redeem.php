<!-- <h3><i class="fa fa-angle-right"></i> အေရြးစာရင္းသြင္းရန္</h3>          	 -->

	<div class="col-md-12 mt">
		<!-- <div class="col-md-12"> -->
			<div class="pn" style="background:rgba(0,0,0,0.20);">
	  		<div class="custom-check goleft mt">
	             <?=form_open('Main/redeem_insert/')?>
	             <input type="hidden" name="entryby" value="<?php echo $this->session->userdata('username'); ?>">

	             <div class="gray-border form-box">
	             	<div class="col-md-12 top-form-box">
	             		<div class="col-md-4 col-lg-4">
	             			<p class="center white-color left" style="margin-top:10px !important;">အမွတ္စဥ္ - <b><span style="color:#000;"> <?php echo $id+1;?> </span></b>/ ထည္႕သြင္းသူ - <?php echo $this->session->userdata('username'); ?></p>
	             		</div>
	             		<div class="col-md-4 col-lg-4">
	             			<h3 class="center white-color" style="margin-top:10px !important;"> <i class="fa fa-angle-right"></i>  အေရြးစာရင္းသြင္းရန္ <i class="fa fa-angle-left"> </i></h3>
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
										<input name="entrydate" class="form-control" value="<?php echo date('d-m-Y')?>">
									</div>
							</div>
							<div class="form-group">
									<label class="col-md-4 control-label">VR နံပါတ္</label>

									<div class="col-md-8">
										<input list="browsers" name="voucher" id="voucher" class="form-control" onchange=loanamtsearch(this.value) onblur=checkredeemvoucher(this.value) required>
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
										<input type="text" name="getmoney" class="form-control" id="getmoney" required>
									</div>
							</div>
							<div class="form-group">
									<label class="col-md-4 control-label">အမည္</label>
									<div class="col-md-8">
										<input type="text" name="name" class="form-control" id="getname" required>
									</div>
							</div>
							<div class="form-group">
									<label class="col-md-4 control-label">လိပ္စာ</label>
									<div class="col-md-8">
										<input type="text" name="address" class="form-control" id="getaddress" required>
									</div>
							</div>


	             		</div>

	             		<div class="col-md-4 col-lg-4">
	             			<div class="form-group">
								<label class="col-md-4 control-label">လေပါင္း</label>
								<div class="col-md-8">
									<input type="text" name="totalmonth" class="form-control" id="totalmonth" required>
								</div>
							</div>

	             			<div class="form-group">
								<label class="col-md-4 control-label">ႏႈန္း</label>
								<div class="col-md-8">
									<input type="text" name="rate" class="form-control" id="rate" required>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">တြက္ခ်က္ရေငြ</label>
								<div class="col-md-8">
									<input type="text" name="calculatemoney" class="form-control" id="result" required>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">အမွန္ရေငြ</label>
								<div class="col-md-8">
									<input type="text" name="realgetmoney" class="form-control" id="realget_amt" required>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">အစြန္းထြက္</label>
								<div class="col-md-8">
									<input type="text" name="balance" class="form-control" id="balance_result" required>
								</div>
							</div>
	             		</div>

	             	</div>
	             	<div class="col-md-offset-3 col-md-4 padding_md">
		             		<div class="right"><button type="submit" class="btn btn-success">Save</button></div>
		            </div>
					<?=form_close()?>
					</div>
					<div class="col-md-12 bottom-form-box padding_sm">
						<p><span class="todaydate_bg"><?php echo date("Y-m-d"); ?></span> ေန႕အတြက္ =>  စုစုေပါင္း ေဘာက္ခ်ာအေရအတြက္ - <?php echo $totalvoucher->total; ?>

						<span style="font-weight:bold; margin-left:20px;">စုစုေပါင္းေခ်းေငြ</span> - <?php echo number_format($totalloanamt->loan_total); ?>

					 <span style="font-weight:bold; margin-left:20px;">စုစုေပါင္း အမွန္ရေငြြ</span> - <?php echo number_format($totalnetamt->nettotal); ?>

					 <span style="font-weight:bold; margin-left:20px;">စုစုေပါင္း တြက္ခ်က္ရေငြ</span> - <?php echo number_format($totalcalcuamt->calcutotal); ?>

					 <span style="font-weight:bold; margin-left:20px;">အစြန္းထြက္ေငြ စုစုေပါင္း</span> - <?php echo number_format($totalbalanceamt->balancetotal); ?>
				 		</p>

						<p><span class="todaydate_bg"><?php echo $monthname; ?></span>  လ အတြက္ => စုစုေပါင္း ေဘာက္ခ်ာအေရအတြက္ - <?php echo number_format($mtotalvoucher->total); ?>

						<span style="font-weight:bold; margin-left:20px;">စုစုေပါင္းေခ်းေငြ</span> - <?php echo number_format($mtotalloanamt->loan_total); ?>

						<span style="font-weight:bold; margin-left:20px;">စုစုေပါင္း အမွန္ရေငြ</span> - <?php echo number_format($mtotalnetamt->nettotal); ?>

						<span style="font-weight:bold; margin-left:20px;">စုစုေပါင္း တြက္ခ်က္ရေငြ</span> - <?php echo number_format($mtotalcalculate->calcutotal); ?>

						<span style="font-weight:bold; margin-left:20px;">အစြန္းထြက္ေငြ စုစုေပါင္း</span> - <?php echo number_format($mtotalbalance->balancetotal); ?>
					</p>
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
       getmoney = Number($('#getmoney').val());
       totalmonth = Number($('#totalmonth').val());
       rate = Number($('#rate').val());

       var result = (getmoney/100) * totalmonth * rate;
 		$('#result').val(result.toFixed(2));
       //$('#result').val(result.toFixed(2));
   });
</script>

<script>
   $('#realget_amt').keyup(function(){
       var result;
       var realget_amt;

       result = Number($('#result').val());
       realget_amt = $('#realget_amt').val();

       var balance_result = result - realget_amt;
       $('#balance_result').val(balance_result.toFixed(0));
   });
</script>
