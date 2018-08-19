<?php

// upload_max_filesize = 10M
// post_max_size = 10M

define("MAX_UPLOAD_SIZE_ALLOWED", (5 * 1048576));

echo '<pre>';
print_r($_FILES);
echo '</pre>';

$fileFullName = $_FILES["file"]["name"]; // The file name
$fileFullNameNormalized = normalize($fileFullName);
$fileType = $_FILES["file"]["type"]; // The type of file it is
$fileSize = $_FILES["file"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["file"]["error"]; // 0 for false... and 1 for true
$fileTmpLoc = $_FILES["file"]["tmp_name"]; // File in the PHP tmp folder

echo ini_get('post_max_size');
echo '<br>';
echo ini_get('upload_max_filesize');
echo '<br>';

echo $fileFullName;
echo '<br>';
echo $fileFullNameNormalized;
echo '<br>';
echo $fileType;
echo '<br>';
echo $fileSize;
echo '<br>';
echo $fileErrorMsg;
echo '<br>';
echo $fileTmpLoc;
echo '<br>';
echo MAX_UPLOAD_SIZE_ALLOWED;
echo '<br>';
echo '<br>';

$path_parts = pathinfo($fileFullName);
$fileExtension = $path_parts['extension'];
$fileName = $path_parts['filename'];

if (!$fileTmpLoc) { // if file not chosen
    echo "ERROR: Please browse for a file before clicking the upload button.";
    exit();
}

if ($fileSize > MAX_UPLOAD_SIZE_ALLOWED) {
    echo 'ERROR: Arquivo maior que o máximo permitido';
    exit();
}

// https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mine_type = $finfo->file($fileTmpLoc);
if (!in_array(
        $mine_type,
        array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/msword', // .doc
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //.docx
            'application/pdf'
        ),
        true
    )) {
    echo 'ERROR: Formato de aquivo inválido ou não permitido!';
    exit();
}

if(move_uploaded_file($fileTmpLoc, "uploads/$fileName" . "-" . uniqid() . "." . $fileExtension)){
    echo "$fileFullName upload is complete";
} else {
    echo "move_uploaded_file function failed";
}

function normalize ($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y',
        'Ŕ'=>'R', 'ŕ'=>'r',
    );

    return strtr($string, $table);
}


