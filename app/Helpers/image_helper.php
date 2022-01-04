<?php

function imgDecoding($string64)
{
    list($type) = explode(';', $string64);
    $type = substr($type, 11);
    $randomStr = mt_rand();
    $newFile = $randomStr . '.' . $type;
    $newPath = FCPATH . 'img/' . $newFile;
    $source  = fopen($string64, 'r');
    $destination =  fopen($newPath, 'w');

    stream_copy_to_stream($source, $destination);

    fclose($source);
    fclose($destination);
    compressImg($newFile);

    return $newFile;
}

function compressImg($filename)
{
    $image = \Config\Services::image();

    $image->withFile(FCPATH . 'img/' . $filename)
        ->resize(250, 0, true, 'auto')
        ->save(FCPATH . 'img/' . $filename);
}
