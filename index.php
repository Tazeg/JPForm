<?php
//-----------------------------------------------------------------------
//  AUTHOR	: Jean-Francois GAZET
//  WEB 	: http://www.jeffprod.com
//  TWITTER	: @JeffProd
//  MAIL	: jeffgazet@gmail.com
//  LICENCE 	: GNU GENERAL PUBLIC LICENSE Version 2, June 1991
//-----------------------------------------------------------------------
 
require 'class.JPForm.php';

date_default_timezone_set('Europe/Paris');

// creating form, adding textfields and rules for each (required|email|numeric|integer|url)
$myform=new JPForm('index.php','POST');

$myform->addFreeText('Your name<br>');
$myform->addText('name','',40,30,'','required'); // name,value,size,maxlength,CSSclass,rules
$myform->addFreeText('<br>');

$myform->addFreeText('Your password (8 chars)<br>');
$myform->addPassword('passwd','',20,8); // name,value,size,maxlength,css,rules
$myform->addFreeText('<br>');

$myform->addFreeText('Your mail<br>');
$myform->addText('email','',30,10,'','email'); // name,value,size,maxlength,CSSclass,rules
$myform->addFreeText('<br>');

$myform->addFreeText('Your message<br>');
$myform->addTextarea('message','',10,20,'required'); // name,value,rows,cols,CSSclass,rules
$myform->addFreeText('<br>');

$myform->addFreeText('Your web site<br>');
$myform->addText('website','',40,'','','url'); // name,value,size,maxlength,CSSclass,rules
$myform->addFreeText('<br>');

$myform->addFreeText('Sex<br>');
$myform->addRadio('sex','required'); // name,rules
$myform->addRadioOption('sex','male','Male',true); // parent,value,label,isSelected (default=false)
$myform->addRadioOption('sex','female','Female','');
$myform->addRadioOption('sex','other','Other','');
$myform->addFreeText('<br>');

$today_day=date('d');
$today_month=date('m');
$today_year=date('Y');

$myform->addFreeText('Your birthday<br>');
$myform->addSelect('day','','required|integer'); // name,CSSclass,rules
for($i=1;$i<=31;$i++) {$myform->addSelectOption('day',$i,$i,($today_day==$i));} // parent,value,label,isSelected (default=false)
$myform->addSelect('month','','required|integer');
for($i=1;$i<=12;$i++) {$myform->addSelectOption('month',$i,$i,($today_month==$i));}
$myform->addSelect('year','','required|integer');
for($i=$today_year;$i>$today_year-100;$i--) {$myform->addSelectOption('year',$i,$i,($today_year==$i));}
$myform->addFreeText('<br>');

$myform->addFreeText('Your favorites sports<br>');
$myform->addCheckbox('sport_natation','natation',true); // name,val,isChecked,css,rules
$myform->addFreeText('Natation<br>');
$myform->addCheckbox('sport_soccer','soccer',false); // name,val,isChecked,css,rules
$myform->addFreeText('Soccer<br>');
$myform->addCheckbox('sport_ski','ski',false); // name,val,isChecked,css,rules
$myform->addFreeText('Ski<br>');

$myform->addHidden('id','45',''); // name,value,rules

$myform->addBtSubmit('Envoyer'); // value,name,CSS (i.e. : class='myclass')

// the form has been submitted
if($myform->isSubmitted()) 
	{
	// testing submitted values with validation rules 
	if($myform->isValid())
		{
		// values are OK : process what you have to do with $_POST or $_GET['things']...
		echo 'Thank you, the form has been sent.<br>';
		print_r($_POST);
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
