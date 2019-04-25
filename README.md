# Bowling Field

## Synopsis

The bowling field module provides a new field type, widget and formatter to
record bowling scores. By entering your scorecard you can keep track of your
bowling statistics. Currently the following statistics are being kept for each
game: strikes, spares, misses, faults, splits, splits_closed, open_frames,
closed_frames and total_score. The bowling field scorecard widget updates the
secondary throw options of a frame to only show the possible options.

## Roadmap

- update options of throw 20 and 21 to only show the possible options
- add a single statistic formatter that can be used primarily in views

## Development

For development in this module run `docker-compose up -d` and you will have the
Cloud9 editor available at port `8181`. In the C9 shell run `composer install`
and `./vendor/bin/run dsi`. This will result in a working website at port `81`.
