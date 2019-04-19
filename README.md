# Installation

```bash
docker-compose up -d
```

```bash
docker-compose exec composer install
```

```bash
docker-compose exec ./vendor/bin/run dsi
```

Using the default configuration, the development site web root should be in the `build` directory.

Then the site should be available at [http://127.0.0.1:81/](http://127.0.0.1:81/).
