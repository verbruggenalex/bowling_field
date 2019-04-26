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
 *   id = "bowling_field_statistic",
 *   label = @Translation("Statistic"),
 *   field_types = {
 *     "bowling_field"
 *   }
 * )
 */
class BowlingFieldStatisticFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'display_statistics' => 'total_score',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['display_statistics'] = [
      '#type' => 'select',
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
    $display_statistics = $this->getSetting('display_statistics');
    $summary[] = $this->t('Displays statistic: %statistics', ['%statistics' => $display_statistics]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $strikes = [];
    $spares = [];
    $misses = [];
    $faults = [];
    $splits = [];
    $splits_closed = [];
    $open_frames = [];
    $closed_frames = [];
    $total_score = [];

    foreach ($items as $delta => $item) {

      $strikes[] = $item->strikes;
      $spares[] = $item->spares;
      $misses[] = $item->misses;
      $faults[] = $item->faults;
      $splits[] = $item->splits;
      $splits_closed[] = $item->splits_closed;
      $open_frames[] = $item->open_frames;
      $closed_frames[] = $item->closed_frames;
      $total_score[] = $item->total_score;

      $display_statistics = ${$this->getSetting('display_statistics')};

      $elements[$delta]['#plain_text'] = $display_statistics[$delta];
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
