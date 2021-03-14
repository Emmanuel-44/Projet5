<?php
namespace controllers;

use core\DBFactory;
use core\Image;
use core\TwigFactory;
use models\User;
use models\UserManager;

/**
 * User controller
 */
class UserController
{
    /**
     * Login user controller
     *
     * @return void
     */
    public function login()
    {
        if (empty($_SESSION['user'])) {
            $twig = TwigFactory::twig();

            if (isset($_POST['email']) && isset($_POST['password']) 
                && !empty($_POST['email']) && !empty(['password'])
            ) {
                $db = DBFactory::dbConnect();
                $UserManager = new UserManager($db);
                $check = $UserManager->findByEmail();
    
                if (!$check) {
                    $error = ['fail'];
                    echo $twig->render(
                        'frontend/loginView.twig', array(
                            'error' => $error
                        )
                    );
                } else {
                    $user = $UserManager->read($check['id']);
                    if (password_verify($_POST['password'], $user->getPassword())) {
                        $user->setSession();
                        if (in_array('ADMIN', $user->getRole())) {
                            header('location: ../Projet5/admin');
                        } else {
                            header('location: ../Projet5');
                        }
                        
                    } else {
                        $error = ['fail'];
                        echo $twig->render(
                            'frontend/loginView.twig', array(
                                'error' => $error
                            )
                        );
                    }
                }   
            } else {
                echo $twig->render('frontend/loginView.twig');
            } 
        } else {
            header('location: ../Projet5');
        } 
    }

    public function logout()
    {
        session_destroy();
        header('location: ../Projet5');
    }

    /**
     * Create account
     *
     * @return void
     */
    public function create()
    {
        if (empty($_SESSION['user'])) {
            $db = DBFactory::dbConnect();
            $twig = TwigFactory::twig();
            
            if (isset($_POST['username']) && isset($_POST['email']) 
                && isset($_POST['password'])
            ) {
                if (!empty($_POST['password'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                } else {
                    $password = '';
                }
                
                $imagePath = Image::getImage('user');
    
                $user = new User(
                    [
                    'username' => $_POST['username'],
                    'contactEmail' => $_POST['email'],
                    'password' => $password,
                    'imagePath' => $imagePath,
                    'role' => ['USER']
                    ]
                );
        
                if ($user->isValid() 
                    && $_POST['password'] == $_POST['confirm_password']
                ) {
                    $UserManager = new UserManager($db);
                    $checkUsername = $UserManager->findByUsername();
                    $checkEmail = $UserManager->findByEmail();
    
                    if (!$checkEmail && !$checkUsername) {
                        Image::uploadImage('user');
                        $UserManager->add($user);
                        $user->setSession();
                        header('location: ../Projet5');
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
    
                        echo $twig->render(
                            'frontend/createUserView.twig', array(
                                'user' => $user
                            )
                        );
                    }
                    
                } else {
                    echo $twig->render(
                        'frontend/createUserView.twig', array(
                            'user' => $user,
                            'post' => $_POST
                        )
                    );
                }
            } else {
                echo $twig->render('frontend/createUserView.twig');
            }
        } else {
            header('location: ../Projet5');
        }
    }
}
