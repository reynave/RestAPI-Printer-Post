<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$text = isset($data['message']) ? $data['message'] : 'Default Text';
$printerName = isset($data['printer']) ? $data['printer'] : 'TP805L';

$profile = CapabilityProfile::load("simple");
$connector = new WindowsPrintConnector($printerName);
$printer = new Printer($connector, $profile);

$printer->setTextSize(1, 1);
$printer->text($text . "\n");
$printer->cut();
$printer->close();

echo json_encode(['status' => 'ok']);