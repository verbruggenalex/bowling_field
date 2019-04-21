<?php

declare(strict_types = 1);

namespace Drupal\bowling_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\MapDataDefinition;

/**
 * Plugin implementation of the 'bowling_field' field type.
 *
 * @FieldType(
 *   id = "bowling_field",
 *   label = @Translation("Bowling"),
 *   description = @Translation("Field type to store bowling scores.") * )
 */
class BowlingFieldType extends FieldItemBase implements FieldItemInterface {

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $scorecard = $this->get('scorecard')->getValue();

    return empty(array_filter($scorecard));
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['scorecard'] = MapDataDefinition::create()
      ->setLabel(new TranslatableMarkup('Scorecard'));

    $properties['scoreboard'] = MapDataDefinition::create()
      ->setLabel(new TranslatableMarkup('Scoreboard'));

    $properties['strikes'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Strikes'));

    $properties['spares'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Spares'));

    $properties['misses'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Misses'));

    $properties['faults'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Faults'));

    $properties['splits'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Splits'));

    $properties['splits_closed'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Splits closed'));

    $properties['open_frames'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Open frames'));

    $properties['closed_frames'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Closed frames'));

    $properties['total_score'] = DataDefinition::create('integer')
      ->setLabel(new TranslatableMarkup('Total score'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'scorecard' => [
          'type' => 'blob',
          'size' => 'normal',
          'not null' => TRUE,
          'serialize' => TRUE,
        ],
        'scoreboard' => [
          'type' => 'blob',
          'size' => 'normal',
          'not null' => TRUE,
          'serialize' => TRUE,
        ],
        'strikes' => [
          'type' => 'int',
          'size' => 'tiny',
          'default' => 0,
          'not null' => TRUE,
        ],
        'spares' => [
          'type' => 'int',
          'size' => 'tiny',
          'default' => 0,
          'not null' => TRUE,
        ],
        'misses' => [
          'type' => 'int',
          'size' => 'tiny',
          'default' => 0,
          'not null' => TRUE,
        ],
        'faults' => [
          'type' => 'int',
          'size' => 'tiny',
          'default' => 0,
          'not null' => TRUE,
        ],
        'splits' => [
          'type' => 'int',
          'size' => 'tiny',
          'default' => 0,
          'not null' => TRUE,
        ],
        'splits_closed' => [
          'type' => 'int',
          'size' => 'tiny',
          'default' => 0,
          'not null' => TRUE,
        ],
        'open_frames' => [
          'type' => 'int',
          'size' => 'tiny',
          'default' => 0,
          'not null' => TRUE,
        ],
        'closed_frames' => [
          'type' => 'int',
          'size' => 'tiny',
          'default' => 0,
          'not null' => TRUE,
        ],
        'total_score' => [
          'type' => 'int',
          'size' => 'small',
          'default' => 0,
          'not null' => TRUE,
        ],
      ],
    ];
  }

}
