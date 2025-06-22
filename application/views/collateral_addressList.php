<div class="row searchform">
	<div class="col-lg-12 col-md-12"> 
	<?=form_open('Main/collateraladdress_search/')?>
		<div class="col-md-2">
			<label>မွ</label>
			<input type="text" name="startdate" id="date" class="form-control" /> 
		</div>
		<div class="col-md-2">
			<label>သို႕</label>
			<input type="text" name="enddate" id="enddate" class="form-control"/> 
		</div>
		<div class="col-md-1" style="padding-top:25px;">
			<button type="submit" value="submit" name="submit" class="btn btn-primary">Search</button>
		</div>
	<?=form_close()?>
	<div class="col-md-12">
		<div class="white-panel pn">	                	
  		<div class="custom-check goleft mt">
             <table class="table">
             	<thead>
					<tr>
						<th style="width:10%;">စဥ္</th>
						<th style="width:20%;">ေက်းရြာမ်ား</th>
						<th>Total</th>
						
						
					</tr>
				<tbody>
					
					<?php   
					$i=1;                 
	                    foreach($collateral_address->result() as $row):
	                  ?>
	                <tr>
	                	<td><?php echo $i; ?></td>
	                	<td><?php echo $row->address; ?></td>
	                	<td><?php echo $row->total; ?></td>
						
						
	                </tr>
	                <?php 
	                $i++;
	            	endforeach; ?>
	            	<!-- Max total: <?php echo $collateral_address->maxtotal; ?> -->
             </table>
        </div>
    </div>
</div>

<script type="text/javascript">
	 $('#date').datepicker({
                    format: "yyyy-mm-dd"
                });
</script>