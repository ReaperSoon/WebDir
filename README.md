[![N|Solid](http://nsm07.casimages.com/img/2017/04/26/17042603415512824615001554.png)](https://github.com/ReaperSoon/WebDir)

WebDir is a based PHP Web GUI to navigate through a directory on your serveur.
You can access all sub-directories, download files in one click and use embeded player for movies and musics

## Getting Started

Juste a little configuration and it's ready to use!
All files used by WebDir are hidden (a dot before the name)

### Screenshots

[![N|Solid](http://nsm07.casimages.com/img/2017/04/26//mini_17042605545612824615001890.png)](http://nsm07.casimages.com/img/2017/04/26//17042605545612824615001890.png) [![N|Solid](http://nsm07.casimages.com/img/2017/04/26//mini_17042606061712824615001943.png)](http://nsm07.casimages.com/img/2017/04/26//17042606061712824615001943.png)

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
cd /var/www
sudo git clone https://github.com/stevecohenfr/WebDir.git
sudo chown -R www-data:www-data WebDir
```

Configure your virtualhost (replace /var/www by your directory path)

```xml
<VirtualHost *:80>
    DocumentRoot /var/www/WebDir
    ServerName %domain%

    <Directory /var/www/WebDir>
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

    ErrorLog ${APACHE_LOG_DIR}/webdir_error.log
    CustomLog ${APACHE_LOG_DIR}/webdir_access.log combined
</VirtualHost>
```

Replace `%domain%` by your domain.
If you installed WebDir in other place than /var/www/WebDir pease update `DocumentRoot /var/www/WebDir` and `<Directory /var/www/WebDir>`

## Update WebDir

To update WebDir you just need to pull

```sh
cd /var/www/WebDir && git pull
```

If you modified some files that have been updated like .config you may need to stash your changes, pull, unstash and resolve conflicts:

```sh
cd /var/www/WebDir
git stash
git pull
git stash pop
# If you have conflics, just open the file(s) with your favorite editor and resolve conflicts
# Your WebDir is up to date !
```

## Configuration file

To configure your WebDir you can edit .config file:
```json
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
sudo htpasswd -b .htpasswd <username> <password>
```
You also can use [this online tool](http://www.htaccesstools.com/htpasswd-generator/)

Add whitelisted IP:
Edit .htaccess file :
```
<RequireAny>
    Require valid-user
    Require ip 127.0.0.1
    # Add another ip here like the line above
</RequireAny>
```

## Built With

* [PHP](https://secure.php.net/) - Language

## Contributing

To contribute please use pull request and if your development is clean and useful it will be integrated on new release

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](http://git.stevecohen.fr/public-projects/webdir/tags). 

## Authors

* **Steve Cohen** - *Initial work* - [PurpleBooth](http://git.stevecohen.fr/explore/projects)

See also the list of [contributors](http://git.stevecohen.fr/public-projects/webdir/CONTRIBUTORS) who participated in this project.

## License

This project is licensed under the MIT License

[MIT License](https://choosealicense.com/licenses/mit/)

Copyright (c) 2017 Steve Cohen


## Acknowledgments

* Based on [KEITH KNITTEL](https://css-tricks.com/styling-a-server-generated-file-directory/) work
