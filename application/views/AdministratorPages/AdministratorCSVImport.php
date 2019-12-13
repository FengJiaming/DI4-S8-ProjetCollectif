<div class="container">
	<h2 class="display-5 text-center mb-4">Import CSV</h2>

	<div class="card">
		<div class="card-header">
			<H4>Import matériels (catalogue)</h4>
		</div>
		<div class="card-body">
			<?php echo form_open_multipart('/Admin/CSVImportAdminController/importHardware'); ?>
			<div class="form-row ">

				<div class="col">
					<input class="form-control" type="file" name="csvfile" accept=".csv" required="required"/>
				</div>

				<div class="col">
					<input type="submit" class="btn btn-primary " value="Importer"/>
				</div>
			</div>
			</form>
		</div>
	</div>

<div class="mt-5">

</div>

	<div class="card">
		<div class="card-header">
			<H4>Import consommables (hors catalogue)</h4>
		</div>
		<div class="card-body">
			<?php echo form_open_multipart('/Admin/CSVImportAdminController/importConsumable'); ?>

			<div class="form-row ">

				<div class="col">
					<input class="form-control" type="file" name="csvfile" accept=".csv" required="required"/>
				</div>

				<div class="col">
					<input type="submit" class="btn btn-primary " value="Importer"/>
				</div>
			</div>

			</form>
		</div>
	</div>


</div>
