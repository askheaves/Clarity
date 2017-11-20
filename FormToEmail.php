<?php

error_reporting(E_ALL ^ E_NOTICE);

/*

Thank you for choosing FormToEmail by FormToEmail.com

Version 2.5 April 16th 2009

COPYRIGHT FormToEmail.com 2003 - 2009

You are not permitted to sell this script, but you can use it, copy it or distribute it, providing that you do not delete this copyright notice, and you do not remove any reference or links to FormToEmail.com

For support, please visit: http://formtoemail.com/support/

You can get the Pro version of this script here: http://formtoemail.com/formtoemail_pro_version.php
---------------------------------------------------------------------------------------------------

FormToEmail-Pro (Pro version) Features:

Check for required fields
Attach file uploads
Upload files to the server
Securimage CAPTCHA support
reCAPTCHA support
textCAPTCHA support
identiPIC photo CAPTCHA
HTML output option
Use email templates
Show date and time submitted
Create Message ID
CSV output to attachment or file
Autoresponder (with file attachment)
Show sender's IP address
Block IP addresses
Block web addresses or rude words
Block gibberish (MldMtrPAgZq etc)
Block gobbledegook characters (Å ð ç etc)
Pre-populate the form
Show errors on the form page
Check for a set cookie
Set encoding (utf-8 etc)
Ignore fields
Sort fields
Auto redirect to "Thank You" page
HTML template for "Thank You" page
No branding
Free upgrades for life

---------------------------------------------------------------------------------------------------

Confused by PHP and PERL scripts?  Don't have PHP on your server?  Can't send email from your server?

Try our remotely hosted form service:

http://FormToEmailRemote.com

---------------------------------------------------------------------------------------------------

FormToEmail DESCRIPTION

FormToEmail is a contact-form processing script written in PHP. It allows you to place a form on your website which your visitors can fill out and send to you.  The contents of the form are sent to the email address (or addresses) which you specify below.  The form allows your visitors to enter their name, email address and comments.  The script will not allow a blank form to be sent.

Your visitors (and nasty spambots!) cannot see your email address.  The script cannot be hijacked by spammers.

When the form is sent, your visitor will get a confirmation of this on the screen, and will be given a link to continue to your homepage, or other page if you specify it.

Should you need the facility, you can add additional fields to your form, which this script will also process without making any additional changes to the script.  You can also use it to process other forms.  The script will handle the "POST" or "GET" methods.  It will also handle multiple select inputs and multiple check box inputs.  If using these, you must name the field as an array using square brackets, like so: <select name="fruit[]" multiple>.  The same goes for check boxes if you are using more than one with the same name, like so: <input type="checkbox" name="fruit[]" value="apple">Apple<input type="checkbox" name="fruit[]" value="orange">Orange<input type="checkbox" name="fruit[]" value="banana">Banana

** PLEASE NOTE **  If you are using the script to process your own forms (or older FormToEmail forms) you must ensure that the email field is named correctly in your form, like this for example: <input type="text" name="email">.  Note the lower case "email".  If you don't do this, the visitor's email address will not be available to the script and the script won't be able to check the validity of the email, amongst other things.  If you are using the form code below, you don't need to check for this.

This is a PHP script.  In order for it to run, you must have PHP (version 4.1.0 or later) on your webhosting account, and have the PHP mail() function enabled and working.  If you are not sure about this, please ask your webhost about it.

SETUP INSTRUCTIONS

Step 1: Put the form on your webpage
Step 2: Enter your email address and (optional) continue link below
Step 3: Upload the files to your webspace

Step 1:

To put the form on your webpage, copy the code below as it is, and paste it into your webpage:

<form action="FormToEmail.php" method="post">
<table border="0" style="background:#ececec" cellspacing="5">
<tr align="left"><td>Name</td><td><input type="text" size="30" name="name"></td></tr>
<tr align="left"><td>Email address</td><td><input type="text" size="30" name="email"></td></tr>
<tr align="left"><td valign="top">Comments</td><td><textarea name="comments" rows="6" cols="30"></textarea></td></tr>
<tr align="left"><td>&nbsp;</td><td><input type="submit" value="Send"><font face="arial" size="1">&nbsp;&nbsp;Created with <a href="http://FormToEmail.com">FormToEmail.com</a></font></td></tr>
</table>
</form>

Step 2:

Enter your email address.

Enter the email address below to send the contents of the form to.  You can enter more than one email address separated by commas, like so: $my_email = "info@example.com"; or $my_email = "bob@example.com,sales@example.co.uk,jane@example.com";

*/

