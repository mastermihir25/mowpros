<?php
  // setting up session
  /* note: This is not a secure way to store oAuth tokens. You should use a secure
  *     data sore. We use this for simplicity in this example.
  */
  session_save_path('/tmp');
  session_start();

  define('OAUTH_CONSUMER_KEY', 'qyprd4v9exfERxiilWmHnseqa4uriZ');
  define('OAUTH_CONSUMER_SECRET', 'fszlUrcWLqBQZIhhRa1Qyeq1v5sBe44zUPzMnVtp');
  //define('OAUTH_CONSUMER_KEY', 'Q0IYxuPVlI6JMzVQcUoUTTPk1ZSqCpWyMZP80R8y9gv6sdcsgD');
  //define('OAUTH_CONSUMER_SECRET', 'B2bXzAWRe80sjLYR1B8qrO0MIsLwbMo9vEFG5PHz');
  define('BASE_URL','http://51.15.39.58/mowpros/quickbookapi/');
  
  if(strlen(OAUTH_CONSUMER_KEY) < 5 OR strlen(OAUTH_CONSUMER_SECRET) < 5 ){
    echo "<h3>Set the consumer key and secret in the config.php file before you run this example</h3>";
  }
  
 ?>
