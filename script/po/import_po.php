#!/usr/bin/env php
<?php

use Doctrine\CouchDB\CouchDBClient;
use Translator\Storage\CouchDb;
use Doctrine\CouchDB\HTTP\SocketClient as HttpClient;

include __DIR__ . '/../../vendor/autoload.php';

define('DB_PREFIX', '');

if ($poInput = readFirstArgument($argc, $argv)) {

    $storage = new CouchDb(conn(dbName($poInput)), locale($poInput));
    $sourceIterator = new \Translator\Import\Source\PortableObject();

    $process = new \Translator\Import\Process($storage);
    $count = $process->run($sourceIterator->select($poInput));

    echo "Imported $count items\n";
    echo "\n";

} else {
    echo "\n";
    echo "This script can import PO translations into couchDB database\n";
    echo "Usage: ./import_po.php {locale}.po\n";
    echo "\n";
}

//----------------------------------------------------------------------------------------------------------------------

function conn($locale)
{
    return new CouchDBClient(new HttpClient(), strtolower($locale));
}

function readFirstArgument($argc, $argv) {

    if ($argc === 2) {
        $filePath = realpath($argv[1]);
        if ('.po' === substr($filePath, -3)) {

            echo "\n";
            echo "This script will import translations from: '" . basename($filePath) . "'\n";
            echo "into couchDb database: '". dbName($filePath) . "'\n";
            echo "\n";
            echo "Press Ctrl+C to abort\n";
            echo 'Proceed? [Y/n]: ';
            if (readFirstLetterInUpperCase() == "N") exit;
            return $filePath;
        }
    }
    return false;
}

function readFirstLetterInUpperCase() {
    return substr(strtoupper(trim(fgets(STDIN))), 0, 1);
}

function dbName($inputFilePath) {
    return DB_PREFIX . strtolower(substr(basename($inputFilePath), 0, -3));
}

function locale($inputFilePath) {
    return substr(basename($inputFilePath), 0, -3);
}