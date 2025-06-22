<div class="col-md-12 action">
	<div  class="col-md-1 right" style="padding-top:20px;">
		<a href="Main/searchredeem_print" class="btn btn-success" target="_blank"><i class="fa fa-print"> </i></a>
	</div>

<?=form_open('Main/multiple_redeem_delete/')?>
    <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"> </i></button>
<div class="row mt">
          		<div class="col-md-12">
          			<div class="white-panel pn">	                	
				  		<div class="custom-check goleft mt">
				             <table class="table">
								<thead>
									<tr>
										<th>စဥ္</th>
										<th>VR နံပါတ္</th>
										<th>အမည္</th>
										<th>လိပ္စာ</th>
										<th>ၿပန္ရေငြေပါင္း</th>
										<th>လေပါင္း</th>
										<th>ႏႈန္း</th>
										<th>တြက္ခ်က္ရေငြ</th>
										<th>အမွန္ရေငြ</th>
										<th>အစြန္းထြက္+/-</th>	
										<th>ေရြးသည္႕ေန႕စြဲ</th>
										<th style="color:#cc262f;">ၿပင္</th>	
										<th></th>
									</tr>
								</thead>
								<tbody id="content">
									<?php 
										$no=1;                   
					                    foreach($lists->result() as $row):
					                  ?>
					                <tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $row->voucher; ?></td>
										<td><?php echo $row->customer_name; ?></td>
										<td><?php echo $row->address; ?></td>
										<td><?php echo number_format($row->getmoney); ?></td>
										<td><?php echo $row->total_month; ?></td>
										<td><?php echo $row->rate; ?></td>
										<td><?php echo number_format($row->calculate_money); ?></td>
										<td><?php echo number_format($row->realget_money); ?></td>
										<td><?php echo $row->balance_money; ?></td>
										<td><?php echo $row->entry_date; ?></td>
										<td>
											<a href="Main/redeemedit_form/<?php echo $row->id; ?>"><i class="fa fa-edit fa-2x"></i></a>
											<!-- <a href="Main/redeem_delete/<?php echo $row->id; ?>" onclick="return confirm('Are you sure to delete?')"><i class="fa fa-trash-o fa-2x"></i></a> -->
										</td>
										<td style="width:5px;"><input type="checkbox" name="redeemdelete[]" value="<?=$row->id?>"></td>
									</tr>
									<?php 
									$no++;
									endforeach; ?>  
									<tr style="background:#5CB85C;color:#000;font-weight:bold;">
										<td colspan="3">ေဘာက္ခ်ာ စုစုေပါင္း : <span class="badge" id="total_list"> <?=$collateralList->num_rows()?>
  										</span> </td>
  										<td colspan="10">ၿပန္ရေငြ စုစုေပါင္း : <span class="badge" > <?=number_format($loantotalamt->loantotal)?>
  										</span> </td>
									</tr>
									<tr style="background:#5CB85C;color:#000;font-weight:bold;">
										<td colspan="3">အမွန္ရေငြ စုစုေပါင္း : <span class="badge" > <?=number_format($loantotalamt->netamt)?>
  										</span> </td>
  										<td colspan="10">တြက္ခ်က္ရေငြ စုစုေပါင္း : <span class="badge" > <?=number_format($loantotalamt->calculateamt)?>
  										</span> </td>
									</tr>
									<tr style="background:#5CB85C;color:#000;font-weight:bold;">
										<td colspan="13">အစြန္းထြက္ စုစုေပါင္း : <span class="badge" > <?=number_format($loantotalamt->balanceamt)?>
  										</span> </td>
									</tr>
								</tbody>
							</table>
						</div><!-- /table-responsive -->
					</div><!--/ White-panel -->
          		</div><! --/col-md-12 -->
          	</div><! -- row -->
 </div>