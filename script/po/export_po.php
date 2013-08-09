#!/usr/bin/env php
<?php

use Doctrine\CouchDB\HTTP\SocketClient as HttpClient;

include __DIR__ . '/../../vendor/autoload.php';

define('DB_PREFIX', '');

if ($locale = readFirstArgument($argc, $argv)) {

    $http = new HttpClient();
    $response = $http->request('GET', '/' . dbName($locale) . '/_design/main/_list/po/translations', null, true);

    if ($response->status === 200) {
        echo $response->body;
    } else {
        fwrite(STDERR, print_r($response, true));
    }

} else {
    echo "\n";
    echo "This script can export PO translations from couchDB database\n";
    echo "Usage: ./export_po.php {locale}\n";
    echo "\n";
}

//----------------------------------------------------------------------------------------------------------------------

function readFirstArgument($argc, $argv) {

    if ($argc === 2) {
        $locale = $argv[1];
        if (preg_match('/^[a-z]{2}_[a-z]{2}$/i', $locale)) {
            return $locale;
        }
    }
    return false;
}

function dbName($locale) {
    return DB_PREFIX . strtolower($locale);
}
