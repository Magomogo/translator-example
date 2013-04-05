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

$app = translator();

echo $app->translate('hello');
echo '<hr>';
echo $app->injectAtClientSide('ru_RU');

//--------------------------------------------------------------------------------------------------

function translator() {
    $storage = new Translator\Storage\CouchDb(new CouchDBClient(new HttpClient(), 'ru_RU'));
    $mode = array_key_exists('t', $_GET) ?
        Translator\Application::TRANSLATE_ON: Translator\Application::TRANSLATE_OFF;

    return new Translator\Application(
        '/translator/',
        new \Translator\Adapter\Simple($storage->readTranslations(), $mode),
        $mode
    );
}
?>
</body>
</html>
