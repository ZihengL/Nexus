<?php

global $path;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';

require_once $path . 'controllers/central_controller.php';
$central_controller = CentralController::getInstance();

include_once __DIR__ . '/../vendor/autoload.php';
include_once "templates/base.php";

/************************************************
  Make an API request authenticated with a service
  account.
 ************************************************/

$client = new Google\Client();

/************************************************
  ATTENTION: Fill in these values, or make sure you
  have set the GOOGLE_APPLICATION_CREDENTIALS
  environment variable. You can get these credentials
  by creating a new Service Account in the
  API console. Be sure to store the key file
  somewhere you can get to it - though in real
  operations you'd want to make sure it wasn't
  accessible from the webserver!
  Make sure the Books API is enabled on this
  account as well, or the call will fail.
 ************************************************/

$credentials_file = $path . 

if ($credentials_file = checkServiceAccountCredentialsFile()) {
    // set the location manually
    $client->setAuthConfig($credentials_file);
} elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
    // use the application default credentials
    $client->useApplicationDefaultCredentials();
} else {
    echo missingServiceAccountDetailsWarning();
    return;
}

$client->setApplicationName("Client_Library_Examples");
$client->setScopes(['https://www.googleapis.com/auth/books']);
$service = new Google\Service\Books($client);

/************************************************
  We're just going to make the same call as in the
  simple query as an example.
 ************************************************/
$query = 'Henry David Thoreau';
$optParams = [
    'filter' => 'free-ebooks',
];
$results = $service->volumes->listVolumes($query, $optParams);
?>

<h3>Results Of Call:</h3>
<?php foreach ($results as $item) : ?>
    <?= $item['volumeInfo']['title'] ?>
    <br />
<?php endforeach ?>

<?php pageFooter(__FILE__);
