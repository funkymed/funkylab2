Funkylab 2.20
=============

# Introduction
Funkylab is a CMS originally done in php4 in "spaghetti pure style".

This version is full compatible php5, but keep the good old shitty code :)

This CMS is here just to study. You will discover how was made CMS in 2005.

GitHub : https://github.com/funkymed/funkylab2

## Author

Cyril Pereira http://www.cyrilpereira.com

## Doc
See the file [data/doc/FunkylabV2.19.6.doc](data/doc/FunkylabV2.19.6.doc)

# How to install

You need to make a vhost like this :
```
<VirtualHost *:80>
  ServerName funkylab2.local
  DocumentRoot /your_path_files/web/
  DirectoryIndex index.php index.html
  <Directory "/your_path_files/web/">
      AllowOverride All
      Options +FollowSymLinks
      Order allow,deny
      allow from all
  </Directory>
</VirtualHost>
```

add this url to your hosts file `funkylab2.local`


make the directory web/image in chmod 777 :
```bash
chmod 777 web/image
```

create a database called 'f2' and load the data/dump/bddfunkylab2.sql file in it.

In commande line :
```bash
mysql -u root f2<data/dump/bddfunkylab2.sql
```

Edit the config file web/admin/config/config.bdd.url.php
```php
<?php
  $host = "localhost";
  $user = "root";
  $pass = "";
  $bdd  = "f2";
  $url  = "http:/funkylab2.local/";
  $emailpost  = "admin@funkylab.net";
?>
```

and voilà you can now see the website on http://funkylab2.local

## Widget

You will found the source of the widget in data/widget/sources_widget
The widget is coded with yahoo widget engine (ex konfabulator) and can found in data/widget
