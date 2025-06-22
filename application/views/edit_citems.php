<div class="col-md-12 mt">
	<p class="col-md-offset-2" style="color:#fff;">ပစၥည္းမ်ား ၿပင္ဆင္ရန္</p>
	<div class="col-md-4 form-horizontal">
	<?=form_open('Main/citems_edit/')?>
	<input type="hidden" name="id" value="<?php echo $addresslist->id; ?>">
		<div class="form-group">
				<label class="col-md-4 control-label">ပစၥည္း</label>		
				<div class="col-md-8">		
					<input type="text" name="citems" class="form-control" value="<?php echo $citemslist->citems; ?>">		
				</div>									
		</div>	

		<div class="right"><button type="submit" class="btn btn-success">Save</button></div>
	<?=form_close()?>
	</div>	
</div>