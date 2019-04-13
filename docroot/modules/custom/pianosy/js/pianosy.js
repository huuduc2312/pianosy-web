(function ($, Drupal) {

    Drupal.PianosyApp = Drupal.PianosyApp || {};
  
    Drupal.behaviors.ActionsPianosyApp = {
      attach: function (context, settings) {
        Drupal.PianosyApp.appAction();
      }
    };
  
    Drupal.PianosyApp.appAction = function() {
    }
  
  })(jQuery, Drupal);