$my_email = "catherine@clarityacuherbs.com";

/*

Optional.  Enter a From: email address.  Only do this if you know you need to.  By default, the email you get from the script will show the visitor's email address as the From: address.  In most cases this is desirable.  On the majority of setups this won't be a problem but a minority of hosts insist that the From: address must be from a domain on the server.  For example, if you have the domain example.com hosted on your server, then the From: email address must be something@example.com (See your host for confirmation).  This means that your visitor's email address will not show as the From: address, and if you hit "Reply" to the email from the script, you will not be replying to your visitor.  You can get around this by hard-coding a From: address into the script using the configuration option below.  Enabling this option means that the visitor's email address goes into a Reply-To: header, which means you can hit "Reply" to respond to the visitor in the conventional way.  (You can also use this option if your form does not collect an email address from the visitor, such as a survey, for example, and a From: address is required by your email server.)  The default value is: $from_email = "";  Enter the desired email address between the quotes, like this example: $from_email = "contact@example.com";  In these cases, it is not uncommon for the From: ($from_email) address to be the same as the To: ($my_email) address, which on the face of it appears somewhat goofy, but that's what some hosts require.

*/

$from_email = "";

/*

Optional.  Enter the continue link to offer the user after the form is sent.  If you do not change this, your visitor will be given a continue link to your homepage.

If you do change it, remove the "/" symbol below and replace with the name of the page to link to, eg: "mypage.htm" or "http://www.elsewhere.com/page.htm"

*/

$continue = "http://clarity.searium.com/index.html";

/*

Step 3:

Save this file (FormToEmail.php) and upload it together with your webpage containing the form to your webspace.  IMPORTANT - The file name is case sensitive!  You must save it exactly as it is named above!

THAT'S IT, FINISHED!

You do not need to make any changes below this line.

*/

$errors = array();

// Remove $_COOKIE elements from $_REQUEST.

if(count($_COOKIE)){foreach(array_keys($_COOKIE) as $value){unset($_REQUEST[$value]);}}

// Validate email field.

if(isset($_REQUEST['email']) && !empty($_REQUEST['email']))
{

$_REQUEST['email'] = trim($_REQUEST['email']);

if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ") || stristr($_REQUEST['email'],"\\") || stristr($_REQUEST['email'],":")){$errors[] = "Email address is invalid";}else{$exploded_email = explode("@",$_REQUEST['email']);if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){$errors[] = "Email address is invalid";}else{if(substr_count($exploded_email[1],".") == 0){$errors[] = "Email address is invalid";}else{$exploded_domain = explode(".",$exploded_email[1]);if(in_array("",$exploded_domain)){$errors[] = "Email address is invalid";}else{foreach($exploded_domain as $value){if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){$errors[] = "Email address is invalid"; break;}}}}}}

}

// Check referrer is from same site.

if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}

// Check for a blank form.

function recursive_array_check_blank($element_value)
{

global $set;

if(!is_array($element_value)){if(!empty($element_value)){$set = 1;}}
else
{

foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}

}

}

recursive_array_check_blank($_REQUEST);

if(!$set){$errors[] = "You cannot send a blank form";}

unset($set);

// Display any errors and exit if errors exist.

if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}

if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}

// Build message.

function build_message($request_input){if(!isset($message_output)){$message_output ="";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!empty($value)){if(!is_numeric($key)){$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;}else{$message_output .= build_message($value).", ";}}}}return rtrim($message_output,", ");}

$message = build_message($_REQUEST);

$message = $message . PHP_EOL.PHP_EOL."-- ".PHP_EOL."Thank you for using FormToEmail from http://FormToEmail.com";

$message = stripslashes($message);

$subject = "FormToEmail Comments";

$subject = stripslashes($subject);

