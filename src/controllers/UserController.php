<?php
namespace controllers;

use core\Controller;
use core\Image;
use models\User;
use models\UserManager;

/**
 * User controller
 */
class UserController extends Controller
{
    /**
     * Login user controller
     *
     * @return void
     */
    public function login()
    {
        $UserManager = new UserManager($this->db);

        if (!$this->sessionExist('user', 'USER')) {

            if (isset($_POST['email']) && isset($_POST['password']) 
                && !empty($_POST['email']) && !empty(['password'])
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
                    if (password_verify($_POST['password'], $user->getPassword())) {
                        $user->setSession();
                        if ($this->sessionExist('user', 'ADMIN')) {
                            header('location: http://localhost/Projet5/admin');
                        } else {
                            header('location: http://localhost/Projet5');
                        }
                        
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
    }

    /**
     * Create account
     *
     * @return void
     */
    public function create()
    {
        $UserManager = new UserManager($this->db);

        if (!$this->sessionExist('user', 'USER')) {
            
            if ($this->formValidate($_POST, ['username','email', 'password'])) {

                if (!empty($_POST['password'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                } else {
                    $password = '';
                }
                
                $imagePath = Image::getImage('user');
    
                $user = new User(
                    [
                    'username' => htmlspecialchars($_POST['username']),
                    'contactEmail' => htmlspecialchars($_POST['email']),
                    'password' => $password,
                    'imagePath' => $imagePath,
                    'role' => ['USER']
                    ]
                );
        
                if ($user->isValid() 
                    && $_POST['password'] == $_POST['confirm_password']
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
     * @return void
     */
    public function update()
    {
        if ($this->sessionExist('user', 'SUPER_ADMIN')) {
            if ($this->tokenValidate("http://localhost/Projet5/admin", 300)) {
                $UserManager = new UserManager($this->db);
                $id = (int)substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
                $user = $UserManager->getUser($id);
                $user = new User(
                    [
                        'id' => $id,
                        'role' => ['USER', 'ADMIN']
                    ]
                );
                $UserManager->update($user);
            } else {
                session_unset();
                header('location: http://localhost/Projet5'); 
            }
        }
        header('location: http://localhost/Projet5/admin');  
    }

    /**
     * Remove admin role
     *
     * @return void
     */
    public function remove()
    {
        if ($this->sessionExist('user', 'SUPER_ADMIN')) {
            if ($this->tokenValidate("http://localhost/Projet5/admin", 300)) {
                $UserManager = new UserManager($this->db);
                $id = (int)substr(strrchr($_SERVER['REQUEST_URI'], '/'), 1);
                $user = $UserManager->getUser($id);
                $user = new User(
                    [
                        'id' => $id,
                        'role' => ['USER']
                    ]
                );
                $UserManager->update($user);
            } else {
                session_unset();
                header('location: http://localhost/Projet5'); 
            }
        }
        header('location: http://localhost/Projet5/admin'); 
    }
}
