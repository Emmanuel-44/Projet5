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
        $image = $_FILES['image'];
        if (isset($image) AND $image['error'] == 0) {
            if ($image['size'] <= 1000000) {
                $datasFile = pathinfo($image['name']);
                $extension_upload = $datasFile['extension'];
                $authorized_ext = array('jpg', 'jpeg', 'png');

                if (in_array($extension_upload, $authorized_ext)) {
                    $imagepath = "../public/img/$folder/"
                        . basename($image['name']);
                    move_uploaded_file($image['tmp_name'], $imagepath);
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
        $image = $_FILES['image'];
        if (!empty($image['name'])) {
            $imagePath = "public/img/$folder/" .$image['name'];
        } else {
            $imagePath = "public/img/$folder/default.png";
        }
        return $imagePath;
    }
}
