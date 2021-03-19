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
     * @return void
     */
    public function sessionExist(string $name, string $value)
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
    public function formValidate(array $form, array $fields)
    {
        foreach ($fields as $field) {
            
            if (isset($form[$field])) {
                return true;
            } else {
                return false;
            }
        }
    }
}
