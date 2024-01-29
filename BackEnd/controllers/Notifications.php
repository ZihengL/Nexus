 <?php

require_once "$path/models/notifications.php";

class NotificationsController {
    private $model;

    public function __construct($pdo) {
        $this->model = new NotificationsModel($pdo);
    }

    public function getSubscriberById($id, $columns = []) {
        return $this->model->getById($id, $columns);
    }

    public function getSubscriberByEmail($email, $columns = []) {
        return $this->model->get('email', $email, $columns);
    }

    public function getSubscribersByName($name, $columns = []) {
        return $this->model->get('name', $name, $columns);
    }

    public function getSubscribersByLastname($lastname, $columns = []) {
        return $this->model->get('lastname', $lastname, $columns);
    }

    public function getSubscribersByGender($gender, $columns = []) {
        return $this->model->get('gender', $gender, $columns);
    }

    public function createSubscriber($data) {
        return $this->model->create($data);
    }

    public function updateSubscriber($id, $data) {
        return $this->model->update($id, $data);
    }

    public function deleteSubscriber($id) {
        return $this->model->delete($id);
    }

    public function isSubscribed($email) {
        return !empty($this->getSubscriberByEmail($email));
    }

    // public function subscribeUser($userController, $userId) {
    //     $user = $userController->getUserById($userId);

    //     $data = [
    //         'email' => $user['email'],
    //         'name' => $user['name'],
    //         'last_name' => $user['last_name']
    //     ];

    //     return $this->model->create($data);
    // }

    // public function unsubUser($userController, $userId) {
    //     $user = $userController->getUserById($userId);

    //     if ($user) {
    //         $subber = $this->getSubByEmail($user['email']);

    //         if ($subber) {
    //             return $this->unsub($subber['id']);
    //         }
    //     }

    //     return false;
    // }

    // public function userIsSubbed($userController, $userId) {
    //     $user = $userController->getUserById($userId);

    //     if ($this->getSubByEmail($user['email'])) {
    //         return true;
    //     }

    //     return false;
    // }
}