<?php //netteCache[01]000363a:2:{s:4:"time";s:21:"0.40943500 1366723170";s:9:"callbacks";a:2:{i:0;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:9:"checkFile";}i:1;s:41:"/var/www/sandbox/libs/o5/Grido/Grid.latte";i:2;i:1366722644;}i:1;a:3:{i:0;a:2:{i:0;s:19:"Nette\Caching\Cache";i:1;s:10:"checkConst";}i:1;s:25:"Nette\Framework::REVISION";i:2;s:30:"c0332ac released on 2013-03-08";}}}?><?php

// source file: /var/www/sandbox/libs/o5/Grido/Grid.latte

?><?php
// prolog Nette\Latte\Macros\CoreMacros
list($_l, $_g) = Nette\Latte\Macros\CoreMacros::initRuntime($template, '8g14dzeec9')
;
// prolog Nette\Latte\Macros\UIMacros
//
// block _grid
//
if (!function_exists($_l->blocks['_grid'][] = '_lbe0f77ee1ea__grid')) { function _lbe0f77ee1ea__grid($_l, $_args) { extract($_args); $_control->validateControl('grid')
;Nette\Latte\Macros\FormMacros::renderFormBegin($form = $_form = (is_object("form") ? "form" : $_control["form"]), array()) ?>

<?php $form['buttons']['search']->controlPrototype->class[] = 'btn search' ;$form['buttons']['reset']->controlPrototype->class[] = 'btn reset' ?>

<?php $iterations = 0; foreach ($form->errors as $error): ?><ul>
    <li><?php echo Nette\Templating\Helpers::escapeHtml($error, ENT_NOQUOTES) ?></li>
</ul>
<?php $iterations++; endforeach ?>

<?php $columnCount = count($control[Grido\Components\Columns\Column::ID]->getComponents()) ;$columnCount = $control->hasOperations() ? $columnCount + 1 : $columnCount ;$showActionsColumn = $control->filterRenderType == Grido\Components\Filters\Filter::RENDER_INNER || $control->hasActions() ?>

<?php if ($control->filterRenderType == Grido\Components\Filters\Filter::RENDER_OUTER): call_user_func(reset($_l->blocks['outerFilter']), $_l, get_defined_vars()) ; endif ?>

<?php call_user_func(reset($_l->blocks['table']), $_l, get_defined_vars()) ; Nette\Latte\Macros\FormMacros::renderFormEnd($_form) ;
}}

//
// block outerFilter
//
if (!function_exists($_l->blocks['outerFilter'][] = '_lb5d66293b9f_outerFilter')) { function _lb5d66293b9f_outerFilter($_l, $_args) { extract($_args)
?>    <div class="grido filter outer">
        <div class="items">
<?php if ($control->hasFilters()): $iterations = 0; foreach ($form[Grido\Components\Filters\Filter::ID]->getComponents() as $filter): ?>
                <span class="grid-filter-<?php echo htmlSpecialChars($filter->name) ?>">
                    <?php echo Nette\Templating\Helpers::escapeHtml($filter->label, ENT_NOQUOTES) ?>

                    <?php echo Nette\Templating\Helpers::escapeHtml($filter->control, ENT_NOQUOTES) ?>

                </span>
<?php $iterations++; endforeach ;endif ?>
        </div>
        <div class="buttons">
<?php $_formStack[] = $_form; $formContainer = $_form = (is_object("buttons") ? "buttons" : $_form["buttons"]) ;if ($control->hasFilters()): $_input = (is_object("search") ? "search" : $_form["search"]); echo $_input->getControl()->addAttributes(array()) ;endif ;$_input = (is_object("reset") ? "reset" : $_form["reset"]); echo $_input->getControl()->addAttributes(array()) ;$_form = array_pop($_formStack) ?>
        </div>
    </div>
<?php
}}

