          	
          	
	<div class="col-md-12 mt">
		<!-- <div class="col-md-12"> -->
			<div class="pn" style="background:rgba(0,0,0,0.20);">	                	
	  		<div class="custom-check goleft mt">
	             <?=form_open('Main/collateral_edit/')?>
	             <input type="hidden" name="id" value="<?=$collateraldata->id?>">
	             <input type="hidden" name="entryby" value="<?php echo $this->session->userdata('username'); ?>">
	             <div class="gray-border form-box">
	             	<div class="col-md-12 top-form-box" style="background: #e0a418;">
	             		<div class="col-md-4 col-lg-4">
	             			<p class="center white-color left" style="margin-top:10px !important;"> ထည္႕သြင္းသူ - <?php echo $this->session->userdata('username'); ?></p>
	             		</div>
	             		<div class="col-md-4 col-lg-4">
	             			<h3 class="center white-color" style="margin-top:10px !important;"> <i class="fa fa-angle-right"></i>  အေပါင္စာရင္း ၿပင္ဆင္ရန္ <i class="fa fa-angle-left"> </i></h3>
	             		</div>
	             		
	             		<div class="col-md-4 col-lg-4">
	             			<p class="center white-color right" style="margin-top:10px !important;"><i class="fa fa-calendar">  ယေန႕ ေန႕စြဲ - <?php echo date('d-m-Y')?></i></p>
	             		</div>
	             	</div>
	             	<div class="col-md-12 padding_md form-horizontal">
	             		<div class="col-md-12">
							<div class="col-md-3"></div>
							<div class="col-md-3"></div>
							<div class="col-md-4"><h4 style=""><span class="text-right btn redborder_btn" onclick="collateralcloneform(event)" style="float:right" ><i class="fa fa-plus">  Add New </i></span> </div>
							<div class="col-md-2"></div>
						</div>
	             		<div class="col-md-3">  							
								<div class="form-group">
										<label class="col-md-4 control-label">Date</label>
										
										<div class="col-md-8">		
											<input type="date" name="entrydate" class="form-control" value="<?=$collateraldata->entry_date?>" >		
										</div>
								</div>								
								
								<!-- <div class="form-group">
									<label class="col-md-4 control-label">Sr No</label>		
									<div class="col-md-8">		
										<input type="text" name="srno" class="form-control" value="<?=$collateraldata->sr_no?>">		
									</div>
								</div>	 -->

								<div class="form-group">
									<label class="col-md-4 control-label">Vr No</label>		
									<div class="col-md-4">		
										<input type="text" name="vrtype" class="form-control" value="<?=$collateraldata->vr_type?>">		
									</div>
									<div class="col-md-4">		
										<input type="text" name="vrno" class="form-control" value="<?=$collateraldata->vr_no?>">		
									</div>
								</div>		
							
	             		</div>
	             		<div class="col-md-3">
							
							<div class="form-group">
									<label class="col-md-4 control-label">အမည္</label>		
									<div class="col-md-8">		
										<input type="text" name="customername" class="form-control" value="<?=$collateraldata->customer_name?>">		
									</div>
							</div>	

							<div class="form-group">
									<label class="col-md-4 control-label">ေနရပ္</label>		
									<div class="col-md-8">		
										<input list="browsers" name="address" class="form-control" value="<?=$collateraldata->address?>">
										<datalist id="browsers">
						                    <?php 
						                        foreach($addresslist as $row)
						                    { 
						                      echo '
						                      <option value="'.$row->address.'">'.$row->address.'</option>
						                      ';
						                    }
						                    ?>
						                </datalist>		
									</div>
							</div>
	             		</div>
	             		<div class="col-md-4">
	             			<div class="form-group col-md-12" id="collateral">
								<div class="collateralclone">
									<?php 
						            $collateral = explode(']', $collateraldata->collateral);

						            for($i=1;$i<count($collateral);$i++)
						            {
						              $col = explode(',', $collateral[$i-1]);

						                 ?> 
									<div class="col-md-5 nopadding">
										<label class="col-md-4 control-label nopadding">ပစၥည္း</label>		
										<div class="col-md-8 nopadding">		
											<input type="text" name="collateral[]" class="form-control" value="<?php echo $col[0]; ?>">	
										</div>
									</div>
									<div class="col-md-5 nopadding">
										<label class="col-md-8 control-label nopadding">အေရအတြက္</label>		
										<div class="col-md-4 nopadding">		
											<input type="text" name="collateral_qty[]" class="form-control" value="<?php echo $col[1]; ?>">		
										</div>
									</div>
									
									<div class="col-md-2">
										<span class='btn btn-danger' onclick="removerlgn(event)" style="margin-left:15px;"> x </span>
									</div>
								<?php } ?>
								</div>
							</div>
	             		</div>
	             		<div class="col-md-2">
	             			<div class="form-group">
								<label class="col-md-4 control-label">က်ပ္</label>
								<div class="col-md-8">							
								<input type="text" name="kyat" class="form-control" value="<?=$collateraldata->kyat?>">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">ပဲ</label>					
								<div class="col-md-8">							
								<input type="text" name="pe" class="form-control" value="<?=$collateraldata->pe?>">				
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">ေရြး</label>				
								<div class="col-md-8">								
								<input type="text" name="ywe" class="form-control" value="<?=$collateraldata->ywe?>">				
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label" style="color: #fcd70d !important;">ေခ်းေငြ</label>				
								<div class="col-md-8">								
								<input type="text" name="loan" id="number" class="form-control" value="<?=$collateraldata->loan_amt?>" onkeyup=changetonumber(this.value)> 			
								</div>
							</div>
	             		</div>
	             	<div class="toppadding_md right">
	             		<button type="submit" class="btn btn-success">Save</button>
	             	</div>
	             	</div>
					<?=form_close()?>
					<div class="col-md-12 bottom-form-box padding_sm" style="background: #f0bd44;">
						<p><span style="font-weight:bold;"><?php echo date("Y-m-d"); ?></span> ေန႕အတြက္ စုစုေပါင္း ေဘာက္ခ်ာအေရအတြက္ - <?php echo $totalvoucher->total; ?>

						<span style="font-weight:bold; margin-left:50px;">စုစုေပါင္းေခ်းေငြ</span> - <?php echo $totalloanamt->loan_total; ?></p>

						<p><span style="font-weight:bold;"><?php echo $monthname; ?></span>  လ အတြက္ စုစုေပါင္း ေဘာက္ခ်ာအေရအတြက္ - <?php echo $mtotalvoucher->total; ?>

						<span style="font-weight:bold; margin-left:50px;">စုစုေပါင္းေခ်းေငြ</span> - <?php echo $mtotalloanamt->loan_total; ?></p>
					</div>
			</div>
		</div><!--/ White-panel -->
		<!-- </div> --><!-- col md 12 -->
	</div><!-- row -->


<!-- <div class="content-box">

	
</div> -->