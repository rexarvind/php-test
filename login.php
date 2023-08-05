<?php

require_once('inc/login-with-google-url.php');
require_once('inc/header.php');

$login_with_google_url = isset($login_with_google_url) ? $login_with_google_url : '';

?>

<a href="<?php echo $login_with_google_url; ?>">Login with Google</a>
<p></p>
<a href="/">Back to home</a>

<?php
require_once('inc/footer.php');

