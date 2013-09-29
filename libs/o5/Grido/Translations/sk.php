<?php

/**
 * This file is part of the Grido (http://grido.bugyik.cz)
 *
 * Copyright (c) 2011 Petr Bugyík (http://petr.bugyik.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

$dict = array(
    'You can use <, <=, >, >=, <>. e.g. ">= %d"' => 'Môžete použiť<, <=, >, >=, <>. Napr.: ">= %d"',
    'Select some row' => 'Vyberte riadok',
    'Invert' => 'Obrátiť výber',
    'Items %d - %d of %d' => 'Položky %d - %d z %d',
    'Previous' => 'Predchadzajúce',
    'Next' => 'Ďalej',
    'Actions' => 'Akcie',
    'Search' => 'Vyhľadať',
    'Reset' => 'Resetovať',
    'Items per page' => 'Položiek na stránku',
    'Selected...' => 'Vybrané...',
    'Enter page:' => 'Vložte stranu:',
    'No results.' => 'Žiadné výsledky.',
    'Export all items' => 'Exportovat všetky položky',
);

function pluralIndex($number)
{
    $number = (int) $number;
    if ($number == 1) {
        return 0;
    } else if ($number >= 2 && $number <= 4) {
        return 1;
    } else {
        return 2;
    }
}
