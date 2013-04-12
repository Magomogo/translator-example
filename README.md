translator-example
==================

example to be translated with Translator application

### apache virtual host example ###

    <VirtualHost *:80>
        ServerName translator-example.hostname.com

        DocumentRoot /srv/www/translator-example/public
        ProxyPass /translator/ http://translator.local/
    </VirtualHost>


    <VirtualHost 127.0.0.1:80>
        ServerName translator.hostname.com

        DocumentRoot /srv/www/translator/public
        ProxyPass /couchdb/ http://127.0.0.1:5984/
    </VirtualHost>

