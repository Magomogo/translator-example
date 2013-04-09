<?php

use Doctrine\CouchDB\CouchDBClient;
use Doctrine\CouchDB\HTTP\SocketClient as HttpClient;

require dirname(__DIR__) . '/vendor/autoload.php';

define('LOCALE', readLocaleCode($_GET) ?: 'en_US');

$app = translator();

//--------------------------------------------------------------------------------------------------

function translator() {
    $storage = new Translator\Storage\CouchDb(new CouchDBClient(new HttpClient(), strtolower(LOCALE)));
    $mode = array_key_exists('t', $_GET) ?
        Translator\Application::TRANSLATE_ON: Translator\Application::TRANSLATE_OFF;

    return new Translator\Application(
        '/translator/',
        new \Translator\Adapter\ICU($storage->mappedTranslations(), LOCALE),
        $mode
    );
}

function readLocaleCode($get) {
    if (array_key_exists('locale', $get)
        && preg_match('/^[a-z]{2}_[a-z]{2}$/i', $get['locale'])
        && in_array(strtolower($get['locale']), array('en_us', 'ru_ru'))) {
        return $get['locale'];
    }
    return null;
}

function addl() {
    echo '?locale=' . LOCALE;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Translated example site</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
</head>
<body>
<a href="<?php addl() ?>&t">Translate ON</a> | <a href="<?php addl() ?>">Translate OFF</a> |
<a href="?locale=en_US">en_US</a> | <a href="?locale=ru_RU">ru_RU</a>
<hr/>
<div>
    <?php echo $app->translate('hello'); ?>
    <?php echo $app->translate('niceDate/time:hours', array(date('H'))); ?>
    <?php echo $app->translate('niceDate/time:minutes', array(date('i'))); ?>
    <br>
    <br>
    <ol>
    <?php for ($i = 1; $i <=10; $i++):?>
        <li>
            <?php echo $app->translate('plural:numberOfPages', array($i)) ?>
        </li>
    <?php endfor ?>
    </ol>
</div>

<?php echo $app->injectAtClientSide(strtolower(LOCALE)); ?>
</body>
</html>
