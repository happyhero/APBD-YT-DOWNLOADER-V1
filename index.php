<?php require_once 'includes/header.php'; ?>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3 text-center">
			<form action="download-details.php" method="post">
				<label for="link">Paste your youtube video link</label>
				<div class="from-group input-group">
					<input type="url" id="link" class="form-control" name="link" placeholder="Youtube video link..">
					<span class="input-group-btn">
						<input type="submit" id="submit_url" name="submit_url" class="btn btn-info input-control" value="Fetch Video">
					</span>
				</div>
			</form>
			<br>
			<?php
				if(isset($_GET['error']) && $_GET['error'] == 'notyoutube') {
					echo "<p class='bg-danger'><strong>Please enter a valid youtube link</strong></p>";
				}
			?>
		</div>
	</div>
</div>

<?php require_once 'includes/footer.php'; ?>