<?php
//-----------------------------------------------------------------------
//	AUTOR	: Jean-Francois GAZET
//	WEB 	: http://www.jeffprod.com
//  TWITTER	: @JeffProd
//  MAIL	: jeffgazet@gmail.com
//  LICENCE : GNU GENERAL PUBLIC LICENSE Version 2, June 1991
//-----------------------------------------------------------------------

require 'class.JPForm.php';

// creating form, adding textfields and rules for each (required|email|numeric|integer)
$myform=new JPForm('index.php','POST');

$myform->addFreeText('Your name<br>');
$myform->addText('name','',40,'required');

$myform->addFreeText('Your mail<br>');
$myform->addText('email','','','required|email');

$myform->addFreeText('Your message<br>');
$myform->addTextarea('message','',10,20,'required');

$myform->addFreeText('Your web site<br>');
$myform->addText('website','',40,'url');

$myform->addFreeText('Your birthday<br>');
$myform->addSelect('day','required|integer');
for($i=1;$i<=31;$i++) 
	{
	$isSelected=false;
	if(isset($_POST['day'])) {$isSelected=($_POST['day']==$i);}
	$myform->addSelectOption('day',$i,$i,$isSelected);
	}
$myform->addSelect('month','required|integer');
for($i=1;$i<=12;$i++) 
	{
	$isSelected=false;
	if(isset($_POST['month'])) {$isSelected=($_POST['month']==$i);}		
	$myform->addSelectOption('month',$i,$i,$isSelected);
	}
date_default_timezone_set('Europe/Paris');
$myform->addSelect('year','required|integer');
for($i=date("Y");$i>date("Y")-100;$i--)
	{
	$isSelected=false;
	if(isset($_POST['year'])) {$isSelected=($_POST['year']==$i);}		
	$myform->addSelectOption('year',$i,$i,$isSelected);
	}
$myform->addFreeText('<br>');

$myform->addBtSubmit('Envoyer');

// the form has been submitted
if($myform->isSubmitted()) 
	{
	// testing submitted values with validation rules 
	if($myform->isValid())
		{
		// values are OK : process what you have to do with $_POST['things']...
		echo 'Thank you, the form has been sent.<br>';
		echo print_r($_POST);
		}
	else
		{
		// values are not ok : display errors and fill form with data given
		echo $myform->getErrors();
		}
	}

// display form, empty or with values refilled if form submitted
echo $myform->render();
?>
