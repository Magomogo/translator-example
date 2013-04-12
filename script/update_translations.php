#!/usr/bin/env php
<?php

use Doctrine\CouchDB\CouchDBClient;
use Translator\SourceCode\Crawler;
use Translator\SourceCode\TranslateIterator\PhpView;
use Translator\Storage\CouchDb;
use Doctrine\CouchDB\HTTP\SocketClient as HttpClient;

include __DIR__ . '/../vendor/autoload.php';

foreach (glob(__DIR__ . '/translations/??_??.php') as $translations) {
    $locale = substr(basename($translations), 0, -4);

    $storage = new CouchDb(new CouchDBClient(new HttpClient(), strtolower($locale)), $locale);
    $crawler = new Crawler($storage, new PhpView());

    $crawler->collectTranslations(array(__DIR__ . '/../public'), include $translations, '.php');
}
