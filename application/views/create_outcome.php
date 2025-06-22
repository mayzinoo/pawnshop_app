<div class="col-md-12 mt">
	<div class="col-md-6" style="border-right:1px solid #ccc;">
		<div class="col-md-12">
				<p class="col-md-offset-4" style="color:#FFD777;font-size:16px;">၀င္ေငြ စာရင္းထည္႕ရန္</p>
				<div class="form-horizontal">
				<?=form_open('Main/income_outcome_insert/')?>
				<div class="topmargin_md">
					<div class="row">
							<div class="col-md-6">
									<label class="col-md-2">ေန႕စြဲ</label>
									<div class="col-md-8">
										<input name="entrydate" class="form-control" value="<?php echo date('d-m-Y')?>">
									</div>
							</div>
							<div class="col-md-6">
									<input type="text" name="netamt" class="form-control netamt" id="netamt" value="<?php echo number_format($totalincome->incometotal);?>">
							</div>
					</div>
					<div class="col-md-12 toppadding_sm">
						<span class="text-right btn redborder_btn" onclick="budgetcloneform(event)" style="float:right" ><i class="fa fa-plus">  Add New </i></span>
					</div>
					<div class="col-md-12" id="budget">
							<div class="budgetclone">
								<div class="col-md-1 nopadding">
									<!-- <div class="form-group"> -->
												<input type="text" name="sign[]" class="form-control" required onchange=categorysearch(this.value,event)>
									<!-- </div> -->
								</div>
								<div class="col-md-6">
											<!-- <div class="form-group"> -->
													<input type="text" name="category[]" class="form-control" required >
											<!-- </div> -->
								</div>
								<div class="col-md-4 nopadding">
											<!-- <div class="form-group"> -->
														<input type="text" name="income_amt[]" id="number" class="form-control" required>
											<!-- </div> -->
								</div>
								<div class="col-md-1">
									<span class='btn btn-danger' onclick="remover(event)" style="margin-left:15px;"> x </span>
								</div>
							</div>
					</div>

					<div class="right toppadding_sm"><button type="submit" class="btn btn-success">Save</button></div>
				</div>
				<?=form_close()?>
				</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="col-md-12">
				<p class="col-md-offset-4" style="color:#FFD777;font-size:16px;">ထြက္ေငြ စာရင္း ထည္႕ရန္</p>
				<div class="form-horizontal">
				<?=form_open('Main/income_outcome_insert/')?>
				<div class="topmargin_md">
					<div class="row">
							<div class="col-md-6">
									<label class="col-md-2">ေန႕စြဲ</label>
									<div class="col-md-8">
										<input name="entrydate" class="form-control" value="<?php echo date('d-m-Y')?>">
									</div>
							</div>
							<div class="col-md-6">
									<input type="text" name="netamt" class="form-control netamt" id="netamt" value="<?php echo number_format($totaloutcome->outcometotal);?>">
							</div>
					</div>
					<div class="col-md-12 toppadding_sm">
						<span class="text-right btn redborder_btn" onclick="outbudgetcloneform(event)" style="float:right" ><i class="fa fa-plus">  Add New </i></span>
					</div>
					<div class="col-md-12" id="outbudget">
							<div class="outbudgetclone">
								<div class="col-md-1 nopadding">
									<!-- <div class="form-group"> -->
												<input type="text" name="sign[]" class="form-control" required onchange=outcategorysearch(this.value,event)>
									<!-- </div> -->
								</div>
								<div class="col-md-6">
											<!-- <div class="form-group"> -->
													<input type="text" name="category[]" class="form-control" required >
											<!-- </div> -->
								</div>
								<div class="col-md-4 nopadding">
											<!-- <div class="form-group"> -->
														<input type="text" name="outcome_amt[]" id="outnumber" class="form-control" required>
											<!-- </div> -->
								</div>
								<div class="col-md-1">
									<span class='btn btn-danger' onclick="outremover(event)" style="margin-left:15px;"> x </span>
								</div>
							</div>
					</div>

					<div class="right toppadding_sm"><button type="submit" class="btn btn-success">Save</button></div>
				</div>

				<?=form_close()?>
				</div>
		</div>
	</div>
</div>
