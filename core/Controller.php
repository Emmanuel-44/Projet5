<?php
namespace core;

/**
 * Controller class parent
 */
class Controller
{
    protected $twig;
    protected $database;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->twig  = Twig::twig();
        $this->database  = DBFactory:: dbConnect();
    }

    /**
     * Render view
     *
     * @param string $view     view path
     * @param array  $variable variables
     * 
     */
    public function render(string $view, array $variable=[])
    {
        echo $this->twig->render($view, $variable);
    }

    /**
     * Check if session exist
     *
     * @param string $name  name's session
     * @param string $value value's role
     * 
     * @return boolean
     */
    public function sessionExist(string $name, string $value) : bool
    {
        $session = $_SESSION;
        
        return !empty($session[$name]) && in_array(
            $value, $session[$name]['role']
        );
    }

    /**
     * Check if form is valid
     *
     * @param array $form   form name
     * @param array $fields form fields
     * 
     * @return boolean
     */
    public function formValidate(array $form, array $fields) : bool
    {
        foreach ($fields as $field) {
            
            if (isset($form[$field])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check token
     *
     * @param string $formPath
     * 
     * @return boolean
     */
    public function tokenValidate(string $formPath, int $time) : bool
    {
        $session_token = $_SESSION['token'];
        $session_token_time = $_SESSION['token_time'];
        $post_token = filter_input(INPUT_POST, 'token');
        $referer = filter_input(INPUT_SERVER, 'HTTP_REFERER');

        if(isset($session_token) && isset($session_token_time) && isset($post_token))
        {
            //Si le jeton de la session correspond à celui du formulaire
            if($session_token == $post_token)
            {
                //On stocke le timestamp qu'il était il y a X minutes
                $timestamp_ancien = time() - ($time*60);
                //Si le jeton n'est pas expiré
                if($session_token_time >= $timestamp_ancien)
                {
                    //Si le referer est bon
                    if(isset($referer) && $referer == "$formPath")
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
