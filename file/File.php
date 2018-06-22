<?php
namespace File;
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-22
 * Time: 15:38
 */

class File
{
    public $file_name;
    public $file_dir;
    public $file_content;
    public $base_name;
    public $file_extension;
    protected $full_path;
    protected $file_size;

    public function __construct($path = null)
    {
        if ($path && is_file($path)) {
            $this->setPath($path);
        }
    }

    public function getFileName()
    {
        if (! $this->fileInfoExists($this->file_name)) {
            $this->file_name = pathinfo($this->full_path, PATHINFO_FILENAME);
        }
        return $this->file_name;
    }

    public function getFileExtension()
    {
        if (! $this->fileInfoExists($this->file_extension)) {
            $this->file_extension = pathinfo($this->full_path, PATHINFO_EXTENSION);
        }
        return $this->file_extension;
    }

    public function getFileDir()
    {
        if (! $this->fileInfoExists($this->file_dir)) {
            $this->file_dir = pathinfo($this->full_path, PATHINFO_DIRNAME);
        }
        return $this->file_dir;
    }
    
    public function getFileBaseName()
    {
        if (! $this->fileInfoExists($this->base_name)) {
            $this->base_name = pathinfo($this->full_path, PATHINFO_BASENAME);
        }
        return $this->base_name;
    }
    
    public function getFileContent()
    {
        if (! $this->fileInfoExists($this->file_content)) {
            $this->file_content = file_get_contents($this->full_path);
        }
        return $this->file_content;
    }
    
    public function getFileSize()
    {
        if (! $this->fileInfoExists($this->file_size)) {
            $this->file_size = filesize($this->full_path);
        } elseif ($this->file_content) {
            $this->file_size = strlen($this->file_content);
        }
        return $this->file_size;
    }

    public function save()
    {
        if ($this->getFullPath()) {
            file_put_contents($this->full_path, $this->getFileContent());
            return true;
        }
        return false;
    }

    public function isFile($full_path)
    {
        if (file_exists($full_path) && ! dir($full_path)) {
            return true;
        }
        return false;
    }
    
    public function setPath($path)
    {
        if ($path) {
            $this->full_path = realpath(pathinfo($path, PATHINFO_DIRNAME)).'/'.pathinfo($path, PATHINFO_BASENAME);
        }
        return $this;
    }

    public function getFullPath()
    {
        if (! $this->full_path) {
            if ($this->file_dir) {
                $full_path = '';
                if ($this->base_name) {
                    $full_path = $this->file_dir.$this->base_name;
                } elseif ($this->file_name && $this->file_extension) {
                    $full_path = $this->file_dir.$this->file_name.'.'.$this->file_extension;
                }
                $this->setPath($full_path);
            }
        }
        return $this->full_path;
    }

    protected function fileInfoExists($file_info)
    {
        return ($file_info && $this->full_path);
    }
}