if($from_email)
{

$headers = "From: " . $from_email;
$headers .= PHP_EOL;
$headers .= "Reply-To: " . $_REQUEST['email'];

}
else
{

$from_name = "";

if(isset($_REQUEST['name']) && !empty($_REQUEST['name'])){$from_name = stripslashes($_REQUEST['name']);}

$headers = "From: {$from_name} <{$_REQUEST['email']}>";

}

mail($my_email,$subject,$message,$headers);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Clarity Acupuncture</title>
<link rel="stylesheet" type="text/css" href="clarity.css" />
<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Ubuntu:regular,bold' />
<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Vollkorn:regular,italic,bold' />
<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if lt IE 9]><script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script><![endif]-->
</head>

<body>
<div id="main-container">
  <div id="header"> <span class="rndimgcnr" style="background-image:url(images/header4.jpg)" > <a href="index.html"><img src="images/header4.jpg" alt="Clarity Acupuncture and Herbs"></a></span> </div>
  <div id="navigation">
    <div class="rollover" id="home-over"> <a href="index.html">Home</a></div>
    <div class="rollover" id="about"> <a href="about.html">About</a></div>
    <div class="rollover" id="services"> <a href="services.html">Services</a></div>
    <div class="rollover" id="herbs"> <a href="herbs.html">Herbs</a></div>
    <div class="rollover" id="faq"> <a href="faq.html">FAQ</a></div>
    <div class="rollover" id="contact"> <a href="contact.html">Contact</a></div>
    <div class="rollover" id="blog"> <a href="blog.html">Blog</a></div>
  </div>
  
    <div id="container">
    <div id="side-bar-container">
      <div id="left-bar-item"> 
        <SCRIPT TYPE = "text/javascript">
			var randpic = new Array ("images/rnd/ht-1.jpg", "images/rnd/ht-2.jpg", "images/rnd/ht-3.jpg", "images/rnd/ht-4.jpg", "images/rnd/ht-5.jpg", "images/rnd/ht-6.jpg");
			var randnum = Math.floor(Math.random() * 6);
			document.write("<img src='" + randpic[randnum] + "'>")
		</SCRIPT> 
      </div>
      <div id="left-bar-item">
          <a href="schedule.html" style="border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;box-shadow:0 1px 0 rgba(255,255,255,0.2) inset,0 2px 3px rgba(0,0,0,.6);-moz-box-shadow:0 1px 0 rgba(255,255,255,0.2) inset,0 2px 3px rgba(0,0,0,.6);-webkit-box-shadow:0 1px 0 rgba(255,255,255,0.2) inset,0 2px 3px rgba(0,0,0,.6);border:none !important;text-decoration:none !important;background:#00c6f2 repeat-x;color:white;text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);font-style:normal;font-size:15px;line-height:25px;display:inline-block;padding:0 25px;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#00d0ff', endColorstr='#0092b3', GradientType=0);background-image:linear-gradient(top,#00d0ff,#0092b3);background-image:-moz-linear-gradient(top,#00d0ff,#0092b3);background-image:-webkit-linear-gradient(top,#00d0ff,#0092b3);">Schedule Appointment</a>
      </div>
      <div id="left-bar-item"> 
        <SCRIPT TYPE = "text/javascript">
			var randpic = new Array ("images/rnd1/r-img-1.jpg", "images/rnd1/r-img-2.jpg", "images/rnd1/r-img-3.jpg", "images/rnd1/r-img-4.jpg");
			var randnum = Math.floor(Math.random() * 4);
			document.write("<img src='" + randpic[randnum] + "'>")
		</SCRIPT> 
      </div>
    </div>
    <div id="content">
      <div align="center">
        <p> <b>Thank you,
          <?php if(isset($_REQUEST['name'])){print stripslashes($_REQUEST['name']);} ?>
          </b> <br>
          Your message has been sent! </p>
        <p><a href="<?php print $continue; ?>">Click here to continue</a></p>
        <!-- <p><b>FormToEmail</b> by <a href="http://FormToEmail.com">FormToEmail.com</a></p> --> 
      </div>
    </div>
  </div>
  <div id="footer">
    <p class="left">© Copyright 2016 - Clarity Acupuncture</p>
    <p class="right">site by ShilkyDesigns</p>
  </div>
</div>
</body>
</html>