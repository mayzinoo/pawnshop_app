<h3><i class="fa fa-angle-right"></i> To-Do Lists</h3>
          	
          	<!-- SIMPLE TO DO LIST -->
          	<div class="row mt">
          		<div class="col-md-12">
          			<div class="white-panel pn">	                	
				  		<div class="custom-check goleft mt">
				             <table class="table">
								<thead>
									<tr>
										<th>RecordID</th>
										<th>VR နံပါတ္</th>
										<th>ၿပန္ရေငြေပါင္း</th>
										<th>လေပါင္း</th>
										<th>ႏႈန္း</th>
										<th>တြက္ခ်က္ရေငြ</th>
										<th>အမွန္ရေငြ</th>
										<th>အစြန္းထြက္+/-</th>										
									</tr>
								</thead>
								<tbody>
									<?php                    
					                    foreach($dailyremainlist->result() as $row):
					                  ?>
					                <tr>
										<td><?php echo $row->id; ?></td>
										<td><?php echo $row->vr_type; ?><?php echo $row->vr_no; ?></td>
										<td><?php echo $row->customer_name; ?></td>
										<td><?php echo $row->address; ?></td>
										<td>1000</td>
										<td>1</td>
										<td><input type="text" value="0" id="balance" class="form-control" onkeyup="ChangeAmount(this.value)"></td>
										<td><input type="text" readonly="" id="qty" value="0" class="form-control" /></td>
									</tr>
									<?php endforeach; ?>  
								</tbody>
							</table>
						</div><!-- /table-responsive -->
					</div><!--/ White-panel -->
          		</div><! --/col-md-12 -->
          	</div><! -- row -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<input type="text" name="value1" id="textone">
<input type="text" name="value2" id="texttwo">
<input type="text" name="result" id="result">
<script>
   $('#texttwo').keyup(function(){
       var textone;
       var texttwo;
       textone = parseFloat($('#textone').val());
       texttwo = parseFloat($('#texttwo').val());
       var result = textone + texttwo;
       $('#result').val(result.toFixed(2));
   });
</script>