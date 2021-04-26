<?php
namespace controllers;

use core\Controller;
use core\Image;
use core\pagination;
use models\Post;
use models\PostManager;
use models\CommentManager;

/**
 * Post controller
 */
class PostController extends Controller
{
    /**
     * Home page controller
     *
     */
    public function index()
    {
        $PostManager = new PostManager($this->database);
        $posts = $PostManager->getList();
        $this->render('frontend/homeView.twig', array(
            'posts' => $posts
        ));
    }

    /**
     * Blog page controller
     *
     */
    public function blog()
    {
        $pagination= Pagination::paginationPosts();
        $this->render(
            'frontend/blogView.twig', array(
            'posts' => $pagination['posts'],
            'pages' => $pagination['nbPages'],
            'currentPage' => $pagination['currentPage']
            )
        );
    }
    
    /**
     * Read a single post controller
     *
     */
    public function single()
    {
        $url = filter_input(INPUT_SERVER, 'REQUEST_URI');
        if (isset($url)) {
            $PostManager = new PostManager($this->database);
            $CommentManager = new CommentManager($this->database);
            $Postid = (int)substr(strrchr($url, '-'), 1);
            $slug = substr(strrchr($this->reverse_strrchr($url, '-', 0), '/'), 1);
            $urlValid = $PostManager->checkPost($Postid, $slug);
            if ($urlValid) {
                $post = $PostManager->getPost($Postid);
                $comments = $CommentManager->getList($Postid);
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
    }

    /**
     * Home admin page controller
     *
     */
    public function adminIndex()
    {
        if ($this->sessionExist('user', 'ADMIN')) {
            $pagination= Pagination::paginationPosts();
            $this->render(
                'backend/homeView.twig', array(
                'posts' => $pagination['posts'],
                'pages' => $pagination['nbPages'],
                'currentPage' => $pagination['currentPage']
                )
            );
        } else {
            header('location: http://localhost/Projet5'); 
        }     
    }

    /**
     * Add post controller
     *
     */
    public function create()
    {
        $PostManager = new PostManager($this->database);

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
     */
    public function read()
    {
        $PostManager = new PostManager($this->database);
        $CommentManager = new CommentManager($this->database);

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
     */
    public function update()
    {
        $PostManager = new PostManager($this->database);
        $CommentManager = new CommentManager($this->database);

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
     */
    public function delete()
    {
        $PostManager = new PostManager($this->database);

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
