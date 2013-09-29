<?php //netteCache[01]000366a:2:{s:4:"time";s:21:"0.30758100 1366723170";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:44:"/var/www/sandbox/app/templates/@layout.latte";i:2;i:1366722626;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"c0332ac released on 2013-03-08";}}}?><?php

// source file: /var/www/sandbox/app/templates/@layout.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'tmq4oas6y5')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lbf1c8a40ec7_title')) { function _lbf1c8a40ec7_title($_l, $_args) { extract($_args)
?>Student Project<?php
}}

//
// end of blocks
//

// template extending and snippets support

$_l->extends = empty($template->_extended) && isset($_control) && $_control instanceof Nette\Application\UI\Presenter ? $_control->findLayoutTemplateFile() : NULL; $template->_extended = $_extended = TRUE;


if ($_l->extends) {
	ob_start();

} elseif (!empty($_control->snippetMode)) {
	return Nette\Latte\Macros\UIMacros::renderSnippets($_control, $_l, get_defined_vars());
}

//
// main template
//
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="description" content="" />
<?php if (isset($robots)): ?>	<meta name="robots" content="<?php echo htmlSpecialChars($robots) ?>" />
<?php endif ?>

	<title><?php if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
ob_start(); call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars()); echo $template->upper($template->striptags(ob_get_clean()))  ?></title>

	<link rel="stylesheet" media="screen,projection,tv" href="<?php echo htmlSpecialChars($basePath) ?>/css/screen.css" />
	<link rel="stylesheet" media="print" href="<?php echo htmlSpecialChars($basePath) ?>/css/print.css" />
       <link href="<?php echo htmlSpecialChars($basePath) ?>/css/datepicker.css" rel="stylesheet" type="text/css" /> 

	<link rel="shortcut icon" href="<?php echo htmlSpecialChars($basePath) ?>/favicon.ico" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link rel="stylesheet" media="screen,projection,tv"
    href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" />

	
	
</head>

 <body>
  

     <script>
/* Czech initialisation for the jQuery UI date picker plugin. */
/* Written by Tomas Muller (tomas@tomas-muller.net). */
jQuery(function($) {
        $.datepicker.regional['sk'] = {
                closeText: 'Zavrieť',
                prevText: '&#x3c;Predchádzajúci',
                nextText: 'Nasledujúci&#x3e;',
                currentText: 'Dnes',
                monthNames: ['Január','Február','Marec','Apríl','Máj','Jún',
                'Júl','August','September','Október','November','December'],
                monthNamesShort: ['Jan','Feb','Mar','Apr','Máj','Jún',
                'Júl','Aug','Sep','Okt','Nov','Dec'],
                dayNames: ['Nedel\'a','Pondelok','Utorok','Streda','Štvrtok','Piatok','Sobota'],
                dayNamesShort: ['Ned','Pon','Uto','Str','Štv','Pia','Sob'],
                dayNamesMin: ['Ne','Po','Ut','St','Št','Pia','So'],
                weekHeader: 'Ty',
                dateFormat: 'dd.mm.yy',
                firstDay: 0,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['sk']);

});
</script>

      <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  </script>
<script>
$(document).ready(function () {
    $("input.date").each(function () { // input[type=date] does not work in IE
        var el = $(this);
        var value = el.val();
        var date = (value ? $.datepicker.parseDate($.datepicker.W3C, value) : null);
       

        var minDate = el.attr("min") || null;
        if (minDate) minDate = $.datepicker.parseDate($.datepicker.W3C, minDate);
        var maxDate = el.attr("max") || null;
        if (maxDate) maxDate = $.datepicker.parseDate($.datepicker.W3C, maxDate);

        // input.attr("type", "text") throws exception
        if (el.attr("type") == "date") {
            var tmp = $("<input/>");
            $.each("class,disabled,id,maxlength,name,readonly,required,size,style,tabindex,title,value".split(","), function(i, attr)  {
                tmp.attr(attr, el.attr(attr));
            });
            tmp.data(el.data());
            el.replaceWith(tmp);
            el = tmp;
        }
        el.datepicker({
            minDate: minDate,
            maxDate: maxDate
        });
        el.val($.datepicker.formatDate(el.datepicker("option", "dateFormat"), date));
    });
});
</script>

     
        <div id="header">
            <div class="title"> Študent Projekt</div>

<?php if ($user->isLoggedIn()): ?>
            <div class="user">
                
<?php if (($user->isAllowed('Reg'))): ?>
                <a href="<?php echo htmlSpecialChars($_control->link("Registration:registry")) ?>
">Registrácia užívateľa</a>
                
<?php endif ?>
                <span class="icon user"><?php echo Nette\Templating\Helpers::escapeHtml($user->getIdentity()->login, ENT_NOQUOTES) ?></span> |
               
                <a href="<?php echo htmlSpecialChars($_control->link("Sign:out")) ?>
">Logout</a>
            </div>
<?php endif ;if (!$user->isLoggedIn()): ?>
            <div class="user">
                <a href="<?php echo htmlSpecialChars($_control->link("Sign:in")) ?>
">Prihlásiť sa</a>
            </div>
<?php endif ?>

        </div>

    



	<div id="container">
<?php if ($user->isLoggedIn()): ?>
          
    <div id="sidebar">

        <h2><a href="<?php echo htmlSpecialChars($_control->link("Homepage:")) ?>
">Prehľad</a></h2>

        <div class="task-lists">
<?php if (($user->isAllowed('Default'))): ?>
             <h2><a href="<?php echo htmlSpecialChars($_control->link("Project:projects")) ?>
">Projekty</a></h2>
             <h2><a href="<?php echo htmlSpecialChars($_control->link("Subject:subsum")) ?>
">Predmety</a></h2>   
             <h2><a href="<?php echo htmlSpecialChars($_control->link("UserView:")) ?>
">Užívatelia</a></h2> 
            
            
<?php endif ?>
        </div>

    </div>
         
                
                
<?php if (!$user->isLoggedIn()): ?>
		
                <p>You are not logged in.</p>
<?php endif ?>
   
     
			
<?php endif ?>
        <div id="content">
<?php $iterations = 0; foreach ($flashes as $flash): ?>            <div class="flash <?php echo htmlSpecialChars($flash->type) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($flash->message, ENT_NOQUOTES) ?></div>
<?php $iterations++; endforeach ?>
           
<?php Nette\Latte\Macros\UIMacros::callBlock($_l, 'content', $template->getParameters()) ?>
 
        </div>
           
  
    
    
           


<div id="footer">       
</div>
</div>




</body>
</html>
