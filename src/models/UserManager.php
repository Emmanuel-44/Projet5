<?php
namespace models;

use DateTime;
use PDO;

/**
 * User manager
 */
class UserManager
{
    private $_db;

    /**
     * Construt
     *
     * @param PDO $db bdd
     */
    public function __construct(PDO $db)
    {
        $this->_db = $db;
    }

    /**
     * Add a user
     *
     * @param User $user entity
     * 
     * @return void
     */
    public function add(User $user)
    {
        $req = $this->_db->prepare(
            'INSERT INTO user 
            (username, contactEmail, password, addingDate, imagePath, role) 
            VALUES (:username, :contactEmail, :password, NOW(), :imagePath, :role)'
        );
        $req->bindValue(':username', $user->getUsername());
        $req->bindValue(':contactEmail', $user->getContactEmail());
        $req->bindValue(':password', $user->getPassword());
        $req->bindValue(':imagePath', $user->getImagePath());
        $req->bindValue(':role', serialize($user->getRole()));
        $req->execute();
    }

    /**
     * Get a user
     *
     * @param [int] $id id user
     * 
     * @return object
     */
    public function getUser($id)
    {
        $req = $this->_db->prepare('SELECT * FROM user WHERE id = :id');
        $req->bindValue(':id', $id);
        $req->execute();
        $req->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'models\User');
        $user = $req->fetch();
        $user->setId((int)$user->getId());
        $user->setAddingDate(new DateTime($user->getAddingDate()));
        $user->setRole(unserialize($user->getRole()));
        return $user;
    }

    /**
     * Check if username exist
     *
     * @return void
     */
    public function findByUsername()
    {
        $req = $this->_db->prepare('SELECT username FROM user WHERE username = ?');
        $req->execute(array($_POST['username']));
        $check = $req->fetch();
        return $check;
    }

    /**
     * Check if email exist
     *
     * @return void
     */
    public function findByEmail()
    {
        $req = $this->_db->prepare('SELECT * FROM user WHERE contactEmail = ?');
        $req->execute(array($_POST['email']));
        $check = $req->fetch();
        return $check;
    }
}
