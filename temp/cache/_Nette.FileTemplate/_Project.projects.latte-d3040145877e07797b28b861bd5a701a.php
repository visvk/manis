<?php //netteCache[01]000375a:2:{s:4:"time";s:21:"0.69425600 1366723361";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:53:"/var/www/sandbox/app/templates/Project/projects.latte";i:2;i:1366722627;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"c0332ac released on 2013-03-08";}}}?><?php

// source file: /var/www/sandbox/app/templates/Project/projects.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, 'ifkp4auabw')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block content
//
if (!function_exists($_l->blocks['content'][] = '_lba6e6136ddd_content')) { function _lba6e6136ddd_content($_l, $_args) { extract($_args)
;call_user_func(reset($_l->blocks['title']), $_l, get_defined_vars())  ?>

<?php Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = (is_object("projRegForm") ? "projRegForm" : $_control["projRegForm"]), array()) ?>
<div class="registration-form">
<?php if (is_object($form)) $_ctrl = $form; else $_ctrl = $_control->getComponent($form); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render('errors') ?>

    <div class="pair">
        <table class="tasks">
            <thead>
                <tr>
                    <th class="one"><?php $_input = is_object("text") ? "text" : $_form["text"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th> 
                     <th class="six"><?php $_input = is_object("acronym") ? "acronym" : $_form["acronym"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th> 
                    <th class="two"><?php $_input = is_object("solver") ? "solver" : $_form["solver"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                    <th class="three"><?php $_input = is_object("subject") ? "subject" : $_form["subject"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                    <th class="four"><?php $_input = is_object("birthDate") ? "birthDate" : $_form["birthDate"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                    <th class="five"><?php $_input = is_object("endDate") ? "endDate" : $_form["endDate"]; if ($_label = $_input->getLabel()) echo $_label->addAttributes(array()) ?></th>
                    <th class="six"></th>
                </tr>
            </thead>
            <tbody>
                <tr>  
                    <td class="one"><?php $_input = (is_object("text") ? "text" : $_form["text"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                    <td class="six"><?php $_input = (is_object("acronym") ? "acronym" : $_form["acronym"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                    <td class="two"><?php $_input = (is_object("solver") ? "solver" : $_form["solver"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                    <td class="three"> <?php $_input = (is_object("subject") ? "subject" : $_form["subject"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                  <td class="four"> <?php $_input = (is_object("birthDate") ? "birthDate" : $_form["birthDate"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                    <td class="five"><?php $_input = (is_object("endDate") ? "endDate" : $_form["endDate"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                    <td class="six"><?php $_input = (is_object("set") ? "set" : $_form["set"]); echo $_input->getControl()->addAttributes(array()) ?></td>
                </tr>
            </tbody>
        </table>

        <script>
  $(document).ready(function () {
      $("input.date")
          .datepicker("option", "changeMonth", true)
          .datepicker("option", "changeYear", true);
  });
        </script>
    </div>
</div>

<?php Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ?>


<?php $_ctrl = $_control->getComponent("projectGrid"); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render() ;
}}

//
// block title
//
if (!function_exists($_l->blocks['title'][] = '_lb921d76184a_title')) { function _lb921d76184a_title($_l, $_args) { extract($_args)
?><h1>Projekty</h1>
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