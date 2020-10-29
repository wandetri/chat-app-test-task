<?php
function getFirstLetter($n){
    return strtoupper(substr($n,0,1));
}

function concatLetter($carry,$item){
    return $carry.=$item;
}

/* Create 2-letters max Initials */
function initials($name){
    $in = explode(' ',$name);
    return substr(array_reduce(array_map('getFirstLetter',$in),'concatLetter'),0,2);
}