//
// block table
//
if (!function_exists($_l->blocks['table'][] = '_lb76235e5f36_table')) { function _lb76235e5f36_table($_l, $_args) { extract($_args)
?><table id="<?php echo htmlSpecialChars($control->name) ?>" class="grido">
    <thead>
        <tr class="head">
<?php if ($control->hasOperations()): ?>            <th class="checker"<?php if ($control->hasFilters()): ?>
 rowspan="<?php if ($control->filterRenderType == Grido\Components\Filters\Filter::RENDER_OUTER): ?>
1<?php else: ?>2<?php endif ?>"<?php endif ?>>
                <input type="checkbox" title="<?php echo htmlSpecialChars($template->translate('Invert')) ?>" />
            </th>
<?php endif ;$iterations = 0; foreach ($control[Grido\Components\Columns\Column::ID]->getComponents() as $column): ?>
                <?php echo $column->getHeaderPrototype()->startTag() ?>

<?php if ($column->isSortable()): if (!$column->getSort()): ?>                        <a href="<?php echo htmlSpecialChars($_control->link("sort!", array(array($column->getName() => Grido\Components\Columns\Column::ASC)))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate($column->label), ENT_NOQUOTES) ?></a>
<?php endif ;if ($column->getSort() == Grido\Components\Columns\Column::ASC): ?>
                        <a class="sort" href="<?php echo htmlSpecialChars($_control->link("sort!", array(array($column->getName() => Grido\Components\Columns\Column::DESC)))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate($column->label), ENT_NOQUOTES) ?></a>
<?php endif ;if ($column->getSort() == Grido\Components\Columns\Column::DESC): ?>
                        <a class="sort" href="<?php echo htmlSpecialChars($_control->link("sort!", array(array()))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate($column->label), ENT_NOQUOTES) ?></a>
<?php endif ?>
                        <span></span>
<?php else: ?>
                        <?php echo Nette\Templating\Helpers::escapeHtml($template->translate($column->label), ENT_NOQUOTES) ?>

<?php endif ?>
                <?php echo $column->getHeaderPrototype()->endTag() ?>

<?php $iterations++; endforeach ;if ($showActionsColumn): ?>            <th class="actions center">
                <?php echo Nette\Templating\Helpers::escapeHtml($template->translate('Actions'), ENT_NOQUOTES) ?>

            </th>
<?php endif ?>
        </tr>
<?php if ($control->filterRenderType == Grido\Components\Filters\Filter::RENDER_INNER): ?>        <tr class="filter inner">
<?php $iterations = 0; foreach ($control[Grido\Components\Columns\Column::ID]->getComponents() as $column): if ($column->hasFilter()): ?>
                    <?php echo $control->getFilter($column->getName())->getWrapperPrototype()->startTag() ?>

<?php $_formStack[] = $_form; $formContainer = $_form = (is_object("filters") ? "filters" : $_form["filters"]) ;$_input = (is_object($column->getName()) ? $column->getName() : $_form[$column->getName()]); echo $_input->getControl()->addAttributes(array()) ;$_form = array_pop($_formStack) ?>
                    <?php echo $control->getFilter($column->getName())->getWrapperPrototype()->endTag() ?>

<?php else: ?>
                    <th>&nbsp;</th>
<?php endif ;$iterations++; endforeach ?>

<?php if ($control->hasFilters()): ?>            <th class="buttons">
<?php $_formStack[] = $_form; $formContainer = $_form = (is_object("buttons") ? "buttons" : $_form["buttons"]) ;$_input = (is_object("search") ? "search" : $_form["search"]); echo $_input->getControl()->addAttributes(array()) ;$_input = (is_object("reset") ? "reset" : $_form["reset"]); echo $_input->getControl()->addAttributes(array()) ;$_form = array_pop($_formStack) ?>
            </th>
<?php endif ?>
        </tr>
<?php endif ?>
    </thead>
    <tfoot>
        <tr>
            <td colspan="<?php echo htmlSpecialChars($showActionsColumn ? $columnCount + 1 : $columnCount) ?>">
<?php call_user_func(reset($_l->blocks['operations']), $_l, get_defined_vars())  ?>

<?php call_user_func(reset($_l->blocks['paginator']), $_l, get_defined_vars())  ?>

<?php call_user_func(reset($_l->blocks['count']), $_l, get_defined_vars())  ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($data) as $row): $checkbox = $control->hasOperations()
                ? $form[Grido\Components\Operation::ID][$row[$control[Grido\Components\Operation::ID]->getPrimaryKey()]]
                : NULL ;$tr = $control->getRowPrototype($row) ;$tr->class[] = $iterator->even ? 'even' : NULL ;$tr->class[] = $checkbox && $checkbox->getValue() ? 'selected' : NULL ?>
            <?php echo $tr->startTag() ?>

<?php if ($checkbox): ?>                <td class="checker">
                    <?php echo Nette\Templating\Helpers::escapeHtml($checkbox->getControl(), ENT_NOQUOTES) ?>

                </td>
<?php endif ;$iterations = 0; foreach ($control[Grido\Components\Columns\Column::ID]->getComponents() as $column): ?>
                    <?php echo $column->getCellPrototype()->startTag() ?>

                        <?php echo $column->render($row) ?>

                    <?php echo $column->getCellPrototype()->endTag() ?>

<?php $iterations++; endforeach ;if ($control->hasActions()): ?>                <td class="actions center">
<?php $iterations = 0; foreach ($control[\Grido\Components\Actions\Action::ID]->getComponents() as $action): if (is_object($action)) $_ctrl = $action; else $_ctrl = $_control->getComponent($action); if ($_ctrl instanceof Nette\Application\UI\IRenderable) $_ctrl->validateControl(); $_ctrl->render($row) ;$iterations++; endforeach ?>
                </td>
<?php endif ?>
            <?php echo $tr->endTag() ?>

<?php $iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;if (!$data): ?>
        <tr><td colspan="<?php echo htmlSpecialChars($showActionsColumn ? $columnCount + 1 : $columnCount) ?>
" class="no-results"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate('No results.'), ENT_NOQUOTES) ?></td></tr>
<?php endif ?>
    </tbody>
</table>
<?php
}}

