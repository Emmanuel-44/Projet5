<?php
namespace controllers;

use core\Image;
use models\User;
use core\Controller;
use core\Pagination;
use models\UserManager;

/**
 * User controller
 */
class UserController extends Controller
{
    public function usersList()
    {
        if ($this->sessionExist('user', 'ADMIN')) {
            $pagination= Pagination::paginationUsers();
            $this->render(
                'backend/usersView.twig', array(
                'users' => $pagination['users'],
                'pages' => $pagination['nbPages'],
                'currentPage' => $pagination['currentPage']
                )
            );
        } else {
            header('location: http://localhost/Projet5');
            exit; 
        }
    }

    /**
     * Login user controller
     *
     */
    public function login()
    {
        $UserManager = new UserManager($this->database);

        if (!$this->sessionExist('user', 'USER')) {
            $email = filter_input(INPUT_POST, 'email');
            $password = filter_input(INPUT_POST, 'password');
            if (isset($email) && isset($password) 
                && !empty($email) && !empty($password)
            ) {

                $check = $UserManager->findByEmail();
    
                if (!$check) {
                    $error = ['fail'];
                    $this->render(
                        'frontend/loginView.twig', array(
                            'error' => $error
                        )
                    );
                } else {
                    $user = $UserManager->getUser($check['id']);
                    if (password_verify($password, $user->getPassword())) {
                        $user->setSession();
                        if ($this->sessionExist('user', 'ADMIN')) {
                            header('location: http://localhost/Projet5/admin');
                            exit;
                        }
                        header('location: http://localhost/Projet5');
                        exit;   
                        
                    } else {
                        $error = ['fail'];
                        $this->render(
                            'frontend/loginView.twig', array(
                                'error' => $error
                            )
                        );
                    }
                }   
            } else {
                $this->render('frontend/loginView.twig');
            } 
        } else {
            header('location: http://localhost/Projet5');
            exit;
        } 
    }

    /**
     * Logout user controller
     *
     * @return void
     */
    public function logout()
    {
        session_destroy();
        header('location: http://localhost/Projet5');
        exit;
    }

    /**
     * Create account
     *
     */
    public function create()
    {
        $UserManager = new UserManager($this->database);

        if (!$this->sessionExist('user', 'USER')) {
            
            if ($this->formValidate($_POST, ['username','email', 'password'])) {

                if (!empty(filter_input(INPUT_POST, 'password'))) {
                    $password = password_hash(filter_input(INPUT_POST, 'password'), PASSWORD_DEFAULT);
                } else {
                    $password = '';
                }
                
                $imagePath = Image::getImage('user');
    
                $user = new User(
                    [
                    'username' => htmlspecialchars(filter_input(INPUT_POST, 'username')),
                    'contactEmail' => htmlspecialchars(filter_input(INPUT_POST, 'email')),
                    'password' => $password,
                    'imagePath' => $imagePath,
                    'role' => ['USER']
                    ]
                );
        
                if ($user->isValid() 
                    && filter_input(INPUT_POST, 'password') == filter_input(INPUT_POST, 'confirm_password')
                ) {
                    $checkUsername = $UserManager->findByUsername();
                    $checkEmail = $UserManager->findByEmail();
    
                    if (!$checkEmail && !$checkUsername) {
                        Image::uploadImage('user');
                        $UserManager->add($user);
                        $user->setSession();
                        header('location: http://localhost/Projet5');
                    } else {
                        if ($checkUsername) {
                            $user->setErrors(['username_error']);
                        }
    
                        if ($checkEmail) {
                            $user->setErrors(['email_error']);
                        }
    
                        if ($checkEmail && $checkUsername) {
                            $user->setErrors(['email_error', 'username_error']);
                        }
    
                        $this->render(
                            'frontend/createUserView.twig', array(
                                'user' => $user
                            )
                        );
                    }
                    
                } else {
                    $this->render(
                        'frontend/createUserView.twig', array(
                            'user' => $user,
                            'post' => $_POST
                        )
                    );
                }
            } else {
                $this->render('frontend/createUserView.twig');
            }
        } else {
            header('location: http://localhost/Projet5');
        }
    }

    /**
     * Add admin role
     *
     */
    public function update()
    {
        if ($this->sessionExist('user', 'SUPER_ADMIN')) {
            if ($this->tokenValidate("http://localhost/Projet5/admin/utilisateurs", 300)) {
                $UserManager = new UserManager($this->database);
                $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
                $userId = (int)substr(strrchr($url, '/'), 1);
                $user = $UserManager->getUser($userId);
                $user = new User(
                    [
                        'id' => $userId,
                        'role' => ['USER', 'ADMIN']
                    ]
                );
                $UserManager->update($user);
            } else {
                session_unset();
                header('location: http://localhost/Projet5'); 
                exit;
            }
        }
        header('location: http://localhost/Projet5/admin/utilisateurs');
        exit;
    }

    /**
     * Remove admin role
     *
     */
    public function remove()
    {
        if ($this->sessionExist('user', 'SUPER_ADMIN')) {
            if ($this->tokenValidate("http://localhost/Projet5/admin/utilisateurs", 300)) {
                $UserManager = new UserManager($this->database);
                $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
                $userId = (int)substr(strrchr($url, '/'), 1);
                $user = $UserManager->getUser($userId);
                $user = new User(
                    [
                        'id' => $userId,
                        'role' => ['USER']
                    ]
                );
                $UserManager->update($user);
            } else {
                session_unset();
                header('location: http://localhost/Projet5');
                exit;
            }
        }
        header('location: http://localhost/Projet5/admin/utilisateurs');
        exit; 
    }
}
