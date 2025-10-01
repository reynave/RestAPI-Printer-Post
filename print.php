<?php
require __DIR__ . '/vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;

$printer = "TP805L"; // Ganti dengan nama printer yang sesuai
$profile = CapabilityProfile::load("simple");
$connector = new WindowsPrintConnector($printer);
$printer = new Printer($connector, $profile);

// Teks besar
$printer->setTextSize(2, 2);
$printer->text("Size(2, 2) INI BESAR\n");

// Teks normal
$printer->setTextSize(1, 1);
$printer->text("Size(1, 1) Ini Normal\n");

// Teks lebar saja
$printer->setTextSize(2, 1);
$printer->text("Size(2, 1) Lebar Saja\n");

// Teks tinggi saja
$printer->setTextSize(1, 2);
$printer->text("Size(1, 2) Tinggi Saja\n");

$printer->cut();
$printer->close();