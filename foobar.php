<?php

$result = array_map(function ($num) {
    if ($num % 3 == 0 && $num % 5 == 0) {
        return 'foobar';
    } else if ($num % 3 == 0) {
        return 'foo';
    } else if ($num % 5 == 0) {
        return 'bar';
    } else {
        return $num;
    }
}, range(1, 100));

echo implode(",", $result);