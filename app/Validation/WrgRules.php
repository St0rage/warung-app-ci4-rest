<?php

namespace App\Validation;

class WrgRules
{
    public function price_check($str = null)
    {
        if ($str < 500) {
            return false;
        }
        return true;
    }

    public function img_check($str)
    {
        if ($str === null) {
            return true;
        }

        $allowedExt = [
            ['png', 'jpg', 'jpeg'],
            ['.png', '.jpg', '.jpeg']
        ];

        $string64 = explode(',', $str);

        if (count($string64) < 2) {
            for ($i = 0; $i < count($allowedExt[1]); $i++) {
                if (substr($string64[0], -4) === $allowedExt[1][$i] || substr($string64[0], -5) === $allowedExt[1][$i]) {
                    return true;
                }
            }
            return false;
        }

        if (base64_encode(base64_decode($string64[1])) !== $string64[1]) {
            return false;
        }

        $imgData = base64_decode($string64[1]);
        $f = finfo_open();
        $mime_type = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);

        list($format, $ext) = explode('/', $mime_type);

        if (in_array($ext, $allowedExt[0]) && $format === 'image') {
            return true;
        } else {
            return false;
        }
    }

    public function prod_check($str, $id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('products');

        $get = $builder->where('id', $id)->get()->getRowArray();

        if ($get['product_name'] === $str) {
            return true;
        } else {
            $field = $builder->where('product_name', $str)->get()->getNumRows();

            if ($field > 0) {
                return false;
            } else {
                return true;
            }
        }
    }
}
