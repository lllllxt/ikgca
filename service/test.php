<?php
$arr = array(3, 4, 2);

for ($i = 0; $i < count($arr); $i++) {
    for ($j = $i + 1; $j < count($arr) - $i - 1; $j++) {
        if ($arr[$i] < $arr[$j]) {
            $t = $arr[$i];
            $arr[$i] =  $arr[$j];
            $arr[$j] = $t;
        }
        print_r($arr);
    }
}
?>