<?php
define("NO_LOGIN", true);
require_once "common.php";

writeHead("404");
?>


<body>
	<div class='section'>
		<h1>404</h1>
		<p>File not found.</p>
		<a href="/"><?= DOMAIN ?></a>
	</div>
<body>
</html>
