<?php
namespace core;

class Download
{
    public function downloadCv()
    {
        $file = "C:\wamp64\www\CV.pdf";
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . basename($file) . '"');
        echo readfile($file);
    }
}