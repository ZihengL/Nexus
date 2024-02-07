<?php
function printer($content, $title = null)
{
    $result = $title ?? "<hr><h5> $title </h5><br>";

    if (is_array($content)) {
        $is_dict = array_keys($content) !== range(0, count($content) - 1);

        foreach ($content as $element => $value) {
            if ($is_dict) {
                $result .= "$element: <b>$value</b>";
            } else {
                $result .= "$element <br>";
            }
        }
    } else {
        $result .= $content;
    }

    return $result . '<br>';
}

// PATHS
global $path;
global $webpath;
$path = $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/';
$webpath = '/Nexus/BackEnd/tests/';

// CONTROLLERS
require_once $path . 'controllers/centralcontroller.php';
$central_controller = CentralController::getInstance();

$users_controller = $central_controller->users_controller;
$games_controller = $central_controller->games_controller;

// MANAGERS
$token_manager = $central_controller->token_manager;
$database_manager = $central_controller->database_manager;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXUS - Tests</title>
    <style>
        body {
            margin: auto;
            vertical-align: center;
            text-align: center;
            width: 50%;
            padding: 10%;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }

        ul {
            margin: auto 200px;
            list-style-type: none;
            background-color: #C6CF9B;
            border-radius: 5px;
            padding: 0;
            padding-top: 10px;
        }

        li {
            display: inline-block;
            width: 200px;
            height: 50px;
            margin-bottom: 10px;
            padding: 10px;
        }

        li a {
            text-decoration: none;
            color: #333;
        }

        button {
            background-color: #11235A;
            width: 200px;
            height: 50px;
            color: #F6ECA9;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #596FB7;
            color: #C6CF9B;
        }
    </style>
</head>

<body>
    <h2>Démos Back-End</h2>
    <ul>
        <?php
        $files = new DirectoryIterator($path . '/tests/');

        foreach ($files as $file) {
            if ($file->isDot()) {
                continue;
            }

            $name = $file->getFilename();
            $name = substr($file, 0, strpos($name, '.'));
            $file_path = $webpath . $file->getFilename();

            echo "<li><a href='$file_path'><button>$name</button></a></li><br>";
        }
        ?>
    </ul>
</body>

</html>