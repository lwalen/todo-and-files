<?php

require_once "common.php";

// User will be redirected to this page after logout
define('LOGOUT_URL', '/');

// logout?
if(isset($_GET['logout'])) {
	setcookie("verify", '', 0, '/'); // clear password
	setcookie("verify_session", '', 0, '/'); // clear password
	header('Location: ' . LOGOUT_URL);
	exit();
}

if(!function_exists('showLoginPasswordProtect')) {

	// show login form
	function showLoginPasswordProtect($error_msg = "") {
		writeHead(DOMAIN);
?>
	<!-- <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
</head>-->
<body>
	<div class='section'>
		<h1><?= DOMAIN ?></h1>
<?php
$people = queryPeople();
foreach ($people as $person) {
	echo "	<a href='{$person->website}'>{$person->name}</a>";
}
?>
	</div>

	<div class='section'>
		<div class='login'>	
			<form method='post' autocomplete='off'>
				<input type="password" name="access_password" /><br />
				<div id='save'>save? <input type='checkbox' name='remember_me' value='Yes' /></div>
				<input type="submit" value='Login' />
			</form>
			<div id='error_message'><?php echo $error_msg; ?></div>
		</div>
	</div>


	<!-- begin files -->
	<div class='section'>
		<div id='files'>
<?php
		$public_files = getPublicFiles();

		if (empty($public_files)) {
?>
			<span class='no_content'>no public files</span>
<?php
		} else {
			printPublicFiles($public_files);
		}
?>
		</div>
	</div>
	<!-- end files -->

</body>
</html>

<?php
		// stop at this point
		die();
	}
}

// user provided password
if (isset($_POST['access_password'])) {

	$pass = $_POST['access_password'];
	$remember = isset( $_POST['remember_me'] );
	$hash = password_hash(PASSWORD, PASSWORD_DEFAULT);

	if (!password_verify($pass, $hash)) {
		showLoginPasswordProtect("Incorrect password.");
	} else {

		// set cookie if password was validated
		if ($remember) {
			setcookie("verify", $hash, time() + 60*60*24*30, '/');
		} else {
			setcookie("verify_session", $hash, 0, '/');
		} 

		unset($_POST['access_password']);
		unset($_POST['remember_me']);
	}

} else {

	// check if password cookie is set
	if (!isset($_COOKIE['verify']) && !isset($_COOKIE['verify_session'])) {
		showLoginPasswordProtect();
	}

	// check if cookie is good
	$hash = password_hash(PASSWORD, PASSWORD_DEFAULT);
	if (isset($_COOKIE['verify']) && password_verify(PASSWORD, $_COOKIE['verify'])) {
		setcookie("verify", $hash, time() + 60*60*24*30, '/');
	} else if (isset($_COOKIE['verify_session']) && password_verify(PASSWORD, $_COOKIE['verify_session'])) {
		setcookie("verify_session", $hash, 0, '/');
	} else {
		showLoginPasswordProtect("Invalid cookie");
	}
}

?>
