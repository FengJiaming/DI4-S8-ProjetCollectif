<?php
if (isset($success)) {
	if ($success != null && $success !== "") {
		echo '<div class="alert alert-success" role="alert">'.$success.'</div>';
	}
};
if (isset($error)) {
	if ($error != null && $error !== "") {
		echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
	}
};
?>
