<form class="form-horizontal" >
		<div class="form-group">
			<label for="inputProjectName" class="col-sm-3 control-label">Projektnamn</label>
			<div class="col-sm-9">
				<input type="text" class="form-control" id="inputProjectName" placeholder="Projektnamn" required="">
			</div>
		</div>
		<div class="form-group">
			<label for="ownerInp" class="col-sm-3 control-label">Ägare</label>
			<div class="col-sm-9">
				<input type="text" class="form-control" id="ownerInp" placeholder="Ägare" required="">
			</div>
		</div>
		<div class="form-group">
			<label for="pickyDate" class="col-sm-3 control-label">Startdatum</label>
			<div class="col-sm-9">
				<input type="text"  onchange="formChange(this)"  class="form-control" id="pickyDate" placeholder="Startdatum" required="">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
			</div>
		</div>
		<div class="form-group last">
			<div class="col-sm-offset-3 col-sm-9">
				<input type="button" onclick="createNewProject()" value="Skapa" class="btn btn-success btn-sm">
				<button type="reset" class="btn btn-default btn-sm">Rensa fält</button>
			</div>
		</div>
	</form>