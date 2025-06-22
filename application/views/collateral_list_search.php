<div class="col-md-12 action">
	<div  class="col-md-1 right" style="padding-top:20px;">
		<a href="Main/searchcollateral_print" class="btn btn-success" target="_blank"><i class="fa fa-print"> </i></a>
	</div>

	<?=form_open('Main/multiple_budget_delete/')?>
	<button type="submit" class="btn btn-success"><i class="fa fa-trash-o"> </i></button>
	<div class="row mt">
		<div class="col-md-12">
			<div class="white-panel pn">	                	
				<div class="custom-check goleft mt">
					<div class="center-content">
						<table class="table" id="center-content">
							<thead>
								<tr>
									<!-- <th>စဥ္</th> -->
									<th>ေန႕စြဲ</th>
									<!-- <th>SrNo</th> -->
									<th>ေဘာင္ခ်ာနံပါတ္</th>
									<th>အမည္</th>
									<th>လိပ္စာ</th>
									<th>ေခ်းေငြ (က်ပ္)</th>
									<th>ပစၥည္း</th>

									<th>က်ပ္</th>
									<th>ပဲ</th>
									<th>ေရြး</th>
									<th style="color:#cc262f;">ၿပင္/ဖ်က္</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="content">
								<?php
								$no=1;                      
								foreach($lists->result() as $row):
									?>
									<tr>
										<!-- <td><?php echo $no; ?></td>	 -->
										<td><?php echo $row->entry_date; ?></td>
										<!-- <td><?php echo $row->sr_no; ?></td> -->
										<td><?php echo $row->vr_type; ?><?php echo $row->vr_no; ?></td>	
										<td><?php echo $row->customer_name; ?></td>
										<td><?php echo $row->address; ?></td>
										<td><?php echo number_format($row->loan_amt); ?></td>
										<td>
											<?php 
											$stock = explode(']', $row->collateral);

											for($i=1;$i<count($stock);$i++)
											{
												$item = explode(',', $stock[$i-1]);

												?> 
												<?=$item[0]?> <?=$item[1]?> ၊ 

											<?php } ?>
										</td>
										<td><?php echo $row->kyat; ?></td>
										<td><?php echo $row->pe; ?></td>
										<td><?php echo $row->ywe; ?></td>
										<td>
											<a href="Main/collateraledit_form/<?php echo $row->id; ?>"><i class="fa fa-edit fa-2x"></i></a>
											<a href="Main/collateral_delete/<?php echo $row->id; ?>" onclick="return confirm('Are you sure to delete?')"><i class="fa fa-trash-o fa-2x"></i></a>
										</td>
										<td style="width:5px;"><input type="checkbox" name="collateraldelete[]" value="<?=$row->id?>"></td>
									</tr>
									<?php 
									$no++;
								endforeach; ?>
							</tbody>
						</table>
					</div>
					<table class="table">
						<tr style="background:#5CB85C;color:#000;font-weight:bold;">
							<td colspan="2">ေဘာက္ခ်ာ စုစုေပါင္း : <span class="badge" id="total_list"> <?=$collateralList->num_rows()?></span> 
							</td>
							<td colspan="9">ေခ်းေငြ စုစုေပါင္း : <span class="badge" id="total_list"> <?=number_format($loantotalamt->loantotal)?></span> 
							</td>
						</tr>
					</table>

				</div><!-- custom-check -->
			</div><!--/ White-panel -->
		</div><! --/col-md-12 -->
	</div><! -- row -->
<?=form_close()?>
</div>