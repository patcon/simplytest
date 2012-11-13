(function ($) {
  Drupal.behaviors.simplytest_launch = {
    attach: function (context, settings) {
      $(document).ready(function () {
        if ($("#edit-project").length > 0) {
          var project_textfield = $('#edit-project');
          // Set focus on project textfield after site is ready.
          project_textfield.focus();
          // Hide version selection if empty.
          var versions_box = $("#edit-version");
          if (versions_box.html() === '') {
            versions_box.hide();
          }
          else {
            versions_box.show();
          }
          // Remember project default value.
          Drupal.behaviors.simplytest_launch.project = project_textfield.val();

          // Avoid escaping the labels of autocomplete.
          $[ "ui" ][ "autocomplete" ].prototype["_renderItem"] = function( ul, item) {
            return $( "<li></li>" ) 
              .data( "item.autocomplete", item )
              .append( $( "<a></a>" ).html( item.label ) )
              .appendTo( ul );
          };
          // Attach autocomplete functionality to project textfield
          $("#edit-project").autocomplete({
			      source: function( request, response ) {
				      $.ajax({
					      url: "/simplytest/projects/autocomplete",
					      dataType: "json",
					      data: {
						      string: request.term
					      },
					      success: function( data ) {
						      response( $.map( data, function( item ) {
							      return {
								      label: item.label,
								      value: item.shortname
							      }
						      }));
					      }
				      });
			      },
			      minLength: 1,
			      select: function( event, ui ) {
				      var project_textfield = $('#edit-project');
              var versions_box = $("#edit-version");
              // Load available project versions.
              if (ui.item.value !== '') {
                if (ui.item.value !== Drupal.behaviors.simplytest_launch.project) {
                  $('#simplytest-launch-block-launcher-form').stop().animate({opacity: 0.25});
                  // Get json and put options into versions select box.
                  $.getJSON('/simplytest/project/' + ui.item.value + '/versions', function(data) {
                  Drupal.behaviors.simplytest_launch.project = ui.item.value;
                    var items = [];
                    $.each(data, function(key, val) {
                      var options = '';
                      $.each(val, function(key, val) {
                        options += '<option value="' + key + '">' + val + '</option>';
                      });
                      items.push('<optgroup label="' + key + '">' + options + '</optgroup>');
                    });
                    if (items.length === 0) {
                      versions_box.hide();
                      versions_box.val('');
                      versions_box.html('');
                    }
                    else {
                      versions_box.show();
                      versions_box.html(items.join(''));
                    }
                    $('#simplytest-launch-block-launcher-form').stop().animate({opacity: 1.0});
                  });
                }
                else {
                  if (versions_box.html() !== '') {
                    versions_box.show();
                  }
                }
              }
              else {
                versions_box.hide();
                versions_box.val('');
                versions_box.html('');
              }
			      },
			      open: function() {
				      $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			      },
			      close: function() {
				      $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			      }
		      });
		    }
      });
    }
  };
}(jQuery));
