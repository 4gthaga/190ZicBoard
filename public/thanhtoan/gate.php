<?php
if ($gate == "ViettelMoney_DVS") {
	include('gate/ViettelMoney_DVS.php');
}
if ($gate == "Gachthedvs") {
	include('gate/thecao.php');
}
if ($gate == "AutoACB_DVS") {
	include('gate/ACB_DVS.php');
}
