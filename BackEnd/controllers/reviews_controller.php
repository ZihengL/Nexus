<?php

require_once "$path/controllers/base_controller.php";
require_once "$path/models/review_model.php"; // Ensure this path is correct

class ReviewsController extends BaseController
{
    // protected $model;
    protected $id = "id";
    protected $gameID = "gameID";
    protected $userID = "userID";
    protected $rating = "rating";
    protected $timestamp = "timestamp";
    protected $comment = "comment";

    public function __construct($central_controller, $pdo)
    {
        $this->model = new ReviewModel($pdo);
        parent::__construct($central_controller);
    }

    // GETTERS

    public function getById($id)
    {
        return $this->model->getOne($this->id, $id);
    }

    public function getByGameId($gameId)
    {
        return $this->model->getOne($this->gameID, $gameId);
    }

    public function getByUserId($userId)
    {
        return $this->model->getOne($this->userID, $userId);
    }

    public function getByComment($comment)
    {
        return $this->model->getOne($this->comment, $comment);
    }

    public function getBytimestampt($timestamp)
    {
        return $this->model->getOne($this->timestamp, $timestamp);
    }


    // public function getAll_reviews()
    // {
    //     return $this->model->getAll();
    // }


    public function deleteReview($id)
    {
        return $this->model->delete($id);
    }



    public function getAllMatching($filters = [], $sorting = [], $included_columns = [])
    {
        if (empty($sorting)) {
            $sorting = [$this->rating => true];
        }

        return parent::getAllMatching($filters, $sorting, $included_columns);
    }

    public function create($data)
    {
        // echo "<br> create reviews_controller <br>";
        // print_r($data);
        if ($this->validateReview("create", $data)) {
            if (!$this->checkReviewExists($data)) {
                // echo "create review: ";
                //create review, remove the tokens and send infos without tokens
                unset($data['tokens']);
                $data['timestamp'] = date('Y-m-d');

                // echo "proper review  : ", print_r($data, true), "<br>";
                if ($this->model->create($data)) {
                    return $this->updateGameRatingAverage($data["gameID"]);
                    // return true; 
                }
            }
        }
        // echo "not validated review data: ";
        return false;
    }



    public function delete($data)
    {
        // echo "<br> delete reviews_controller <br>";
        // print_r($data);
        if ($this->validateReview("delete", $data)) {
            if ($this->getOne("id", $data["id"])) {
                // echo "delete review: ";
                // echo "proper review  : ", $data["id"], "<br>";
                if ($this->model->delete($data["id"])) {
                    // echo "mlep: ";
                    return $this->updateGameRatingAverage($data["gameID"]); 
                }
            }
        }
        // echo "not validated review data: ";
        return false;
    }


    public function update($id, $data)
    {
        // echo "<br> update reviews_controller <br>";
        // print_r($data);
        if ($this->validateReview("update", $data)) {
            if ($this->getOne("id", $id)) {
                // echo "update review: ";
                //update review, remove the tokens and send infos without tokens
                unset($data['tokens']);
                // echo "proper review to update : ", print_r($data, true), "<br>";
                if ($this->model->update($id, $data)) {
                    return $this->updateGameRatingAverage($data["gameID"]);
                    // return true; 
                }
            }
        }
        // echo "not validated review data: ";
        return false;
    }


    public function checkReviewExists($data)
    {
        $userID = $data["userID"];
        $gameID = $data["gameID"];
        $filters = [
            'userID' => $userID,
            'gameID' => $gameID,
        ];
        $review = $this->getAllMatching($filters);

        if ($review) {
            // echo "reviews already exists : ", print_r($review, true), "<br>";
            // echo "reviews already exists : ";
            return true;
        }
        return false;
    }

    public function updateGameRatingAverage($gameID)
    {
        $reviews = $this->getAll("gameID", $gameID, null, null);
        // echo "updateGameRatingAverage : <br>";
        if (!empty($reviews)) {
            $totalRating = 0;
            foreach ($reviews as $review) {
                $totalRating += $review['rating'];
            }
            $newAverageRating = count($reviews) > 0 ? $totalRating / count($reviews) : 0;
        } else {
            $newAverageRating = 0;
        }

        $gameController = $this->getGamesController();
        $game = $gameController->getOne("id", $gameID);
        // echo "game before update : ", print_r($game, true), "<br>";
        // echo "newAverageRating : ", $newAverageRating, "<br>";

        if ($game) {
            $game['ratingAverage'] = $newAverageRating;
            // echo "game after update : ", print_r($game, true), "<br>";
            return $gameController->update($gameID, $game);
        }

        return false;
    }


    public function validateReview($action, $data)
    {
        // echo "validateReview: ", print_r($data, true), "<br>";
        $id = $data["id"] ?? null;
        $userID = $data["userID"] ?? null;
        $gameID = $data["gameID"] ?? null;
        $rating = $data["rating"] ?? null;
        $comment = $data["comment"] ?? null;
        $tokens = $data["tokens"] ?? null;
        $accessToken = $tokens["access_token"] ?? null;
        $refreshToken = $tokens["refresh_token"] ?? null;

        // echo "accessToken: " . $accessToken.  "<br>";
        // echo "refreshToken: " . $refreshToken . "<br>";


        $isEmptyData_forCreate = empty($userID) || empty($gameID) || empty($rating) || empty($comment) || empty($accessToken) || empty($refreshToken);
        $isEmptyData_forUpdate = empty($id) || empty($userID) || empty($gameID) || empty($rating) || empty($comment) || empty($accessToken) || empty($refreshToken);
        $isEmptyData_forDelete = empty($id) || empty($userID) || empty($gameID) || empty($accessToken) || empty($refreshToken);
        $gameExists = $this->getGamesController()->getOne("id", $gameID, null);
        $userExists = $this->getUsersController()->getOne("id", $userID, null);
        // $areValidTokens = $this->getTokensController()->validateTokens($userID, $tokens);
        // echo "gameExists: ", print_r($gameExists, true), "<br>";
        // echo "userExists: ", print_r($userExists, true), "<br>";
        // echo "areValidTokens: ". $areValidTokens . "<br>";

        switch ($action) {
            case 'create':
                // echo ": hi <br>";
                if ($isEmptyData_forCreate || !$gameExists || !$userExists) {
                    return false;
                } else {
                    // echo ": blue <br>";
                    return true;
                }
            case 'update':
                if ($isEmptyData_forUpdate || !$gameExists || !$userExists) {  //|| !$areValidTokens
                    return false;
                } else {
                    return true;
                }
            case 'delete':
                if ($isEmptyData_forDelete || !$gameExists || !$userExists) {  //|| !$areValidTokens
                    return false;
                } else {
                    return true;
                }
            default:
                return false;
        }

    }


    function createResponse($success, $message)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return json_encode($response);
    }


}
