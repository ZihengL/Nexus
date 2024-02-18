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
        parent::__construct($central_controller, $pdo, new ReviewModel($pdo));
    }

    // GETTERS

    public function getById($id)
    {
        return $this->model->getById($id);
    }

    public function getByGameId($gameId)
    {
        return $this->model->getByGameId($this->gameID, $gameId);
    }

    public function getByUserId($userId)
    {
        return $this->model->getByUserId($this->userID, $userId);
    }

    public function getByComment($comment)
    {
        return $this->model->getByComment($this->comment, $comment);
    }

    public function getBytimestampt($timestamp)
    {
        return $this->model->getBytimestamp($this->timestamp, $timestamp);
    }


    public function getAll_reviews()
    {
        return $this->model->getAll_reviews();
    }


    public function deleteReview($id)
    {
        return $this->model->deleteReview($id);
    }

    public function applyFiltersAndSorting($filters, $sorting = null)
    {
        return $this->model->applyFiltersAndSorting($filters, $sorting);
    }
}
