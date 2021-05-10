<?php
namespace core;

/**
 * Session twig
 */
class Globals extends \Twig\Extension\AbstractExtension 
implements \Twig\Extension\GlobalsInterface
{
    /**
     * Get $_SESSION from Twig
     *
     * @return array
     */
    public function getGlobals(): array
    {
        $session = Session::get('');
        $url = filter_input(INPUT_SERVER, 'REQUEST_URI');

        return [
            'session' => $session,
            'url' => $url
        ];
    }
}
