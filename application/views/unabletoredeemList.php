<!-- <h3><i class="fa fa-angle-right"></i> ကုန္က်န္စာရင္း</h3> -->

 <div class="padding_sm">
 	<div class="col-lg-12 col-md-12 searchform"> 
	<?=form_open('Main/unabletoredeem_search/')?>
		<div class="col-md-2">
			<label>မွ</label>
			<input type="date" name="startdate" class="form-control" /> 
		</div>
		<div class="col-md-2">
			<label>သို႕</label>
			<input type="date" name="enddate" class="form-control"/> 
		</div>
		<div class="col-md-1">
			<label>ေဘာက္ခ်ာ</label>
			<input type="text" name="voucher" class="form-control">
		</div>
		<div class="col-md-2">
			<label>အမည္</label>
			<input type="text" name="name" class="form-control">
		</div>
		<div class="col-md-2">
			<label>လိပ္စာ</label>
			<input type="text" name="address" class="form-control">
		</div>
		<div class="col-md-2">
			<label>ေခ်းေငြပမာဏ</label>
			<input type="text" name="loan_amt" class="form-control">
		</div>
		<div class="col-md-1" style="padding-top:25px;">
			<button type="submit" value="submit" name="submit" class="btn btn-primary">Search</button>
		</div>
	<?=form_close()?>
	
	</div> 
<div class="col-md-12 col-lg-12 action">
	<div style="padding-top:20px;">
		<?=form_open('Main/searchunabletoredeem_print/')?>	        
	        	<button type="submit" value="submit" name="submit" class="btn btn-success"><i class="fa fa-print"></i></button>	
		<?=form_close()?>
	</div>	

<?=form_open('Main/multiple_unabletoredeem_delete/')?>
    <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"> </i></button>
</div>		
      	
          	<!-- SIMPLE TO DO LIST -->
          	<div class="row mt">
          		<div class="col-md-12">
          			<div class="white-panel pn">	                	
				  		<div class="custom-check goleft mt">
				             <table class="table">
								<thead>
									<tr>
										<th>စဥ္</th>
										<th>ေန႕စြဲ</th>
										
										<th>ေဘာင္ခ်ာနံပါတ္</th>
										<th>အမည္</th>
										<th>လိပ္စာ</th>
										<th>ေခ်းေငြ (က်ပ္)</th>
										<th>ပစၥည္း</th>
										
										<th>က်ပ္</th>
										<th>ပဲ</th>
										<th>ေရြး</th>		
										<th>Action</th>								
									</tr>
								</thead>
								<tbody id="content">

									<?php  
										$no=1;                  
					                    foreach($unabletoredeemList->result() as $row):
					                  ?>
					                <tr>
					                	<td><?php echo $no; ?></td>
					                	<td><?php echo $row->entry_date; ?></td>
										<!-- <td><?php echo $row->sr_no; ?></td> -->
										<td><?php echo $row->stvoucher; ?></td>	
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
										<td style="width:5px;"><input type="checkbox" name="unabletoredeemdelete[]" value="<?=$row->id?>"></td>
										
									</tr>
									<?php 
									$no++;
								endforeach; ?>
									<tr style="background:#5CB85C;color:#000;font-weight:bold;">
										<td colspan="2">Total : <span class="badge" id="total_list"> <?=$unabletoredeemList->num_rows()?>
  </span> </td>      
  									<td colspan="9">ေခ်းေငြ စုစုေပါင္း : <span class="badge" id="total_list"> <?=number_format($loantotalamt->loantotal)?>
  </span> </td>
									</tr>
								</tbody>
							</table>
						</div><!-- /table-responsive -->
					</div><!--/ White-panel -->
          		</div><! --/col-md-12 -->
          	</div><! -- row -->
          	<?=form_close()?>
</div><!-- end search form -->   
<script type="text/javascript">			            
    function unabletoredeem(voucher)
    {
        data="voucher="+voucher;
        
        // alert(data);exit;
        $.ajax({
                type: "POST",
                url : '<?=base_url()?>'+"Main/update_unabletoredeem/",
                data : data,

                success : function(e)
                {    


                    $("#content").html(e);
                	$("#txtchg"+voucher).prop("onclick", null).off("click").html("ဆံုး");

                    $("#txtchg"+voucher). css({"background-color": "red", "cursor":"auto"});
                    
                }
            });
    }

</script>