<?php

use Doctrine\CouchDB\CouchDBClient;
use Doctrine\CouchDB\HTTP\SocketClient as HttpClient;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = translator();

echo $app->translateAdapter(basename(__FILE__), 'ru')->translate('hello');
echo '<hr>';
echo $app->injectAtClientSide(basename(__FILE__), 'ru');

//--------------------------------------------------------------------------------------------------

function translator() {
    return new Translator\Application(
        '/translator/',
        new Translator\CouchDbStorage(new CouchDBClient(new HttpClient(), 'ru')),
        array_key_exists('t', $_GET) ?
                Translator\Application::TRANSLATE_ON: Translator\Application::TRANSLATE_OFF
    );
}
