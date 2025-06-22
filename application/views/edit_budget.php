<div class="col-md-12 mt">
	<p class="col-md-offset-2" style="color:#fff;">၀င္ေငြ ထြက္ေငြ စာရင္းၿပင္ရန္</p>
	<div class="col-md-4 form-horizontal">
	<?=form_open('Main/budget_edit/')?>
	<input type="hidden" name="id" value="<?php echo $budgetdata->id; ?>">
	<div class="form-group">	
		<div class="col-md-4">
			<label>သေကၤတ</label>
		</div>
		<div class="col-md-8">
			<input type="text" name="sign" class="form-control" placeholder="သေကၤတ" onchange=budgetcategorysearch(this.value,event) value="<?php echo $budgetdata->sign; ?>">	
		</div>		
				
	</div>
	<div class="form-group">	
		<div class="col-md-4">
			<label>အေၾကာင္းအရာ</label>	
		</div>
		<div class="col-md-8">
			<input type="text" name="category" class="form-control" placeholder="အေၾကာင္းအရာ" id="categoryresult" value="<?php echo $budgetdata->category; ?>">		
		</div>
	</div>
	<?php if($budgetdata->income_amt=="0"){ ?>
		<div class="form-group">	
			<div class="col-md-4">
				<label>ထြက္ေငြ</label>	
			</div>
			<div class="col-md-8">
				<input type="text" name="outcome_amt" id="outnumber" class="form-control" placeholder="ထြက္ေငြ" value="<?php echo $budgetdata->outcome_amt; ?>">	
			</div>
		</div>
	<?php } ?>
	<?php if($budgetdata->outcome_amt=="0"){ ?>
		<div class="form-group">	
			<div class="col-md-4">
				<label>၀င္ေငြ</label>	
			</div>
			<div class="col-md-8">
				<input type="text" name="income_amt" id="outnumber" class="form-control" placeholder="၀င္ေငြ" value="<?php echo $budgetdata->income_amt; ?>">	
			</div>
		</div>
	<?php } ?>
	
	
		<div class="right toppadding_sm"><button type="submit" class="btn btn-success">Save</button></div>
	<?=form_close()?>
	</div>	
</div>

