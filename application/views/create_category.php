<div class="col-md-12 mt">
	<p class="col-md-offset-2" style="color:#fff;">အေၾကာင္းအရာ</p>
	<div class="col-md-4 form-horizontal">
	<?=form_open('Main/category_insert/')?>
  		<div class="form-group">
  				<label class="col-md-4 control-label">သေကၤတ</label>
  				<div class="col-md-8">
  					<input type="text" name="sign" class="form-control">
  				</div>
  		</div>

      <div class="form-group">
  				<label class="col-md-4 control-label">အေၾကာင္းအရာ</label>
  				<div class="col-md-8">
  					<input type="text" name="category" class="form-control">
  				</div>
  		</div>

		  <div class="right"><button type="submit" class="btn btn-success">Save</button></div>
	<?=form_close()?>
	</div>
</div>
