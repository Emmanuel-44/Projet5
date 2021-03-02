<?php
namespace models;

class Functions
{
    public static function uploadImage()
    {
        if (isset($_FILES['image']) AND $_FILES['image']['error'] == 0)
        {
            if ($_FILES['image']['size'] <= 1000000)
            {
                $datasFile = pathinfo($_FILES['image']['name']);
                $extension_upload = $datasFile['extension'];
                $authorized_extensions = array('jpg', 'jpeg', 'png');

                if (in_array($extension_upload, $authorized_extensions))
                {
                    $imagepath = '../public/img/' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $imagepath);
                }
            }
        }
    }
}