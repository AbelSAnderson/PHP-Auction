<?php


namespace App\Lib;

use App\Exceptions\FileException;

/**
 * Class File
 * @package App\Lib
 */
class File {
    use Helper;

    /**
     *
     */
    const MAXFILESIZE = 3000000;
    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $type;
    /**
     * @var
     */
    protected $size;
    /**
     * @var
     */
    protected $tmp_name;
    /**
     * @var
     */
    protected $error;

    /**
     * @param $destLoc
     *
     * @return bool
     * @throws FileException
     */
    public static function deleteFile($destLoc): bool {
        if (!file_exists($destLoc))
            throw new FileException("File does not exist");
        return unlink($destLoc);
    }

    /**
     * File constructor.
     *
     * @param $file
     */
    public function __construct($file) {
        foreach ($_FILES["$file"] as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return int
     */
    public function getImageSize(): int {
        return $this->size;
    }

    /**
     * @return bool
     * @throws FileException
     */
    public function moveUploadedFile(): bool {
        if(file_exists(FILE_UPLOADLOC . $this->name))
            throw new FileException("File already exists");

        $result = move_uploaded_file($this->tmp_name, FILE_UPLOADLOC . $this->name);
        if(!$result) throw new FileException("Cannot move file");
        return $result;
    }
}