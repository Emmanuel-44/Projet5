<?php
namespace core;

/**
 * Controller class parent
 */
class Controller
{
    protected $twig;
    protected $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->twig  = Twig::twig();
        $this->db  = DBFactory:: dbConnect();
    }

    /**
     * Render view
     *
     * @param string $view     view path
     * @param array  $variable variables
     * 
     * @return void
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
        return !empty($_SESSION[$name]) && in_array(
            $value, $_SESSION[$name]['role']
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
            } else {
                return false;
            }
        }
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
        if(isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($_POST['token']))
        {
            //Si le jeton de la session correspond à celui du formulaire
            if($_SESSION['token'] == $_POST['token'])
            {
                //On stocke le timestamp qu'il était il y a 15 minutes
                $timestamp_ancien = time() - ($time*60);
                //Si le jeton n'est pas expiré
                if($_SESSION['token_time'] >= $timestamp_ancien)
                {
                    //Si le referer est bon
                    if($_SERVER['HTTP_REFERER'] == "$formPath")
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
