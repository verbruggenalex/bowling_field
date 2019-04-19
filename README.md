# Installation

```bash
docker-compose up -d
```

```bash
docker-compose exec web composer install
```

```bash
docker-compose exec web ./vendor/bin/run dsi
```

Using the default configuration, the development site web root should be in the
`build` directory.

- Site is available at [http://127.0.0.1:81/](http://127.0.0.1:81/).
- C9 is available at [http://127.0.0.1:8181/](http://127.0.0.1:8181/).
