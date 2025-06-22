 <?php
 parse_str("zero=0");
  ?>

	<div class="col-md-12 mt">
		<!-- <div class="col-md-12"> -->
			<div class="pn" style="background:rgba(0,0,0,0.20);">
	  		<div class="custom-check goleft mt">
	             <?=form_open('Main/collateral_insert/')?>
	             <input type="hidden" name="entryby" value="<?php echo $this->session->userdata('username'); ?>">
	             <div class="gray-border form-box">
	             	<div class="col-md-12 top-form-box">
	             		<div class="col-md-4 col-lg-4">
	             			<p class="center white-color left" style="margin-top:10px !important;">အမွတ္စဥ္ - <b><span style="color:#000;"> <?php echo $id+1;?> </span></b>/ ထည္႕သြင္းသူ - <?php echo $this->session->userdata('username'); ?></p>
	             		</div>
	             		<div class="col-md-4 col-lg-4">
	             			<h3 class="center white-color" style="margin-top:10px !important;"> <i class="fa fa-angle-right"></i> အပေါင်စာရင်း သွင်းရန် <i class="fa fa-angle-left"> </i></h3>
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
											<input type="text" name="entrydate" class="form-control" value="<?php echo date('d-m-Y')?>" >
										</div>
								</div>

								<!-- <div class="form-group">
									<label class="col-md-4 control-label">Sr No</label>
									<div class="col-md-8">
										<input type="text" name="srno" class="form-control">
									</div>
								</div>	 -->

								<div class="form-group">
									<label class="col-md-4 control-label">Vr No</label>
									<div class="col-md-4">
										<input type="text" name="vrtype" id="vrtype" class="form-control" required>
									</div>
									<div class="col-md-4">

										<input type="text" name="vrno" id="vrno" class="form-control" required onblur=checkvoucher(this.value)>

									</div>
								</div>

	             		</div>
	             		<div class="col-md-3">

							<div class="form-group">
									<label class="col-md-4 control-label">အမည္</label>
									<div class="col-md-8">
										<input type="text" name="customername" class="form-control" required>
									</div>
							</div>

							<div class="form-group">
									<label class="col-md-4 control-label">ေနရပ္</label>
									<div class="col-md-8">
										<input list="browsers" name="address" class="form-control" required>
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
									<div class="col-md-5 nopadding">
										<label class="col-md-4 control-label nopadding">ပစၥည္း</label>
										<div class="col-md-8 nopadding">
											<input list="items" name="collateral[]" class="form-control" required>
											<datalist id="items">
						                    <?php
						                        foreach($citemslist as $item)
						                    {
						                      echo '
						                      <option value="'.$item->citems.'">'.$item->citems.'</option>
						                      ';
						                    }
						                    ?>
						          </datalist>
										</div>
									</div>
									<div class="col-md-5 nopadding">
										<label class="col-md-8 control-label nopadding">အေရအတြက္</label>
										<div class="col-md-4 nopadding">
											<input type="text" name="collateral_qty[]" class="form-control" required>
										</div>
									</div>

									<div class="col-md-2">
										<span class='btn btn-danger' onclick="removerlgn(event)" style="margin-left:15px;"> x </span>
									</div>
								</div>
							</div>
	             		</div>
	             		<div class="col-md-2">
	             			<div class="form-group">
								<label class="col-md-4 control-label">က်ပ္</label>
								<div class="col-md-8">
								<input type="text" name="kyat" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">ပဲ</label>
								<div class="col-md-8">
								<input type="text" name="pe" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">ေရြး</label>
								<div class="col-md-8">
								<input type="text" name="ywe" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label" style="color: #fcd70d !important;">ေခ်းေငြ</label>
								<div class="col-md-8">
								<input type="text" name="loan" id="number" class="form-control" onkeyup=changetonumber(this.value) required>
								</div>
							</div>
	             		</div>
	             	<div class="toppadding_md right">
	             		<button type="submit" class="btn btn-success">Save</button>
	             	</div>
	             	</div>
					<?=form_close()?>
					<div class="col-md-12 bottom-form-box padding_sm">
						<p><span style="font-weight:bold;"><?php echo date("Y-m-d"); ?></span> ေန႕အတြက္ စုစုေပါင္း ေဘာက္ခ်ာအေရအတြက္ - <?php echo $totalvoucher->total; ?>

						<span style="font-weight:bold; margin-left:50px;">စုစုေပါင္းေခ်းေငြ</span> - <?php echo number_format($totalloanamt->loan_total); ?></p>

						<p><span style="font-weight:bold;"><?php echo $monthname; ?></span>  လ အတြက္ စုစုေပါင္း ေဘာက္ခ်ာအေရအတြက္ - <?php echo number_format($mtotalvoucher->total); ?>

						<span style="font-weight:bold; margin-left:50px;">စုစုေပါင္းေခ်းေငြ</span> - <?php echo number_format($mtotalloanamt->loan_total); ?></p>
					</div>
			</div>
		</div><!--/ White-panel -->
		<!-- </div> --><!-- col md 12 -->
	</div><!-- row -->


<!-- <div class="content-box">


</div> -->
