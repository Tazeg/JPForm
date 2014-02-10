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
$myform->addText('Your name','name','',40,'required');
$myform->addText('Your mail','email','','','required|email');
$myform->addTextarea('Your message','message','',10,20,'required');
$myform->addText('Give me a number !','number','',10,'integer');
$myform->addBtSubmit('Envoyer');

// the form has been submitted
if($myform->isSubmitted()) 
	{
	// testing submitted values with validation rules 
	if($myform->isValid())
		{
		// values are OK : process what you have to do with $_POST['things']...
		echo 'Thank you, the form has been sent.<br><br>';
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