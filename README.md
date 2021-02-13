# Planado

[![reuse compliant](https://reuse.software/badge/reuse-compliant.svg)](https://reuse.software/) [![Hosted on Codeberg](https://img.shields.io/badge/Codeberg-Main%20Repository-blue.svg)](https://codeberg.org/ViOffice/Planado) [![Github Mirror](https://img.shields.io/badge/Github-Mirror-blue.svg)](https://github.com/ViOffice/Planado)

> Planning and Room-Reservation tool for Jitsi-Meet and ViOffice Conference. Live Version at https://planado.vioffice.de

* To be used with either Jitsi-Meet or any compatible Conference solution.

* Jitsi-Meet does not require any special configuration, nor does it require to be aware of Planado.

* Please read the [documentation & examples](docs/README.md) if you want to learn more.

## Requirements:

* PHP (>=7)

* Webserver with PHP support (e.g. Apache2)

* MySQL or MariaDB

## Deployment

1. Clone the repository

```
git clone https://codeberg.org/ViOffice/Planado.git 
cd Planado
```

2. Make your individual configuration changes to the configurations in `conf/`:

* `common.php`
    * Supported languages
    * DB configurations
    * Domains for Planado and the Jitsi-Meet instance in use

* `i18n.php`
    * Language strings for the configured languages in `common.php`
    * More languages may be added via additional `else if ($lang == "xx") { ... }` statements

```
cp conf/common.php.sample conf/common.php
cp conf/i18n.php.sample conf/i18n.php
```

Then edit either file, accordingly. *Note: `i18n.php.sample` already contains working defaults, however `common.php.sample` does not*.

3. Optional: Add your own CSS, JS, images and fonts

* Custom CSS: `static/css/custom.css`
* Custom JS: `static/js/custom.js`
* Custom images: `static/img/background.jpg` & `static/img/waiting.gif`
* Custom fonts: `static/img/fonts/` (also add to your CSS)

```
cp static/img/bg.jpg.sample static/img/bg.jpg
cp static/img/waiting.gif.sample static/img/waiting.gif
```

4. Create the database according to your configurations either manually or via the `create_db.php` script:

*Note: The `create_db.php` may create the database and db-table, but you still need to configure the user and their access to the database manually:*

```
# mysql -e "CREATE USER <your-user>@localhost IDENTIFIED BY '<your-password>';"
```

Then execute the `create_db.php` script:

```
php misc/create_db.php
```

Finally, grant the user access to the database:

```
# mysql -e "GRANT ALL PRIVILEGES on <your-database>.* to '<your-user>'@'localhost';"
# mysql -e "FLUSH privileges;"
```

5. Deploy Planado to your webroot:

```
sudo cp -r ../Planado /var/www/html/planado
curl http://localhost/planado/misc/tests.php?tests=all
> OK!
```

## Maintainers

* [Jan Weymeirsch](https://jan.weymeirs.ch)
    * Contact: [dev-AT-vioffice-DOT-de](mailto:dev<AT>vioffice<DOT>de)

## Contribute

Any pull requests or suggestions are welcome on the main repository at <https://codeberg.org/ViOffice/Planado>, the Github-Mirror at <https://github.com/ViOffice/Planado> or via [e-mail to the maintainers](#maintainers).

Please make sure, your changes are [REUSE-compliant](https://git.fsfe.org/reuse/tool)

## License

Copyright (C) 2021 [Weymeirsch und Langer GbR](mailto:info<AT>vioffice<DOT>de)

See Licenses [here](LICENSES).
