<h3 class="center">အေရြးစာရင္းမ်ား</h3>
<table class="table">
			<thead>
				<tr>
					<th>စဥ္</th>
					<th>VR နံပါတ္</th>
					<th>ၿပန္ရေငြေပါင္း</th>
					<th>လေပါင္း</th>
					<th>ႏႈန္း</th>
					<th>တြက္ခ်က္ရေငြ</th>
					<th>အမွန္ရေငြ</th>
					<th>အစြန္းထြက္+/-</th>	
					<th>ေရြးသည္႕ေန႕စြဲ</th>
				</tr>
			</thead>
			<tbody id="content">
				<?php    
					$no=1;                 
                    foreach($printlists->result() as $row):
                  ?>
                <tr>
                	<td><?php echo $no; ?></td>
					<td><?php echo $row->voucher; ?></td>
					<td class="align-right"><?php echo number_format($row->getmoney); ?></td>
					<td><?php echo $row->total_month; ?></td>
					<td><?php echo $row->rate; ?></td>
					<td class="align-right"><?php echo $row->calculate_money; ?></td>
					<td class="align-right"><?php echo $row->realget_money; ?></td>
					<td class="align-right"><?php echo $row->balance_money; ?></td>
					<td><?php echo $row->entry_date; ?></td>
				</tr>
				<?php 
				$no++;
				endforeach; ?>
				<tr style="background:#5CB85C;color:#000;font-weight:bold;">
						<td colspan="2" class="center">စုစုေပါင္း : </td>
						<td class="align-right"><?=number_format($loantotalamt->loantotal)?> </td>
						<td colspan="2"></td>
						<td class="align-right"><?=number_format($loantotalamt->calculateamt)?> </td>
						<td class="align-right"><?=number_format($loantotalamt->netamt)?> </td>
						<td class="align-right"><?=number_format($loantotalamt->balanceamt)?> </td>
					</tr>
			</tbody>
		</table>