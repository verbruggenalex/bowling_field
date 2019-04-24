<?php

declare(strict_types = 1);

namespace Drupal\bowling_field\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'bowling_field_scorecard' formatter.
 *
 * @FieldFormatter(
 *   id = "bowling_field_scorecard",
 *   label = @Translation("Scorecard"),
 *   field_types = {
 *     "bowling_field"
 *   }
 * )
 */
class BowlingFieldScorecardFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'display_statistics' => [
        'game_number' => 'game_number',
        'strikes' => 0,
        'spares' => 0,
        'misses' => 0,
        'faults' => 0,
        'splits' => 0,
        'splits_closed' => 0,
        'open_frames' => 0,
        'closed_frames' => 0,
        'total_score' => 'total_score',
      ],
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['display_statistics'] = [
      '#type' => 'checkboxes',
      '#default_value' => $this->getSetting('display_statistics'),
      '#options' => [
        'game_number' => $this->t('Game number'),
        'strikes' => $this->t('Strikes'),
        'spares' => $this->t('Spares'),
        'misses' => $this->t('Misses'),
        'faults' => $this->t('Faults'),
        'splits' => $this->t('Splits'),
        'splits_closed' => $this->t('Splits closed'),
        'open_frames' => $this->t('Open frames'),
        'closed_frames' => $this->t('Closed frames'),
        'total_score' => $this->t('Total score'),
      ],
      '#title' => $this->t('What statistics would you like to display?'),
    ];

    return $element + parent::settingsForm($form, $form_state);
    ;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $display_statistics = array_filter(array_values($this->getSetting('display_statistics')));
    $summary[] = $this->t('Displays statistics: %statistics', ['%statistics' => implode(',', $display_statistics)]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $rows = [];
      $scorecard = $item->scorecard;
      $scoreboard = $item->scoreboard;
      $rows[] = array_values($scorecard);
      $number = $delta + 1;
      $items = [
        'game_number' => t('Game: %number', ['%number' => '#' . $number]),
        'strikes' => t('Strikes: %number', ['%number' => $item->strikes]),
        'spares' => t('Spares: %number', ['%number' => $item->spares]),
        'misses' => t('Misses: %number', ['%number' => $item->misses]),
        'faults' => t('Faults: %number', ['%number' => $item->faults]),
        'splits' => t('Splits: %number', ['%number' => $item->splits]),
        'splits_closed' => t('Splits closed: %number', ['%number' => $item->splits_closed]),
        'open_frames' => t('Open frames: %number', ['%number' => $item->open_frames]),
        'closed_frames' => t('Closed frames: %number', ['%number' => $item->closed_frames]),
        'total_score' => t('Score: %number', ['%number' => $item->total_score]),
      ];
      $display_statistics = array_intersect_key($items, array_flip(array_filter($this->getSetting('display_statistics'))));

      $elements[$delta]['details'] = [
        '#theme' => 'item_list',
        '#items' => $display_statistics,
        '#attributes' => [
          'class' => 'bowling-details',
        ],
      ];

      $elements[$delta]['scorecard'] = [
        '#type' => 'table',
        '#rows' => $rows,
        '#attributes' => [
          'class' => 'bowling-scorecard',
        ],
      ];
      $elements['#attached']['library'][] = 'bowling_field/formatter';
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}
