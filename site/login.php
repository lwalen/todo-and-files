<?php
require_once "common.php";
require_once "/home/web/include/users.inc";

################  SETTINGS BEGIN  ################

// User will be redirected to this page after logout
define('LOGOUT_URL', 'http://www.walen.me/');

################  SETTINGS END  ################

// logout?
if(isset($_GET['logout'])) {
	setcookie("verify", '', 0, '/'); // clear password;
	setcookie("verify_session", '', 0, '/'); // clear password;
	header('Location: ' . LOGOUT_URL);
	exit();
}

if(!function_exists('showLoginPasswordProtect')) {

	// show login form
	function showLoginPasswordProtect($error_msg) {
		writeHead();
?>
	<title>walen.me</title>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<link href="/css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class='section'>
		<h1>walen.me</h1>
		<a href='http://lars.walen.me'>Lars Walen</a>
		<span style='margin: 0px 10px 0px 10px'> | </span>
		<a href='http://dailies.walen.me'>Dailies</a>
	</div>

	<div class='section'>
		<div id='login'>	
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
		$public_files = getFiles('../../web/site/public/');

		if( empty( $public_files ) )
		{
			echo "			<span id='no_files'>no public files</span>\n";
		} else {
			foreach( $public_files as $file )
			{
				echo "			<a href='/public/$file'>$file</a>\n";
			}
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

	if( md5($pass.$SALT) != md5($PASSWORD.$SALT)) {

		showLoginPasswordProtect("Incorrect password.");

	} else {

		// set cookie if password was validated
		if( $remember ) {
			setcookie("verify", md5($pass.$SALT), time() + 60*60*24*30, '/');
		} else {
			setcookie("verify_session", md5($pass.$SALT), 0, '/');
		} 

		unset($_POST['access_password']);
		unset($_POST['remember_me']);
	}

} else {

	// check if password cookie is set
	if( !isset( $_COOKIE['verify']) && !isset($_COOKIE['verify_session'] ) ) {
		showLoginPasswordProtect("");
	}

	// check if cookie is good
	$found = false;

	$lp = md5($PASSWORD.$SALT);
	if( isset($_COOKIE['verify']) && $_COOKIE['verify'] == $lp ) {
		$found = true;
		setcookie("verify", $lp, time()+60*60*24*30, '/');
	} else if( isset($_COOKIE['verify_session']) && $_COOKIE['verify_session'] == $lp ) {
		$found = true;
		setcookie("verify_session", $lp, 0, '/');
	}

	if (!$found) {
		showLoginPasswordProtect("");
	}

}

?>
