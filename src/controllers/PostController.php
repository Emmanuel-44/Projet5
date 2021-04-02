<?php
namespace controllers;

use core\Controller;
use core\Image;
use models\Post;
use models\PostManager;
use models\CommentManager;
use models\UserManager;

/**
 * Post controller
 */
class PostController extends Controller
{
    /**
     * Home page controller
     *
     * @return void
     */
    public function index()
    {
        $this->render('frontend/homeView.twig');
    }

    /**
     * Blog page controller
     *
     * @return void
     */
    public function blog()
    {
        var_dump($_GET);
        $PostManager = new PostManager($this->db);
        $posts = $PostManager->getList();
        $this->render(
            'frontend/blogView.twig', array(
            'posts' => $posts
            )
        );
    }
    
    /**
     * Read a single post controller
     *
     * @return void
     */
    public function single()
    {
        $PostManager = new PostManager($this->db);
        $CommentManager = new CommentManager($this->db);
        $id = (int)substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $slug = substr(strrchr($this->reverse_strrchr($_SERVER['REQUEST_URI'], '-', 0), '/'), 1);
        $urlValid = $PostManager->checkPost($id, $slug);
        if ($urlValid) {
            $post = $PostManager->getPost($id);
            $comments = $CommentManager->getList($id);
            $this->render(
                'frontend/singleView.twig', array(
                'post' => $post,
                'comments' => $comments
                )
            );
        } else {
            $errorController = new ErrorController();
            $errorController->error404();
        }
    }

    /**
     * Home admin page controller
     *
     * @return void
     */
    public function adminIndex()
    {
        $PostManager = new PostManager($this->db);
        $userManager = new UserManager($this->db);
        
        if ($this->sessionExist('user', 'ADMIN')) {
            $posts = $PostManager->getList();
            $users = $userManager->getList();
            $this->render(
                'backend/homeView.twig', array(
                'posts' => $posts,
                'users' => $users
                )
            );
        } else {
            header('location: http://localhost/Projet5'); 
        }     
    }

    /**
     * Add post controller
     *
     * @return void
     */
    public function create()
    {
        $PostManager = new PostManager($this->db);

        if ($this->sessionExist('user', 'ADMIN')) {

            if ($this->formValidate(
                $_POST, ['username', 'title', 'teaser', 'content']
            ) 
            ) {
                if ($this->tokenValidate("http://localhost/Projet5/admin/ajouter", 300)) {
                    $imagePath = Image::getImage('post');
                    $post = new Post(
                        [
                        'author' => htmlspecialchars($_POST['username'], ENT_NOQUOTES),
                        'title' => htmlspecialchars($_POST['title'], ENT_NOQUOTES),
                        'teaser' => htmlspecialchars($_POST['teaser'], ENT_NOQUOTES),
                        'content' => htmlspecialchars($_POST['content'], ENT_NOQUOTES),
                        'imagePath' => $imagePath,
                        'slug' => htmlspecialchars($_POST['title'], ENT_NOQUOTES),
                        'validComment' => 0,
                        'newComment' => 0
                        ]
                    );
                    
                    if ($post->isValid()) {
                        Image::uploadImage('post');
                        $PostManager->add($post);
                        header('location: ../admin');
                    } else {
                        $this->render(
                            'backend/createView.twig', array (
                            'post' => $post
                            )
                        );
                    }
                } else {
                    session_unset();
                    header('location: http://localhost/Projet5');
                }    
            } else {
                $this->render('backend/createView.twig');
            }
        } else {
            header('location: http://localhost/Projet5');
        }  
    }

    /**
     * Read a post controller in administration
     *
     * @return void
     */
    public function read()
    {
        $PostManager = new PostManager($this->db);
        $CommentManager = new CommentManager($this->db);

        if ($this->sessionExist('user', 'ADMIN')) {
            
            $id = (int)substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
            $slug = substr(strrchr($this->reverse_strrchr($_SERVER['REQUEST_URI'], '-', 0), '/'), 1);
            $urlValid = $PostManager->checkPost($id, $slug);
            if ($urlValid) {
                $post = $PostManager->getPost($id);
                $comments = $CommentManager->getList($id);
                $this->render(
                    'backend/readView.twig', array(
                    'post' => $post,
                    'comments' => $comments,
                    )
                );
            } else {
                $errorController = new ErrorController();
                $errorController->error404();
            }
            
        } else {
            header('location: http://localhost/Projet5');
        }   
    }

    /**
     * Helper to get the slug in url
     *
     * @param string $haystack
     * @param string $needle
     * @param integer $trail
     * 
     * @return string
     */
    private function reverse_strrchr(string $haystack, string $needle, int $trail): string
    {
        return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) + $trail) : false;
    }

    /**
     * Update a post controller in administration
     *
     * @return void
     */
    public function update()
    {
        $PostManager = new PostManager($this->db);
        $CommentManager = new CommentManager($this->db);

        if ($this->sessionExist('user', 'ADMIN')) {
            $id = (int)substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
            $slug = substr(strrchr($this->reverse_strrchr($_SERVER['REQUEST_URI'], '-', 0), '/'), 1);
            $urlValid = $PostManager->checkPost($id, $slug);
            if ($urlValid) {
                $post = $PostManager->getPost($id);
                $slug = $post->getSlug();
                $countValid = $CommentManager->count($id);
                $countNew = $CommentManager->countNew($id);

                if ($this->formValidate(
                    $_POST, ['username', 'title', 'teaser', 'content'] 
                )
                ) {
                    if ($this->tokenValidate("http://localhost/Projet5/admin/modifier/$slug-$id", 300)) {
                        $imagePath = Image::getImage('post');
                        $post = new Post(
                            [
                            'id' => $id,
                            'author' => htmlspecialchars($_POST['username'], ENT_NOQUOTES),
                            'title' => htmlspecialchars($_POST['title'], ENT_NOQUOTES),
                            'teaser' => htmlspecialchars($_POST['teaser'], ENT_NOQUOTES),
                            'content' => htmlspecialchars($_POST['content'], ENT_NOQUOTES),
                            'imagePath' => $imagePath,
                            'slug' => htmlspecialchars($_POST['title'], ENT_NOQUOTES),
                            'validComment' => $countValid,
                            'newComment' => $countNew
                            ]
                        );
        
                        if ($post->isValid()) {
                            Image::uploadImage('post');
                            $PostManager->update($post);
                        }
                        $this->render(
                            'backend/updateView.twig', array(
                            'post' => $post
                            )
                        );
                    } else {
                        session_unset();
                        header('location: http://localhost/Projet5');
                    }       
                } else {
                    $post->setErrors(['vide']);
                    $this->render(
                        'backend/updateView.twig', array(
                        'post' => $post
                        )
                    );
                }
            } else {
                echo 'Cet article n\'existe pas !';
            }
        } else {
            header('location: http://localhost/Projet5');
        }      
    }

    /**
     * Delete a post controller in administration
     *
     * @return void
     */
    public function delete()
    {
        $PostManager = new PostManager($this->db);

        $id = (int)substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);

        if ($this->sessionExist('user', 'ADMIN')) {
            if ($this->tokenValidate("http://localhost/Projet5/admin", 300)) {
                $PostManager->delete($id);
                header('location:http://localhost/Projet5/admin');
            } else {
                session_unset();
                header('location: http://localhost/Projet5');
            } 
        } else {
            header('location: http://localhost/Projet5');
        }     
    }
}
