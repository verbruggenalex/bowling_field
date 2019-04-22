<?php

declare(strict_types = 1);

namespace Drupal\bowling_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'bowling_field_scorecard' widget.
 *
 * @FieldWidget(
 *   id = "bowling_field_scorecard",
 *   label = @Translation("Scorecard"),
 *   field_types = {
 *     "bowling_field"
 *   }
 * )
 */
class BowlingFieldScorecardWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $scorecard = $items[$delta]->scorecard;
    $element['#attached']['library'][] = 'bowling_field/widget';
    $element['scorecard'] = [
      '#type' => 'container',
    ];

    $options = [
      '' => ' ',
      'X' => 'X',
      '/' => '/',
      '9' => '9',
      '8' => '8',
      '7' => '7',
      '6' => '6',
      '5' => '5',
      '4' => '4',
      '3' => '3',
      '2' => '2',
      '1' => '1',
      '0' => '0',
      'S8' => 'S8',
      'S7' => 'S7',
      'S6' => 'S6',
      'S5' => 'S5',
      'S4' => 'S4',
      'F0' => 'F0',
    ];
    $settings = [
      'id' => '#edit-' . str_replace('_', '-', $this->fieldDefinition->getName()) . '-' . $delta . '-scorecard',
      'options' => $options,
    ];

    $element['#attached']['drupalSettings']['bowlingField']['bowlingWidget'] = $settings;

    for ($i = 1; $i <= 21; $i++) {
      if ($i & 1) {
        $trowing_options = $options;
        unset($trowing_options['/']);
        $class = ['odd', 'trow'];
      }
      elseif ($i !== 20) {
        $trowing_options = $options;
        unset($trowing_options['X']);
        unset($trowing_options['S8']);
        unset($trowing_options['S7']);
        unset($trowing_options['S6']);
        unset($trowing_options['S5']);
        unset($trowing_options['S4']);
        $class = ['even', 'trow'];
      }
      else {
        $trowing_options['/'] = '/';
      }
      $element['scorecard']['throw-' . $i] = [
        '#type' => 'select',
        '#options' => $trowing_options,
        '#default_value' => $scorecard['throw-' . $i],
        '#attributes' => [
          'class' => $class,
        ],
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $item) {
      $pins = [];
      if (!empty(array_filter($item['scorecard']))) {
        $splits = 0;
        $splits_closed = 0;
        $scorecard = array_values($item['scorecard']);
        foreach ($scorecard as $key => $value) {
          if ($value !== '') {
            if ($value === 'X') {
              $pins[] = 10;
            }
            elseif ($value === '/') {
              $pins[] = 10 - end($pins);
              $prev = $key - 1;
              if ($scorecard[$prev][0] === 'S') {
                $splits_closed++;
              }
            }
            else {
              if ($value === '0') {
                $pins[] = 0;
              }
              else {
                $pins[] = (int) trim($value, 'SF');
                if ($value[0] === 'S') {
                  $splits++;
                }
              }
            }
          }
        }

        $frame_score = $this->bowlingFieldCalculateBowlingScore($pins);
        $scoreboard = [];
        $total_score = 0;

        $closed_frames = 0;
        $open_frames = 0;
        foreach ($frame_score as $frame => $score) {
          if ($score < 10) {
            $open_frames++;
          }
          else {
            $closed_frames++;
          }
          $total_score = $score + $total_score;
          $scoreboard[] = $total_score;
        }

        $frame_average = $total_score / 10;
        $counts = array_count_values($item['scorecard']);
        $values[$delta]['strikes'] = isset($counts['X']) ? $counts['X'] : '0';
        $values[$delta]['spares'] = isset($counts['/']) ? $counts['/'] : '0';
        $values[$delta]['misses'] = isset($counts['0']) ? $counts['0'] : '0';
        $values[$delta]['faults'] = isset($counts['F0']) ? $counts['F0'] : '0';
        $values[$delta]['splits'] = $splits;
        $values[$delta]['splits_closed'] = $splits_closed;
        $values[$delta]['open_frames'] = $open_frames;
        $values[$delta]['closed_frames'] = $closed_frames;
        $values[$delta]['total_score'] = $total_score;
      }
    }

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public static function validate($element, FormStateInterface $form_state) {
    // TODO: validate the incoming selections.
  }

  /**
   * Calculate bowling score.
   *
   * @param array $pins
   *   An array of integers.
   *
   * @retun int Total Score
   */
  private function bowlingFieldCalculateBowlingScore(array $pins) {

    // Seed our variables so we are error free.
    $frame = 0;
    $frame_score = [];

    // Loop through 10 frames.
    while ($frame <= 9) {
      $frame_score[$frame] = array_shift($pins);
      // If strike, use the first ball and add the next two.
      if ($frame_score[$frame] === 10) {
        $frame_score[$frame] = (10 + $pins[0] + $pins[1]);
      }
      // Otherwise our frame uses two throws.
      else {
        $frame_score[$frame] = $frame_score[$frame] + array_shift($pins);
        // Check to see if it's a spare and if so add the next throw.
        if ($frame_score[$frame] === 10) {
          $frame_score[$frame] = (10 + $pins[0]);
        }
      }
      // On to the next one.
      $frame++;
    }

    return $frame_score;
  }

}
