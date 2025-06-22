<h3 class="center">၀င္ေငြ / ထြက္ေငြ စာရင္းမ်ား</h3>
<table class="table">
			<thead>
				<tr>
					<th>စဥ္</th>
					<th>ေန႕စြဲ</th>
					<!-- <th>သေကၤတ</th> -->
					<th>အေၾကာင္းအရာ</th>
					<th>၀င္ေငြ</th>
					<th>ထြက္ေငြ</th>
				</tr>
			</thead>
			<tbody id="content">
				<?php
					$no=1;
                    foreach($printlists->result() as $row):
                  ?>
                <tr>
                	<td><?php echo $no; ?></td>
                	<td><?php echo $row->entry_date; ?></td>
					<!-- <td><?php echo $row->sign; ?></td> -->
					<td><?php echo $row->category; ?></td>
					<?php
						if($row->income_amt=="0"){ ?>
							<td></td>
						<?php }
						else{ ?>
							<td class="align-right"><?php echo number_format($row->income_amt); ?></td>
						<?php } ?>


					<?php
						if($row->outcome_amt=="0"){ ?>
							<td></td>
						<?php }
						else{ ?>
							<td class="align-right"><?php echo number_format($row->outcome_amt); ?></td>
						<?php } ?>
				</tr>
				<?php
				$no++;
				endforeach; ?>
				<tr style="background:#5CB85C;color:#000;font-weight:bold;">
			            		<td colspan="4"></td>
			            		<td class="align-right"><?php echo number_format($total_amt->incomeamt); ?></td>
			            		<td class="align-right"><?php echo number_format($total_amt->outcomeamt); ?></td>

			            	</tr>
			</tbody>
		</table>
