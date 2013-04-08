<!DOCTYPE html>
<html lang="en">
<head>
    <title>Translated example site</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>
<?php

use Doctrine\CouchDB\CouchDBClient;
use Doctrine\CouchDB\HTTP\SocketClient as HttpClient;

require dirname(__DIR__) . '/vendor/autoload.php';

define('LOCALE', 'ru_RU');

$app = translator();

echo $app->translate('hello');
echo $app->injectAtClientSide(strtolower(LOCALE));

//--------------------------------------------------------------------------------------------------

function translator() {
    $storage = new Translator\Storage\CouchDb(new CouchDBClient(new HttpClient(), strtolower(LOCALE)));
    $mode = array_key_exists('t', $_GET) ?
        Translator\Application::TRANSLATE_ON: Translator\Application::TRANSLATE_OFF;

    return new Translator\Application(
        '/translator/',
        new \Translator\Adapter\Simple($storage->mappedTranslations(), $mode),
        $mode
    );
}
?>
</body>
</html>
