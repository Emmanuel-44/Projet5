<?php
namespace controllers;

use core\Controller;
use core\Image;
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
        $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
        $post = $PostManager->getPost($id);
        $comments = $CommentManager->getList($id);
        $this->render(
            'frontend/singleView.twig', array(
            'post' => $post,
            'comments' => $comments
            )
        );
    }

    /**
     * Home admin page controller
     *
     * @return void
     */
    public function adminIndex()
    {
        $PostManager = new PostManager($this->db);
        
        if ($this->sessionExist('user', 'ADMIN')) {
            $posts = $PostManager->getList();
            $this->render(
                'backend/homeView.twig', array(
                'posts' => $posts
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

                $imagePath = Image::getImage('post');

                $post = new Post(
                    [
                    'author' => $_POST['username'],
                    'title' => $_POST['title'],
                    'teaser' => $_POST['teaser'],
                    'content' => $_POST['content'],
                    'imagePath' => $imagePath,
                    'slug' => $_POST['title'],
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
            
            $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
            $post = $PostManager->getPost($id);
            $comments = $CommentManager->getList($id);
            $this->render(
                'backend/readView.twig', array(
                'post' => $post,
                'comments' => $comments,
                )
            );
        } else {
            header('location: http://localhost/Projet5');
        }   
    }

    /**
     * Update a form controller in administration
     *
     * @return void
     */
    public function update()
    {
        $PostManager = new PostManager($this->db);
        $CommentManager = new CommentManager($this->db);

        if ($this->sessionExist('user', 'ADMIN')) {
            $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
            $post = $PostManager->getPost($id);
            $countValid = $CommentManager->count($id);
            $countNew = $CommentManager->countNew($id);

            if ($this->formValidate(
                $_POST, ['username', 'title', 'teaser', 'content']
            )
            ) {

                $imagePath = Image::getImage('post');

                $post = new Post(
                    [
                    'id' => $id,
                    'author' => $_POST['username'],
                    'title' => $_POST['title'],
                    'teaser' => $_POST['teaser'],
                    'content' => $_POST['content'],
                    'imagePath' => $imagePath,
                    'slug' => $_POST['title'],
                    'validComment' => $countValid,
                    'newComment' => $countNew
                    ]
                );

                if ($post->isValid()) {
                    Image::uploadImage('post');
                    $PostManager->update($post);
                    $this->render(
                        'backend/updateView.twig', array(
                        'post' => $post
                        )
                    );
                } else {
                    $this->render(
                        'backend/updateView.twig', array(
                        'post' => $post
                        )
                    );
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
            header('location: http://localhost/Projet5');
        }     
    }

    /**
     * Delete a form controller in administration
     *
     * @return void
     */
    public function delete()
    {
        $PostManager = new PostManager($this->db);

        if ($this->sessionExist('user', 'ADMIN')) {
            $id = substr(strrchr($_SERVER['REQUEST_URI'], '-'), 1);
            $PostManager->delete($id);
            header('location:../../admin');
        } else {
            header('location: http://localhost/Projet5');
        }
    }
}
