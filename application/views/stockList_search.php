<div class="col-md-12 action">
	<div class="col-md-1 right" style="padding-top:20px;">
		<a href="Main/searchstock_print" class="btn btn-success" target="_blank"><i class="fa fa-print"></i></a>
	</div>

<?=form_open('Main/multiple_stock_delete/')?>
    <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"> </i></button>
<div class="row mt">
          		<div class="col-md-12">
          			<div class="white-panel pn">	                	
				  		<div class="custom-check goleft mt">
				             <table class="table">
								<thead>
									<tr>
										<!-- <th>စဥ္</th>										 -->
										<th>ေဘာင္ခ်ာနံပါတ္</th>	
										<th>အမည္</th>		
										<th>လိပ္စာ</th>	
										<th>ေခ်းေငြ</th>						
										<th>ပစၥည္း</th>										
										<th>က်ပ္</th>
										<th>ပဲ</th>
										<th>ေရြး</th>
										<th>ေပါင္သည္႕ေန႕စြဲ</th>
										<th style="color:#fc0214;">အေပါင္ဆံုး ?</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="content">

									<?php  
										 $no=1;              
					                    foreach($lists->result() as $row):

					                  ?>
					                <tr>
					                	<!-- <td><?php echo $no; ?></td>										 -->
										<td><?php echo $row->voucher; ?></td>	
										<td><?php echo $row->customer_name; ?></td>
										<td><?php echo $row->address; ?></td>
										<td><?php echo number_format($row->loan_amt); ?></td>
										<td>
											<?php 
								            $stock = explode(']', $row->stock_item);

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
										<td><?php echo $row->entry_date; ?></td>
										<td>
											<?php   if($row->unabletoredeem=="0"){
					                          ?>
					                      <a style="padding:5px;color:white !important;background:red;border: 1px solid transparent;border-radius: 4px;">ဆံုး</a>

					                          <?php
					                      } else
					                      {?>
											<button onclick="unabletoredeem('<?=$row->voucher?>')" style="cursor:pointer;padding:5px;color:white !important;background:green" id="txtchg<?=$row->voucher?>" class="btn"><i class="fa fa-check"></i></button>
										</td>
										<td style="width:5px;"><input type="checkbox" name="allstockdelete[]" value="<?=$row->id?>"></td>
										<?php

                      					} ?>
									</tr>
									<?php 
									$no++;
								endforeach; ?>
									<tr>
										<td colspan="2" style="background:#5CB85C;color:#000;font-weight:bold;">ေဘာက္ခ်ာ စုစုေပါင္း : <span class="badge" id="total_list"> <?=$collateralList->num_rows()?>
  </span> </td>
  										<td colspan="9" style="background:#5CB85C;color:#000;font-weight:bold;">ေခ်းေငြ စုစုေပါင္း : <span class="badge" id="total_list"> <?=number_format($loantotalamt->loantotal)?>
  </span> </td>
									</tr>
								</tbody>
							</table>
						</div><!-- /table-responsive -->
					</div><!--/ White-panel -->
          		</div><! --/col-md-12 -->
          	</div><! -- row -->
<?=form_close()?>
</div>