<?php
namespace Model\Piskoviste\Enum;

use Model\Piskoviste\Enum\MojeEnumClass;
use Model\Piskoviste\Enum\ZkouskaEnumClass;


// ja-----------------------------
$mEnum = new MojeEnumClass();
$mEnum->getConstList();
//-------------------------------------------



$zkouska = new ZkouskaEnumClass();

$kuk = $zkouska(MojeEnumClass::DRUHA);
$kuk = $zkouska('a sem ze zadá neějaká hodnota');