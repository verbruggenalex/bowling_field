(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.behaviors.mybehavior = {
    attach: function (context, settings) {

      // Get widget and default options
      var widget = drupalSettings.bowlingField.bowlingWidget.id;
      var options = drupalSettings.bowlingField.bowlingWidget.options;

      // Reset function.
      var reset_options = function(prev_val, next_input) {
        // If we have any pins left standing, update the next select options.
        if (prev_val != ' ' && prev_val != 'X') {
          next_input.removeAttr('disabled');
          var current_val = next_input.val();
          next_input.empty();
          $.each(options, function(key, value) {
            next_input.append($("<option></option>").attr("value", key).text(value));
          });
          var new_options = next_input.find('option');
          $(new_options).each(function(index) {
            var pins = Number($(this).val().replace(/S|F+/g, ''));
            if (pins >=  10 - Number(prev_val.replace(/S|F+/g, ''))) {
              $(this).remove();
            }
            if ($.inArray($(this).val(), [ 'S8', 'S7', 'S6', 'S5', 'S4', 'X', '']) !== -1) {
              $(this).remove();
            }
          });
          //next_input.find('option[value=]').prependTo(next_input);
          next_input.val(current_val);
        }
        // If a strike is trown we set the value for next trow to none and we
        // disable the selectbox because it's not needed in case of a strike.
        else {
          next_input.empty();
          next_input.append($("<option></option>").attr("value", "").text(" "));
        //   next_input.attr('disabled','disabled');
        }
      };

      // Find items.
      $('.field--widget-bowling-field-scorecard').find('.odd.trow', context).each(function () {
        reset_options($(this).val(), $(this).closest('div').next().find('.even.trow'));
      });

      // Reset options on input change.
      $('.field--widget-bowling-field-scorecard').find('.odd.trow', context).change(function () {
        reset_options($(this).val(), $(this).closest('div').next().find('.even.trow'));
        // Set next input to value none.
        if ($(this).val() == 'X') {
          $(this).closest('div').next().find('.even.trow').val('');
        }
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
