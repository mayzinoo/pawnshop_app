<!-- <h3><i class="fa fa-angle-right"></i> ကုန္က်န္စာရင္း</h3> -->

 <div class="padding_sm">
 	
	<div class="col-lg-12 col-md-12 searchform"> 
	<?=form_open('Main/collateralstock_search/')?>
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
		<?=form_open('Main/searchstock_print/')?>	        
	        	<button type="submit" value="submit" name="submit" class="btn btn-success"><i class="fa fa-print"></i></button>	
		<?=form_close()?>
	</div>	

<?=form_open('Main/multiple_stock_delete/')?>
    <button type="submit" class="btn btn-success"><i class="fa fa-trash-o"> </i></button>
</div>	
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
				                    foreach($stockList->result() as $row):

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
										<?php if($row->unabletoredeem=="0"){
				                          ?>
				                      <a style="padding:5px;color:white !important;background:red;border: 1px solid transparent;border-radius: 4px;">ဆံုး</a>

				                          <?php
				                      } else {?>
				                      
										<span onclick="unabletoredeem('<?=$row->voucher?>')" style="cursor:pointer;padding:5px;color:white !important;background:green" id="txtchg<?=$row->voucher?>" class="btn"><i class="fa fa-check"></i></span>

										<?php

                  						} ?>
									</td>
									
                  					<td style="width:5px;"><input type="checkbox" name="allstockdelete[]" value="<?=$row->id?>"></td>
								</tr>

								<?php 
								$no++;
							endforeach; ?>
								<tr>
									<td colspan="2" style="background:#5CB85C;color:#000;font-weight:bold;">ေဘာက္ခ်ာ စုစုေပါင္း : <span class="badge" id="total_list"> <?=$stockList->num_rows()?>
</span> </td>
									<td colspan="8" style="background:#5CB85C;color:#000;font-weight:bold;">ေခ်းေငြ စုစုေပါင္း : <span class="badge" id="total_list"> <?=number_format($loantotalamt->loantotal)?>
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


<script type="text/javascript">			            
    function unabletoredeem(voucher)
    {
        data="voucher="+voucher;
        
      //  alert(data);
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