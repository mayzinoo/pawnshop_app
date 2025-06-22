<div class="row mt">
	<div class="col-md-12">
		<div class="white-panel pn">	                	
  		<div class="custom-check goleft mt">
             <table class="table">
             	<thead>
					<tr>
						<th style="width:10%;">စဥ္</th>
						<th style="width:20%;">ပစၥည္း</th>
						<th>ေန႕စြဲ</th>
						<th>ၿပင္/ဖ်က္</th>
					</tr>
				<tbody>
					
					<?php   
					$i=1;                 
	                    foreach($citemslist->result() as $row):
	                  ?>
	                <tr>
	                	<td><?php echo $i; ?></td>
	                	<td><?php echo $row->citems; ?></td>
						<td><?php echo $row->entry_date; ?></td>
						<td>
							<a href="Main/edit_citems_form/<?php echo $row->id; ?>"><i class="fa fa-edit fa-2x"></i></a>
							<a href="Main/address_delete/<?php echo $row->id; ?>" onclick="return confirm('Are you sure to delete?')"><i class="fa fa-trash-o fa-2x"></i></a>
						</td>
	                </tr>
	                <?php 
	                $i++;
	            	endforeach; ?>
             </table>
        </div>
    </div>
</div>