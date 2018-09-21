[![N|Solid](http://nsm07.casimages.com/img/2017/04/26/17042603415512824615001554.png)](https://github.com/ReaperSoon/WebDir)

WebDir is a based PHP Web GUI to navigate through a directory on your serveur.
You can access all sub-directories, download files in one click and use embeded player for movies and musics

## Getting Started

Juste a little configuration and it's ready to use!
All files used by WebDir are hidden (a dot before the name)

### Screenshots

[![N|Solid](https://nsm09.casimages.com/img/2018/09/11//mini_18091105205412824615887913.png)](https://nsm09.casimages.com/img/2018/09/11//18091105205412824615887913.png) [![N|Solid](https://nsm09.casimages.com/img/2018/09/11//mini_18091105244212824615887914.png)](https://nsm09.casimages.com/img/2018/09/11//18091105244212824615887914.png) [![N|Solid](https://nsm09.casimages.com/img/2018/09/11//mini_18091105270912824615887919.png)](https://nsm09.casimages.com/img/2018/09/11//18091105270912824615887919.png)

### Demo
https://www.youtube.com/watch?v=witBmEQnzqw

### Prerequisites

You need to have MAMP (OS X), LAMP (Linux) or WAMP (Windows) installed

Linux
```sh
sudo apt-get install apache2 php5 php5-mysql
#You need to enable mod_rewrite
sudo a2enmod rewrite
#PHP modules
sudo apt-get install php5-curl
#Restart apache
sudo service apache2 restart
```

NB: You do not need MySQL

### Installing

To install WebDir you need to clone this repository on your web directory.
(Exemple for apache on linux : /var/www)

It's important to clone directly in your web directory because the git files need to be in your directory root

```sh
cd /var/www && git clone http://git.stevecohen.fr/public-projects/webdir.git
sudo chown -R www-data:www-data webdir
```

Configure your virtualhost (replace %domain% by own domain)

```xml
<VirtualHost *:80>
    DocumentRoot /var/www/webdir
    ServerName %domain%

    # To enable WebDav uncomment the lines above
    # and enable this php modules : dav dav_fs dav_lock
    # sudo a2enmod dav dav_fs dav_lock && sudo service apache2 restart
    #
    # You will access your WebDav at %domain%/webdav
    # with your .htpasswd user/password
    #Alias /webdav /var/www/webdir
    #<Location /webdav>
    #    DAV On
    #    # Do not uncomment this line if you don't use SSL (https)
    #    SSLRequireSSL
    #    AuthType Basic
    #    AuthName webdav
    #    AuthUserFile /var/www/webdir/.htpasswd
    #    Require valid-user
    #</Location>

    <Directory /var/www/webdir/>
        <IfModule mod_rewrite.c>
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_URI} !index
        RewriteCond %{REQUEST_URI} !webdav
            RewriteEngine on
            RewriteRule ^(.*)$ /.index.php
        </IfModule>
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Order deny,allow
        Allow from all
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/webdir_error.log
    CustomLog ${APACHE_LOG_DIR}/webdir_access.log combined
</VirtualHost>
```

Rename .htaccess_apacheX.X to .htaccess:

```sh
# For apache 2.2
mv .htaccess_apache2.2 .htaccess
```

```sh
# For apache 2.4
mv .htaccess_apache2.4 .htaccess
```

## Security

WebDir allow Basic Auth (username/password) and whitelisted ip with in .htaccess file.

The default credentials :

```
Username: webdir
Password: webdir
```

Default whitelisted IP :
```
127.0.0.1
```

You can :

Add user/passwords
```
cd /var/www/webdir
sudo htpasswd -b .htpasswd <username>
```
You also can use [this online tool](http://www.htaccesstools.com/htpasswd-generator/)


## Update WebDir

To update WebDir you just need to pull

```sh
cd /var/www/webdir && git pull
```

If you modified some files that have been updated like .config you may need to stash your changes, pull, unstash and resolve conflicts:

```sh
cd /var/www/webdir
git stash
git pull
git stash pop
# If you have conflics, just open the file(s) with your favorite editor and resolve conflicts
# Your WebDir is up to date !
```

## Configuration file

To configure your WebDir you can edit .config file:
```
{
   // The bottom left menu
   "menu": [
      {
         "name": "Private access", // Name of the button (on mouse hover)
         "image": "https://cdn4.iconfinder.com/data/icons/small-n-flat/24/lock-16.png", // Icon of the button
         "url": "https://jsfiddle.net/erikasaves/8p1weyud/2/embedded/result/dark/", // The link of the button
         "tab": false, // If true, will open the link in new tab
         "iframe": true // It true, will open the link inside a popin inside the page
      },
      ... // Add as many menu item as you want
   ],
   // The background configuration
   "background": {
      "random": true, // If true, the background url will be ignored will change at every page load using unsplash.com API
      "url": "https://images.unsplash.com/photo-1460602594182-8568137446ce?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=c6a89cf0d31c8ed23b35aaf9a119a9f5&auto=format&fit=crop&w=2255&q=80", // The background image URL (if random is false)
      "showCopyright": true // Show background copyright at bottom of page (please think about the artists)
   },
   "enableRSS": true // Enable the RSS page (button at top right of WebDir)
}
```
Remove (do not add) comments from your .config file to be valid json.


## Built With

* [PHP](https://secure.php.net/) - Language
* [DropZone](https://www.dropzonejs.com/) - Javascript library for file upload with drag&drop

## Contributing

To contribute please use pull request and if your development is clean and useful it will be integrated on new release

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](http://git.stevecohen.fr/public-projects/webdir/tags). 

## Authors

* Made with ❤️ by [**SteveCohenFr**](https://github.com/stevecohenfr)

*Inspired by* - [KEITH KNITTEL](https://css-tricks.com/styling-a-server-generated-file-directory/)

See also the list of [contributors](https://github.com/stevecohenfr/WebDir/blob/master/CONTRIBUTORS) who participated in this project.

## License

This project is licensed under the MIT License

[MIT License](https://choosealicense.com/licenses/mit/)

Copyright (c) 2017 Steve Cohen