<?php
$base = __DIR__;
include $base = __DIR__ . '/common/header.php';
?>

<body>

<div class="container">

<header>

    <div
            class="logo"><img src="../images/logo3.png" alt="Web Page Pros"></div>

    <span class="name">Samuel Urcuyo</span>

    <div
            class="images"><img src="../images/tri-dev.png" height="" alt="html 5">

    </div>

</header>
<nav>
<nav class="navBar">
<?php
$base = __DIR__;
include $base = __DIR__ . '/common/navbar.php';
?>

</nav>

<main>


<aside class="side_nav">
<?php
$base = __DIR__;
include $base = __DIR__ . '/../common/sidebar.php';
?>

   </aside>

    <section>
<div class="container">
		<div class="row">
			<section>
			<form action="contact.php" method="post">
			<p><b>Your Name:</b> <input type="text" name="yourname" /><br />
			<b>Subject:</b> <input type="text" name="subject" /><br />
			<b>E-mail:</b> <input type="text" name="email" /><br />
			Website: <input type="text" name="website"></p>

			<p>Do you like this website?
			<input type="radio" name="likeit" value="Yes" checked="checked" /> Yes
			<input type="radio" name="likeit" value="No" /> No
			<input type="radio" name="likeit" value="Not sure" /> Not sure</p>

			<p>How did you find us?
			<select name="how">
			<option value=""> -- Please select -- </option>
			<option>Google</option>
			<option>Yahoo</option>
			<option>Link from a website</option>
			<option>Word of mouth</option>
			<option>Other</option>
			</select>

			<p><b>Your comments:</b><br />
			<textarea name="comments" rows="10" cols="40"></textarea></p>

			<p><input type="submit" value="Send it!"></p>

			<p> </p>

</form>
			</section>
			

			</div> <!-- close row -->

		</div> <!-- close services container -->

<hr>
<footer class="footer">

		Samuel Urcuyo &COPY; Copyright 2017

	</footer>



</body>

</html>
