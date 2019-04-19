<?php

namespace Drupal\bowling_field\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'bowling_field' formatter.
 *
 * @FieldFormatter(
 *   id = "bowling_field",
 *   label = @Translation("Bowling field formatter"),
 *   field_types = {
 *     "bowling_field"
 *   }
 * )
 */
class BowlingFieldFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

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
      $number = $delta+1;
      $items = [
        t('Game') . ': #' . $number,
        t('Strikes') . ': ' . $item->strikes,
        t('Spares') . ': ' . $item->spares,
        t('Score') . ': ' . $item->total_score,
      ];
      
      $elements[$delta]['details'] = [
        '#theme' => 'item_list',
        '#items' => $items,
        '#attributes' => array(
          'class' => 'bowling-details',
        ),
      ];

      $elements[$delta]['scorecard'] = array(
        '#type' => 'table',
        '#rows' => $rows,
        '#attributes' => array(
          'class' => 'bowling-scorecard',
        ),
      );
      
      // Split into seperate library for formatters.
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
