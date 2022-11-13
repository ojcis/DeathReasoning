<?php
require_once 'Row.php';

$table = [];
if (($handle = fopen('vtmec-causes-of-death.csv', 'r')) !== false) {
    while (($row = fgetcsv($handle, 1000)) !== false) {
        if ($row[1] === 'datums') {
            continue;
        }
        if ($row[2]=='Vardarbīga nāve'){
            $table[] = new Row([$row[1], $row[2], $row[4], $row[5]]);
        }elseif ($row[2]=='Nevardarbīga nāve'){
            $table[] = new Row([$row[1], $row[2], $row[3]]);
        }else{
            $table[] = new Row([$row[1], $row[2]]);
        }
    }
    fclose($handle);
}

$death=[];
foreach ($table as $row){
    $key=$row->getRow()[1];
    if (isset($death[$key])){
        $death[$key]++;
    }else{
        $death[$key]=1;
    }
}

$neVardarbigaNave=[];
foreach ($table as $row){
    if ($row->getNevardarbigaNave()){
        $selectedValue=explode(';',$row->getRow()[2]);
        $key=end($selectedValue);
        if (end($selectedValue)=='pārējie' || end($selectedValue)=='citas'){
            $key=$selectedValue[count($selectedValue)-2];
        }
        if (isset($neVardarbigaNave[$key])){
            $neVardarbigaNave[$key]++;
        }else{
            $neVardarbigaNave[$key]=1;
        }
    }
}

$vardarbigaNave=[];
foreach ($table as $row){
    if ($row->getVardarbigaNave()){
        $selectedValue2=explode(';',$row->getRow()[3]);
        $key=implode('-',$selectedValue2);
        if (isset($vardarbigaNave[$key])){
            $vardarbigaNave[$key]++;
        }else{
            $vardarbigaNave[$key]=1;
        }
    }
}

START:
echo 'Ekspertīzēs noteikto nāves cēloņu statistika.'.PHP_EOL;
echo 'Ekspertīzēs noteikto nāves gadījumu skaits: '.count($table).PHP_EOL;
$i=1;
foreach ($death as $key=>$count){
    echo "[$i] $key - $count gadījumos".PHP_EOL;
    $i++;
}
while (true) {
    $choice = readline('izvēlies gadījuma numuru ko vēlies apkatī tālāk: ');
    if (in_array($choice,range(1,$i))){
        break;
    }
    echo 'nepareiza ievade'.PHP_EOL;
}
echo PHP_EOL;
switch ($choice){
    case 1:
        echo 'Nevardarbīga nāve:'.PHP_EOL;
        foreach ($neVardarbigaNave as $key=>$count) {
            echo "$key - $count gadījumos" . PHP_EOL;
        }
        echo PHP_EOL;
        break;
    case 2:
        echo 'Nāves cēlonis nav noteikt: '.PHP_EOL;
        echo 'nav tālāku datu'.PHP_EOL.PHP_EOL;
        break;
    case 3:
        echo 'Vardarbīga nāve: '.PHP_EOL;
        foreach ($vardarbigaNave as $key=>$count){
            echo "$key - $count gadījumos" .PHP_EOL;
        }
        echo PHP_EOL;
        break;
}
while (true) {
    $end = readline('enter [y] to continue work, enter [n] to end work: ');
    if ($end=='y'){
        echo PHP_EOL.PHP_EOL;
        goto START;
    }
    if ($end=='n'){
        exit;
    }
    echo 'necorecta ievade!'.PHP_EOL;
}

