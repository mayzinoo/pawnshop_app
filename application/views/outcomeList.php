<div class="padding_sm">
	<div class="row searchform">
		<?=form_open('Main/inoutbudget_search/')?>
			<div class="col-md-offset-3 col-md-2">
				<label>မွ</label>
				<input type="date" name="startdate" class="form-control" />
			</div>
			<div class="col-md-2">
				<label>သို႕</label>
				<input type="date" name="enddate" class="form-control"/>
			</div>
			<div class="col-md-1" style="padding-top:25px;">
				<button type="submit" value="submit" name="submit" class="btn btn-primary">Search</button>
			</div>
		<?=form_close()?>
			<div class="col-md-offset-2 col-md-1" style="padding-top:25px;">
				<?=form_open('Main/searchinoutbudget_print/')?>
		        	<button type="submit" value="submit" name="submit" class="btn btn-success"><i class="fa fa-print"></i></button>
				<?=form_close()?>
			</div>
			<!-- <div class="col-md-1" style="padding-top:25px;">
			<?=form_open('Main/multiple_budget_delete/')?>
    			<button type="submit" class="btn btn-success"><i class="fa fa-trash-o"> </i></button>
			</div> -->
	</div>

	<div class="col-md-12">

				<div class="white-panel pn">
		  		<div class="custom-check goleft mt">
		             <table class="table">
		             	<thead>
							<tr>
								<th>စဥ္</th>
								<th>ေန႕စြဲ</th>
								<th>သေကၤတ</th>
								<th>အေၾကာင္းအရာ</th>
								<th>၀င္ေငြ</th>
								<th>ထြက္ေငြ</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=1;
			                    foreach($income_outcomelist->result() as $row):
			                  ?>
			                <tr>
			                	<td><?php echo $i; ?></td>
			                	<td><?php echo $row->entry_date; ?></td>
								<td><?php echo $row->sign; ?></td>
								<td><?php echo $row->category; ?></td>
								<?php
									if($row->income_amt=="0"){ ?>
										<td></td>
									<?php }
									else{ ?>
										<td><?php echo number_format($row->income_amt); ?></td>
									<?php } ?>


								<?php
									if($row->outcome_amt=="0"){ ?>
										<td></td>
									<?php }
									else{ ?>
										<td><?php echo number_format($row->outcome_amt); ?></td>
									<?php } ?>
									<td>
										<a href="Main/budgetedit_form/<?php echo $row->id; ?>"><i class="fa fa-edit fa-2x"></i></a>
										<a href="Main/budget_delete/<?php echo $row->id; ?>" class="" onclick="return confirm('Are you sure to delete?')"><i class="fa fa-trash-o fa-2x"></i></a>
									</td>
									<!-- <td style="width:5px;"><input type="checkbox" name="budgetdelete[]" value="<?=$row->id?>"></td> -->
			                </tr>
			                <?php
			                $i++;
			            	endforeach; ?>
			            </tbody>
			            <tfoot>
			            	<tr style="background:#5CB85C;color:#000;font-weight:bold;">
			            		<td colspan="4"></td>
			            		<td><?php echo number_format($total_amt->incomeamt); ?></td>
			            		<td><?php echo number_format($total_amt->outcomeamt); ?></td>
			            		<td></td>
			            	</tr>
			            </tfoot>
		             </table>
		        </div>
		    	</div>



	</div>
	<?=form_close()?>
</div>
