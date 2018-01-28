[![N|Solid](http://nsm07.casimages.com/img/2017/04/26/17042603415512824615001554.png)](http://git.stevecohen.fr/public-projects/webdir)

WebDir is a based PHP Web GUI to navigate through a directory on your serveur.
You can access all sub-directories, download files in one click and use embeded player for movies and musics

## Getting Started

Juste a little configuration and it's ready to use!
All files used by WebDir are hidden (a dot before the name)

### Screenshots

[![N|Solid](http://nsm07.casimages.com/img/2017/04/26//mini_17042605545612824615001890.png)](http://nsm07.casimages.com/img/2017/04/26//17042605545612824615001890.png) [![N|Solid](http://nsm07.casimages.com/img/2017/04/26//mini_17042606061712824615001943.png)](http://nsm07.casimages.com/img/2017/04/26//17042606061712824615001943.png)

### Prerequisites

You need to have MAMP (OS X), LAMP (Linux) or WAMP (Windows) installed

Linux
```sh
$ sudo apt-get install apache2 php5 libapache2-mod-php5
```

You need to enable mod_rewrite
```sh
$ sudo a2enmod rewrite
```

NB: You do not need MySQL

### Installing

To install WebDir you need to clone this repository on your web directory.
(Exemple for apache on linux : /var/www)

It's important to clone directly in your web directory because the git files need to be in your directory root

```sh
$ git clone http://git.stevecohen.fr/public-projects/webdir.git /var/www
```

Configure your virtualhost (replace /var/www by your directory path)

```xml
<VirtualHost *:80>
    DocumentRoot /var/www
    ServerName mydomain.ltd

    <Directory />
        <IfModule mod_rewrite.c>
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_URI} !index
            RewriteEngine on
            RewriteRule ^(.*)$ /.index.php
        </IfModule>
        #Options FollowSymLinks
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Order deny,allow
        Allow from all
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```


## Update WebDir

To update WebDir you just need to pull

```sh
$ cd /var/www && git pull
```

## Security

To modify your WebDir security options, edit .htaccess :


The default credentials :

```
Username: webdir
Password: webdir
```

Moreover you can allow access by IP.
127.0.0.1 is allowed by default. Edit .htaccess to add/remove IPs

To generate your credentials you can use [this online tool](http://www.htaccesstools.com/htpasswd-generator/)

## Built With

* [PHP](https://secure.php.net/) - Language

## Contributing

To contribute please use pull request and if your development is clean and useful it will be integrated on new release

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](http://git.stevecohen.fr/public-projects/webdir/tags). 

## Authors

* **Steve Cohen** - *Initial work* - [PurpleBooth](http://git.stevecohen.fr/explore/projects)

See also the list of [contributors](http://git.stevecohen.fr/public-projects/webdir/contributors) who participated in this project.

## License

This project is licensed under the MIT License

[MIT License](https://choosealicense.com/licenses/mit/)

Copyright (c) 2017 Steve Cohen


## Acknowledgments

* Based on [KEITH KNITTEL](https://css-tricks.com/styling-a-server-generated-file-directory/) work
