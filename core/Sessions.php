<?php
namespace core;

/**
 * Session twig
 */
class Sessions extends \Twig\Extension\AbstractExtension 
implements \Twig\Extension\GlobalsInterface
{
    /**
     * Get $_SESSION from Twig
     *
     * @return array
     */
    public function getGlobals(): array
    {
        return [
            'session' => $_SESSION
        ];
    }
}
