<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Nexus/BackEnd/temp_globals.php';

function validate($controller, $token, $isRefresh)
{
    echo '<br>Jeton: <b>' . $token . '</b><br>';

    if ($isRefresh) {
        echo 'Valide (Refresh): <b>';
        print_r($controller->validateRefreshToken($token));
    } else {
        echo 'Valide (Access): <b>';
        print_r($controller->validateAccessToken($token));
    }

    echo '</b><br>';
}

function lister($controller, $title = '')
{

    $response = $controller->getAll();

    echo '<b>' . $title . '</b><br>';
    foreach ($response as $value) {
        echo implode(', ', $value) . '<br>';
    }

    echo '<br>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['intention'] === 'user_token') {
        $id = $_POST['user_id'];
        $type = $_POST['type_token'] ?? 'login';

        $user = $users_controller->getUserById($id);

        switch ($type) {
            case 'refresh':
                $response = $tokens_controller->generateRefreshToken($user['id']);
                break;
            case 'access':
                $response = $tokens_controller->generateRefreshToken($user['id']);
                break;
            case 'both':
                $response = $tokens_controller->generateTokens($user['id']);
                break;
            case 'revoke':
                $temp = $tokens_controller->generateRefreshToken($user['id']);
                echo '<br>Temp: </br>';
                validate($tokens_controller, $temp, true);
                $response = $tokens_controller->revokeRefreshToken($temp);
                echo '<br>Révocation: </br> ' . $response;
                validate($tokens_controller, $temp, true);
        }

        if (is_array($response)) {
            validate($tokens_controller, $response[0], false);
            validate($tokens_controller, $response[1], true);
        } else {
            validate($tokens_controller, $response, $type === 'refresh');
        }
    } else {
        $type = $_POST['type_action'] ?? 'list';

        if ($type === 'delete') {
            $temp = $tokens_controller->generateRefreshToken(1);
            $tokens_controller->revokeRefreshToken($temp);
            lister($tokens_controller, 'Ajout du jeton expiré<br>');

            $tokens_controller->deleteExpiredTokens();
        }

        lister($tokens_controller, 'Jetons invalides');
    }

    echo '<br>';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Jetons JWT</title>
</head>

<body>
    <h2>JETONS JWT</h2>

    <hr>
    <form action="" method="post">
        <input type="number" id="user_id" name="user_id" placeholder="id" required>

        <select id="type_token" name="type_token" required>
            <option value="refresh">Authentification</option>
            <option value="access">Accès</option>
            <option value="both">Paire</option>
            <option value="revoke">Révocation</option>
        </select><br><br>

        <input type="hidden" name="intention" value="user_token">
        <button type="submit">Soumettre</button>
    </form>
    <hr>

    <hr>
    <form action="" method="post">
        <label for="submit">En base de données</label>
        <select id="type_action" name="type_action" required>
            <option value="list">Lister</option>
            <option value="delete">Supprimer</option>
        </select><br><br>

        <input type="hidden" name="intention" value="db_token">
        <button type="submit">Soumettre</button>
    </form>
    <hr>
</body>

</html>