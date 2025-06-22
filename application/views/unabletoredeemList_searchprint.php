
<h3 class="center">အေပါင္ဆံုး စာရင္းမ်ား</h3>
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
					<!-- <td><?php echo $row->sr_no; ?></td> -->
					<td><?php echo $row->voucher; ?></td>	
					<td><?php echo $row->customer_name; ?></td>
					<td><?php echo $row->address; ?></td>
					<td class="align-right"><?php echo number_format($row->loan_amt); ?></td>
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
					<td class="align-right"><?php echo $row->kyat; ?></td>
					<td class="align-right"><?php echo $row->pe; ?></td>
					<td class="align-right"><?php echo $row->ywe; ?></td>
				</tr>
				<?php 
				$no++;
				endforeach; ?>
				<tr style="background:#5CB85C;color:#000;font-weight:bold;">
					<td colspan="5" class="center">ေခ်းေငြ စုစုေပါင္း : </td>
					<td class="align-right"><?=number_format($loantotalamt->loantotal)?> </td>
					<td colspan="4"></td>
				</tr>
			</tbody>
		</table>