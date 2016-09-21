# DMARC-Report-Viewer-Extended

A PHP viewer for DMARC records based on [techsneeze dmarcts-report-viewer] (https://github.com/techsneeze/dmarcts-report-viewer) and [techsneeze dmarcts-report-parser.pl](https://github.com/techsneeze/dmarcts-report-parser)
added the following extended features:
* Paging of the DMARC reports
* Loading of the report records via AJAX
* Deleting of reports and its records

## Requirements

PHP >=5.5.0

As I like working with PEAR the following PEAR libraries need to be installed:
* [PEAR DB] (https://pear.php.net/package/DB)
* [PEAR Pager] (https://pear.php.net/package/Pager)
* make sure you copy the pager_wrapper.php (/usr/share/php5/PEAR/doc/pager/examples) into the root of your PEAR install (/usr/share/php5/PEAR/)

And just for the fun of it I made use of [SavantPHP - The Simplest Templating System For PHP minimalist] (https://github.com/7php/SavantPHP)

To get SvantPHP running you need [Composer] (https://getcomposer.org/) ... yes, more dependencies ;)

A Dependency Manager for PHP [Composer Download] (https://getcomposer.org/download/)

Installation of the composer is pretty straight forward:

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

if you would like a global install instead of a local install change above command to:

```
php composer-setup.php --install-dir=/usr/bin --filename=composer
```

Once Composer is installed on your system run the following command to install SavantPHP

```
$ composer require sevenphp/savantphp
```

which will then create a directory /vendor in your project directory with all the necessary files to run Composer and SavantPHP

I have included the /vendor directory with this Git but I'm actually no PHP nor Composer expert!
So ... if you run into trouble with Composer or SavantPHP or /vendor directory included in this git, just delete the /vendor direcotry and do a fresh Composer & SavantPHP install by following the above instructions.


## MySQL Tabel adjustments

NOTE: The viewer expects that you have already populated a database with data from the [techsneeze dmarcts-report-parser.pl](https://github.com/techsneeze/dmarcts-report-parser) script.

Once the database, expected to be named 'dmarc', is setup you have to alter the table engine and add a constraint to get the auto delete feature of the records associated with the report.
Run the following commands in phpMyAdmin or via CLI mysql:

```
ALTER TABLE `report` ENGINE=InnoDB;
ALTER TABLE `rptrecord` ENGINE=InnoDB;
ALTER TABLE `rptrecord` ADD CONSTRAINT `rptrecord_ibfk_1` FOREIGN KEY (`serial`) REFERENCES `dmarc`.`report`(`serial`) ON DELETE CASCADE ON UPDATE RESTRICT;
```

## Installation and Configuration

Download the required files:
```
git clone https://github.com/beckspaced/Dmarc-Report-Viewer-Extended.git
```

Fill in your basic configuration options at /config/config.php:

```
$db_host = "localhost";
$db_name = "dmarc";
$db_user = "dmarc";
$db_pass = "secretpassword";
```

Assuming your apache document root is, e.g. /srv/www/dmarc/httpdocs
which you can access via URL http://my-dmarc-domain.com/

just copy all the files into the document root:

```
/srv/www/dmarc/httpdocs/config
/srv/www/dmarc/httpdocs/css
/srv/www/dmarc/httpdocs/js
/srv/www/dmarc/httpdocs/vendor
/srv/www/dmarc/httpdocs/views
/srv/www/dmarc/httpdocs/index.php
/srv/www/dmarc/httpdocs/ajax.php
/etc/...
```

and then you can access DMARC Report Viewer Extended via URL http://my-dmarc-domain.com/index.php

you can also copy all files into sub-folder, e.g. /dmarc-viewer/
and then you have to navigate in your browser to http://my-dmarc-domain.com/dmarc-viewer/index.php

## Usage

You should be presented with the basic dmarc report view, allowing you to navigate through the reports that have been parsed.
And AJAX load the report records via clicking on the report link.
You can also delete records / report records from the database.

### Legend of the Colors

* Green : DKIM and SPF = pass
* Red : DKIM and SPF = fail
* Orange : Either DKIM or SPF (but not both) = fail
* Yellow : Some other condition, and should be investigated (e.g. DKIM or SPF result were missing, "softfail", "temperror", etc.)


Problems, questions, what-so-ever ... please ask or create a new issue - thanks! [Beckspaced.com](http://beckspaced.com/)

### Thank you

Thank you to [techsneeze dmarcts-report-viewer] (https://github.com/techsneeze/dmarcts-report-viewer) and [techsneeze dmarcts-report-parser.pl](https://github.com/techsneeze/dmarcts-report-parser)

Another thank you to [Composer] (https://getcomposer.org/) and [SavantPHP] (https://github.com/7php/SavantPHP)

