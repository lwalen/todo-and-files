<!DOCTYPE html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<title>Lars Walen</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<script type="text/javascript" src="js/myJavascript.js"></script>
	</head>

	<body>
		<!-- Begin logo -->
		<div id="logo">
			<a href="http://lars.walen.me/" style="width:323px;" title="to lars.walen.me"><img style="border:0px" src="img/logo.png" alt="Lars.Walen" /></a>
		</div>
		<!-- End logo -->


		<!-- Start left body -->
		<div class="left_body">

			<p>Hello, my name is Lars Walen and this is my website. I am a senior in Computer Science at <a href="http://www.mines.edu">Colorado School of Mines</a>. Contact me at <a href="mailto:lars@walen.me">lars@walen.me</a>. Some of my coursework is on <a href="http://www.github.com/lwalen">github</a>.</p>
			<br />

			<h1>My interests:</h1>
			<ul>
				<li>Typography</li>
				<li><a href="woodworking/">Woodworking</a></li>
				<li>Fantasy novels</li>
			</ul>

			<p>
				<br />
				<a href="files/PowerPoint_for_Engineers.pdf">PowerPoint for Engineers</a> [pdf]
			</p>
				<p class="inset">I made this slideshow after having to sit through a poorly made presentation on a topic I didn't care about to begin with. The fact that the slides were poorly made did nothing to increase my interest. I don't think it is a stretch to say that engineers are usually more passionate about the thing they are presenting than how well they are presenting it.</p>
			<br />
		</div>
		<!-- End left body -->


		<!-- Begin picture -->
		<div id="picture">
			<img src="img/lars.jpg" alt="A picture of me in a suit" title="A picture of me in a suit" />
		</div>
		<!-- End picture -->


		<!-- Start bottom -->
		<div class="bot">
			<p id="validate"><a href="http://validator.w3.org/check?uri=http%3A%2F%2Flars.walen.me">Validate me!</a></p>
			<p>Last Updated: <?= date("F d Y", filemtime("index.php")) ?></p>
		</div>
		<!-- End bottom -->

	</body>
</html>
