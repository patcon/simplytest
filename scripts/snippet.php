<?php

// Make sure snippet is executed at the and of a request.
register_shutdown_function(function() {
  global $simplytest_snippet;
  // Make sure the accepted content is text/html.
  if (isset($simplytest_snippet) && isset($_SERVER['HTTP_ACCEPT']) && strstr($_SERVER['HTTP_ACCEPT'], 'text/html') !== FALSE) {
    // Make sure the returned content is also text/html.
    $headers = headers_list();
    foreach ($headers as $header) {
      $header = strtolower($header);
      if (strstr($header, 'content-type:') !== FALSE) {
        if (strstr($header, 'content-type: text/html') !== FALSE) {
          // Everything is fine, print the snippet.
          // Print infobar.
          _simplytest_snippet_infobar($simplytest_snippet);
          return;
        }
        else {
          return;
        }
      }
    }
  }
});

/**
 * Prints out the infobar snippet for showing time left and other info.
 */
function _simplytest_snippet_infobar($variables) {
  extract($variables);
  $save_project = htmlspecialchars($project, ENT_QUOTES, 'UTF-8');
  $save_version = htmlspecialchars($version, ENT_QUOTES, 'UTF-8');
  // Calculate time left in seconds and pass it into the js.
  $seconds = ($timeout * 60 + $created_timestamp) - time();
  ?>
  <html><head><style type="text/css" media="all">
  .simplytest-snippet-infobar * {
    margin: 0;
    padding: 0;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 17px;
    line-height: 1.2;
    color: #fff !important;
    background-color: transparent;
  }
  .simplytest-snippet-infobar {
    position: fixed;
    z-index: 999999;
    bottom: 0px;
    left: 0px;
    width: 100%;
    background-color: #1B557A;
    border-top: #C0C5FF dashed 1px;
    padding-top: 3px;
  }
  .simplytest-snippet-countdown {
    margin-left: 10px;
    font-weight: bold;
  }
  .simplytest-snippet-title {
    margin-left: 10px;
  }
  .simplytest-snippet-back {
    float: right;
    margin-right: 10px;
  }
  </style></head><body>
  <div class="simplytest-snippet-infobar">
    <span class="simplytest-snippet-countdown" title="Time left">
      <span id="simplytest_snippet_countdown_timer"></span>
    </span>
    <span class="simplytest-snippet-title">
      <?php print $save_project; ?> <?php print $save_version; ?>
    </span>
    </span>
    <span class="simplytest-snippet-back">
      Back to <strong>
        <a href="<? print 'http://simplytest.me/project/' . urlencode($project) . '/' . urlencode($version); ?>">
          simplytest.me
        </a>
      </strong>
    </span>
  </div>
  <script>
  var simplytest_counter = function () {
	  var keep_counting = 1;
	  var no_time_left_message = 'Time over!';
   
	  function countdown() {
		  if(time_left < 2) {
			  keep_counting = 0;
		  }
   
		  time_left = time_left - 1;
	  }
   
	  function add_leading_zero(n) {
		  if(n.toString().length < 2) {
			  return '0' + n;
		  } else {
			  return n;
		  }
	  }
   
	  function format_output() {
		  var hours, minutes, seconds;
		  seconds = time_left % 60;
		  minutes = Math.floor(time_left / 60) % 60;
		  hours = Math.floor(time_left / 3600);
   
		  seconds = add_leading_zero( seconds );
		  minutes = add_leading_zero( minutes );
		  hours = add_leading_zero( hours );
   
		  return hours + ':' + minutes + ':' + seconds;
	  }
   
	  function show_time_left() {
		  document.getElementById(output_element_id).innerHTML = format_output();
	  }
   
	  function no_time_left() {
		  document.getElementById(output_element_id).innerHTML = no_time_left_message;
		  window.location = 'http://simplytest.me/';
	  }
   
	  return {
		  count: function () {
			  countdown();
			  show_time_left();
		  },
		  timer: function () {
			  simplytest_counter.count();
   
			  if(keep_counting) {
				  setTimeout("simplytest_counter.timer();", 1000);
			  } else {
				  no_time_left();
			  }
		  },
		  setTimeLeft: function (t) {
			  time_left = t;
			  if(keep_counting == 0) {
				  simplytest_counter.timer();
			  }
		  },
		  init: function (t, element_id) {
			  time_left = t;
			  output_element_id = element_id;
			  simplytest_counter.timer();
		  }
	  };
  }();
  simplytest_counter.init(<?php echo $seconds ?>, 'simplytest_snippet_countdown_timer');
  if (document.getElementById('edit-name') !== null && document.getElementById('edit-pass') !== null) {
    document.getElementById('edit-name').value="<?php echo $admin_user ?>";
    document.getElementById('edit-pass').value="<?php echo $admin_password ?>";
  }
  if (document.getElementById('edit-account-name') !== null && document.getElementById('edit-account-pass-pass1') !== null) {
    document.getElementById('edit-account-name').value="<?php echo $admin_user ?>";
    document.getElementById('edit-account-pass-pass1').value="<?php echo $admin_password ?>";
    document.getElementById('edit-account-pass-pass2').value="<?php echo $admin_password ?>";
  }
  if (document.getElementById('edit-mysql-database') !== null) {
    document.getElementById('edit-mysql-database').value="<?php echo $mysql ?>";
  }
  if (document.getElementById('edit-mysql-username') !== null) {
    document.getElementById('edit-mysql-username').value="<?php echo $mysql ?>";
  }
  if (document.getElementById('edit-mysql-password') !== null) {
    document.getElementById('edit-mysql-password').value="<?php echo $mysql ?>";
  }
  if (document.getElementById('edit-site-mail') !== null) {
    document.getElementById('edit-site-mail').value="<?php echo $id . $mail_suffix ?>";
  }
  if (document.getElementById('edit-account-mail') !== null) {
    document.getElementById('edit-account-mail').value="<?php echo $id . $mail_suffix ?>";
  }
  </script></body></html>
<?php } ?>
