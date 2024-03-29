<?php
namespace controllers;

use core\Image;
use models\User;
use core\Controller;
use core\Pagination;
use core\Session;
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
            return;
        }
        header('location: http://localhost/Projet5');
    }

    /**
     * Login user controller
     *
     */
    public function login()
    {
        $UserManager = new UserManager($this->database);
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
                return;
            }
            $user = $UserManager->getUser($check['id']);
            if (password_verify($password, $user->getPassword())) {
                $user->setSession();
                if ($this->sessionExist('user', 'ADMIN')) {
                    header('location: http://localhost/Projet5/admin');
                    return;
                }
                header('location: http://localhost/Projet5');
            }
            $error = ['fail'];
            $this->render(
                'frontend/loginView.twig', array(
                    'error' => $error
                )
            );
            return; 
        }
        $this->render('frontend/loginView.twig');
    }

    /**
     * Logout user controller
     *
     * @return void
     */
    public function logout()
    {
        Session::forget();
        header('location: http://localhost/Projet5');
    }

    /**
     * Create account
     *
     */
    public function create()
    {
        $UserManager = new UserManager($this->database);
            
        if ($this->formValidate(filter_input_array(INPUT_POST), ['username','email', 'password'])) {

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
                    if ($checkEmail || $checkUsername) {
                        $user->setErrors(['id_error']);
                    }

                    $this->render(
                        'frontend/createUserView.twig', array(
                            'user' => $user
                        )
                    );
                }  
            } else {
                // Confirmation de mot de passe pas rempli
                $this->render(
                    'frontend/createUserView.twig', array(
                        'user' => $user,
                        'post' => filter_input_array(INPUT_POST)
                    )
                );
            }
        } else {
            // Formulaire non rempli, arrivée sur la page
            $this->render('frontend/createUserView.twig');
        }
    }

    /**
     * Add admin role
     *
     */
    public function update()
    {
        if ($this->sessionExist('user', 'SUPER_ADMIN')) {
            if ($this->tokenValidate(300)) {
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
                Session::forget();
                header('location: http://localhost/Projet5'); 
            }
        }
        header('location: http://localhost/Projet5/admin/utilisateurs');
    }

    /**
     * Remove admin role
     *
     */
    public function remove()
    {
        if ($this->sessionExist('user', 'SUPER_ADMIN')) {
            if ($this->tokenValidate(300)) {
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
                Session::forget();
                header('location: http://localhost/Projet5');
            }
        }
        header('location: http://localhost/Projet5/admin/utilisateurs'); 
    }
}
