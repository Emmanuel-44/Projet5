<?php
namespace core;

use models\PostManager;
use models\UserManager;

class Pagination
{
    /**
     * Data for posts pagination
     *
     * @return array
     */
    public static function paginationPosts(): array
    {
        // On détermine sur quelle page on se trouve
        $page = filter_input(INPUT_GET, 'page');
        if(isset($page) && !empty($page)){
            $currentPage = (int) strip_tags($page);
        } else {
            $currentPage = 1;
        }

        $database = DBFactory:: dbConnect();
        $PostManager = new PostManager($database);
        $nbPosts = $PostManager->countPost();
        
        // On détermine le nombre d'articles par page
        $perPage = 3;
        // On calcule le nombre de pages total
        $pages = (int)ceil($nbPosts / $perPage);

        // Calcul du 1er article de la page
        $firstPage = ($currentPage * $perPage) - $perPage;

        $posts = $PostManager->getPosts($firstPage, $perPage);

        $pagination = [
            'nbPages' => $pages,
            'currentPage' => $currentPage,
            'posts' => $posts
        ];

        return $pagination;
    }

    /**
     * Data for users pagination
     *
     * @return array
     */
    public static function paginationUsers(): array
    {
        // On détermine sur quelle page on se trouve
        $page = filter_input(INPUT_GET, 'page');
        if(isset($page) && !empty($page)){
            $currentPage = (int)htmlspecialchars($page);
        } else {
            $currentPage = 1;
        }

        $database = DBFactory:: dbConnect();
        $UserManager = new UserManager($database);
        $nbUsers = $UserManager->countUser();
        
        // On détermine le nombre d'articles par page
        $perPage = 4;
        // On calcule le nombre de pages total
        $pages = (int)ceil($nbUsers / $perPage);

        // Calcul du 1er article de la page
        $firstPage = ($currentPage * $perPage) - $perPage;

        $users = $UserManager->getUsers($firstPage, $perPage);

        $pagination = [
            'nbPages' => $pages,
            'currentPage' => $currentPage,
            'users' => $users
        ];

        return $pagination;
    }
}