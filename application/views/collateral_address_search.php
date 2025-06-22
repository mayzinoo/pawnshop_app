<div class="row">

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
	                    foreach($lists->result() as $row):
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