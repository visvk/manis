<?php //netteCache[01]000373a:2:{s:4:"time";s:21:"0.92225900 1366723252";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:51:"/var/www/sandbox/app/templates/Subject/subsum.latte";i:2;i:1366722627;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"c0332ac released on 2013-03-08";}}}?><?php

// source file: /var/www/sandbox/app/templates/Subject/subsum.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'mwrtf992o3')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lb1d5176417a_content')) { function _lb1d5176417a_content($_l, $_args) { extract($_args)
;call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars())  ?>

<?php Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = (is_object("subjRegForm") ? "subjRegForm" : $_control["subjRegForm"]), array()) ?>
<div class="registration-form">
<?php if (is_object($form)) $_ctrl = $form; else $_ctrl = $_control->getComponent($form); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render('errors') ?>

    <div class="pair">
        <table class="tasks">
            <thead>
                <tr>
                    <th class="one"><?php $_input = is_object("subject") ? "subject" : $_form["subject"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th> 
                    <th class="two"><?php $_input = is_object("year") ? "year" : $_form["year"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                    <th class="three"></th>                    
                </tr>
            </thead>
            <tbody>
                <tr>  
                    <td class="one"><?php $_input = (is_object("subject") ? "subject" : $_form["subject"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                    <td class="two"><?php $_input = (is_object("year") ? "year" : $_form["year"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                    <td class="three"> <?php $_input = (is_object("set") ? "set" : $_form["set"]); echo $_input->getControl()->addAttributes(array()) ?></td>                   
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ?>


<?php $_ctrl = $_control->getComponent("subjectGrid"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = (is_object("yearRegForm") ? "yearRegForm" : $_control["yearRegForm"]), array()) ?>
<div class="registration-form">
<?php if (is_object($form)) $_ctrl = $form; else $_ctrl = $_control->getComponent($form); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render('errors') ?>

    <div class="pair">
        <table class="tasks">
            <thead>
            <tr>
                <th class="one"><?php $_input = is_object("year1") ? "year1" : $_form["year1"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th> 
                <th class="two"><?php $_input = is_object("year2") ? "year2" : $_form["year2"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                <th class="three"><?php $_input = is_object("term") ? "term" : $_form["term"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                <th class="four"><?php $_input = is_object("start") ? "start" : $_form["start"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                <th class="five"><?php $_input = is_object("end") ? "end" : $_form["end"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                <th class="five"></th>
                
              
            </tr>
        </thead>
        <tbody>
            <tr>  
                <td class="one"><?php $_input = (is_object("year1") ? "year1" : $_form["year1"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                <td class="two">/  <?php $_input = (is_object("year2") ? "year2" : $_form["year2"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                <td class="three"><?php $_input = (is_object("term") ? "term" : $_form["term"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                <td class="four"><?php $_input = (is_object("start") ? "start" : $_form["start"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                <td class="five"><?php $_input = (is_object("end") ? "end" : $_form["end"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                <td class="five"><?php $_input = (is_object("set") ? "set" : $_form["set"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                <td class="five"></td>
                
            </tr>
        </tbody>
        </table>
    </div>
</div>
 <script>
  $(document).ready(function () {
      $("input.date")
          .datepicker("option", "changeMonth", true)
          .datepicker("option", "changeYear", true);
  });
        </script>
<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ;$_ctrl = $_control->getComponent("yearsGrid"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
}}

//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lb754be907a1_title')) { function _lb754be907a1_title($_l, $_args) { extract($_args)
?><h1>Predmety</h1>
<?php
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
if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); }
call_user_func(reset($_l->blocks['content']), $_l, get_defined_vars()) ; 