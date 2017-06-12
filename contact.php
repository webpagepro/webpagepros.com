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
<?php
/* Set e-mail recipient */
$myemail  = "webpagepro@aol.com";

/* Check all form inputs using check_input function */
$yourname = check_input($_POST['yourname'], "Enter your name");
$subject  = check_input($_POST['subject'], "Write a subject");
$email    = check_input($_POST['email']);
$website  = check_input($_POST['website']);
$likeit   = check_input($_POST['likeit']);
$how_find = check_input($_POST['how']);
$comments = check_input($_POST['comments'], "Write your comments");

/* If e-mail is not valid show error message */
if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email))
{
    show_error("E-mail address not valid");
}

/* If URL is not valid set $website to empty */
if (!preg_match("/^(https?:\/\/+[\w\-]+\.[\w\-]+)/i", $website))
{
    $website = '';
}

/* Let's prepare the message for the e-mail */
$message = "Hello!

Your contact form has been submitted by:

Name: $yourname
E-mail: $email
URL: $website

Like the website? $likeit
How did he/she find it? $how_find

Comments:
$comments

End of message
";

/* Send the message using mail() function */
mail($myemail, $subject, $message);

/* Redirect visitor to the thank you page */
header('Location: thank-you.php');
exit();

/* Functions we used */
function check_input($data, $problem='')
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($problem && strlen($data) == 0)
    {
        show_error($problem);
    }
    return $data;
}

function show_error($myError)
{
?>
    <html>
    <body>

    <b>Please correct the following error:</b><br />
    <?php echo $myError; ?>

    </body>
    </html>
<?php
exit();
}
?>
<footer class="footer">

		Samuel Urcuyo &COPY; Copyright 2017

	</footer>



</body>

</html>
