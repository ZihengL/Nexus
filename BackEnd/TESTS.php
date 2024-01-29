<?php

$arr1 = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 50];
$arr2 = ['b', 'd'];

$result = array_intersect(array_keys($arr1), $arr2);

// print_r($arr1);
// echo "<br>";
// print_r($arr2);
// echo "<br>";
// print_r($result);

foreach ($result as $key) {
    print($arr1[$key] . '<br>');
}