<?php
namespace core;

/**
 * Multiple(s) function(s)
 */
Class Image
{
    /**
     * Upload image
     *
     * @param string $folder folder name
     * 
     * @return void
     */
    public static function uploadImage($folder)
    {
        if (isset($_FILES['image']) AND $_FILES['image']['error'] == 0) {
            if ($_FILES['image']['size'] <= 1000000) {
                $datasFile = pathinfo($_FILES['image']['name']);
                $extension_upload = $datasFile['extension'];
                $authorized_extensions = array('jpg', 'jpeg', 'png');

                if (in_array($extension_upload, $authorized_extensions)) {
                    $imagepath = "../public/img/$folder/"
                        . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $imagepath);
                }
            }
        }
    }

    /**
     * Get image Path
     *
     * @param string $folder folder name
     * 
     * @return string
     */
    public static function getImage($folder): string
    {
        if (!empty($_FILES['image']['name'])) {
            $imagePath = "public/img/$folder/" .$_FILES['image']['name'];
        } else {
            $imagePath = "public/img/$folder/default.png";
        }
        return $imagePath;
    }
}
