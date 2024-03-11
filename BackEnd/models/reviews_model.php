<?php

require_once "$path/models/base_model.php";

class ReviewsModel extends BaseModel
{
    public function __construct($pdo)
    {
        parent::__construct($pdo, "reviews");
    }


    // public function create($data)
    // {
    //     // echo "<br> create user_model <br>";
    //     // print_r($data);
    //     if (!$this->validateReview($data)) {
    //         // return false;
    //         echo "create review: ";

    //     }
    //     // return parent::create($new_data);
    // }






}
