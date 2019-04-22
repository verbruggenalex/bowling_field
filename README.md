# Bowling field

The bowling field module provides a new field type, widget and formatter to
record bowling scores. By entering your scorecard you can keep track of your
bowling statistics. Currently the following statistics are being kept for each
game: strikes, spares, misses, faults, splits, splits_closed, open frames,
closed frames and total score.

## Development

For development in this module run `docker-compose up -d` and you will have the
Cloud9 editor available at port `8181`. In the C9 shell run `composer install`
and `./vendor/bin/run dsi`. This will result in a working website at port `81`.