//
// block operations
//
if (!function_exists($_l->blocks['operations'][] = '_lb3e3f3f6ed8_operations')) { function _lb3e3f3f6ed8_operations($_l, $_args) { extract($_args)
;if ($control->hasOperations()): ?>                <span class="operations"  title="<?php echo htmlSpecialChars($template->translate('Select some row')) ?>">
                    <?php echo Nette\Templating\Helpers::escapeHtml($form[Grido\Components\Operation::ID][Grido\Components\Operation::ID]->control, ENT_NOQUOTES) ?>

<?php $form[Grido\Grid::BUTTONS][Grido\Components\Operation::ID]->controlPrototype->class[] = 'hide' ?>
                    <?php echo Nette\Templating\Helpers::escapeHtml($form[Grido\Grid::BUTTONS][Grido\Components\Operation::ID]->control, ENT_NOQUOTES) ?>

                </span>
<?php endif ;
}}

//
// block paginator
//
if (!function_exists($_l->blocks['paginator'][] = '_lb55e85468fc_paginator')) { function _lb55e85468fc_paginator($_l, $_args) { extract($_args)
;if ($paginator->steps && $paginator->pageCount > 1): ?>                <span class="paginator">
<?php if ($control->page == 1): ?>
                        <span class="btn btn-mini disabled" href="<?php echo htmlSpecialChars($_control->link("page!", array('page' => $paginator->page - 1))) ?>
"><i class="icon-arrow-left"></i> <?php echo Nette\Templating\Helpers::escapeHtml($template->translate('Previous'), ENT_NOQUOTES) ?></span>
<?php else: ?>
                        <a class="btn btn-mini" href="<?php echo htmlSpecialChars($_control->link("page!", array('page' => $paginator->page - 1))) ?>
"><i class="icon-arrow-left"></i> <?php echo Nette\Templating\Helpers::escapeHtml($template->translate('Previous'), ENT_NOQUOTES) ?></a>
<?php endif ;$steps = $paginator->getSteps() ;$iterations = 0; foreach ($iterator = $_l->its[] = new Nette\Iterators\CachingIterator($steps) as $step): if ($step == $control->page): ?>
                            <span class="btn btn-mini disabled"><?php echo Nette\Templating\Helpers::escapeHtml($step, ENT_NOQUOTES) ?></span>
<?php else: ?>
                            <a class="btn btn-mini" href="<?php echo htmlSpecialChars($_control->link("page!", array('page' => $step))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($step, ENT_NOQUOTES) ?></a>
<?php endif ;if ($iterator->nextValue > $step + 1): ?>                        <a class="promt no-ajax" data-grido-promt="<?php echo htmlSpecialChars($template->translate('Enter page:')) ?>
" data-grido-link="<?php echo htmlSpecialChars($_control->link("page!", array('page' => 0))) ?>">...</a>
<?php endif ;$prevStep = $step ;$iterations++; endforeach; array_pop($_l->its); $iterator = end($_l->its) ;if ($control->page == $paginator->getPageCount()): ?>
                        <span class="btn btn-mini disabled" href="<?php echo htmlSpecialChars($_control->link("page!", array('page' => $paginator->page + 1))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate('Next'), ENT_NOQUOTES) ?> <i class="icon-arrow-right"></i></span>
<?php else: ?>
                        <a class="btn btn-mini" href="<?php echo htmlSpecialChars($_control->link("page!", array('page' => $paginator->page + 1))) ?>
"><?php echo Nette\Templating\Helpers::escapeHtml($template->translate('Next'), ENT_NOQUOTES) ?> <i class="icon-arrow-right"></i></a>
<?php endif ?>
                </span>
<?php endif ;
}}

//
// block count
//
if (!function_exists($_l->blocks['count'][] = '_lba68d09969d_count')) { function _lba68d09969d_count($_l, $_args) { extract($_args)
?>                <span class="count">
                    <?php echo Nette\Templating\Helpers::escapeHtml($template->translate('Items %d - %d of %d', $paginator->getCountBegin(), $paginator->getCountEnd(), $control->count), ENT_NOQUOTES) ?>

<?php $_input = (is_object("count") ? "count" : $_form["count"]); echo $_input->getControl()->addAttributes(array()) ;$_formStack[] = $_form; $formContainer = $_form = (is_object("buttons") ? "buttons" : $_form["buttons"]) ;$_input = (is_object("perPage") ? "perPage" : $_form["perPage"]); echo $_input->getControl()->addAttributes(array('class' => 'hide')) ;$_form = array_pop($_formStack) ;if ($control->hasExporting()): ?>
                    <a class="btn btn-mini no-ajax" title="<?php echo htmlSpecialChars($template->translate('Export all items')) ?>
" href="<?php echo htmlSpecialChars($_control->link("export!")) ?>"><i class="icon-download"></i></a>
<?php endif ?>
                </span>
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
if ($_l->extends) { ob_end_clean(); return Nette\Latte\Macros\CoreMacros::includeTemplate($_l->extends, get_defined_vars(), $template)->render(); } ?>
<div id="<?php echo $_control->getSnippetId('grid') ?>"><?php call_user_func(reset($_l->blocks['_grid']), $_l, $template->getParameters()) ?>
</div>