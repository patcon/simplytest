(function ($) {
  Drupal.behaviors.simplytest_progress = {
    attach: function (context, settings) {
      $(document).ready(function () {
        if (Drupal.settings.simplytest_progress !== undefined) {
          var progressbar = $('.simplytest-progress-bar');
          var reload = function () {
            $.ajax({
              url: "/progress/" + Drupal.settings.simplytest_progress.id + "/state",
              dataType: "json",
              success: function( data ) {
                $('.bar .filled', progressbar).stop().animate({
                  width: data.percent + '%'
                }, 1000);
                $('.percentage', progressbar).html(data.percent + '%');
                $('.message', progressbar).html(data.message);
                if (data.percent == 100) {
                  window.location.replace('/goto/' + Drupal.settings.simplytest_progress.id);
                }
                setTimeout(reload, 2000);
              }
            });
          }
          reload();
        }
      });
    }
  };
}(jQuery));
