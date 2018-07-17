<?php
namespace Upload;

/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-20
 * Time: 9:49
 */

class Upload
{
    protected $input_file = [];
    protected $path;

    public function __construct(array $files)
    {
        $this->setInputFile($files);
    }

    public function save()
    {
        $filename_array = [];
        foreach ($this->input_file as $name => $item) {
            if (! is_dir($item['path']) && $this->path)
                $item['path'] = $this->path;
            if (is_dir($item['path'])) {
                if (is_array($_FILES[$name]['tmp_name'])) {
                    $filename_array[$name] = $this->uploadMultipartFile($name, $item);
                } else {
                    $item['name'] = $_FILES[$name]['name'];
                    $item['tmp_name'] = $_FILES[$name]['tmp_name'];
                    $filename_array[$name] = $this->uploadSingleFile($item);
                }
            }
        }
        return $filename_array;
    }

    public function setPath($path)
    {
        if (is_dir($path))
            $this->path = $this->getRealPath($path);
        return $this;
    }

    protected function getRealPath($path)
    {
        return realpath($path);
    }

    /**
     *
     * @param array $files
     *  ['name' => ['filename' => 'test','extension' => 'png','path' => '/temp']]
     * @return $this
     */
    protected function setInputFile(array $files)
    {
        $this->input_file = $files;
        return $this;
    }

    protected function uploadSingleFile($item)
    {
        if ($this->isUploadFile($item['tmp_name'])) {
            $filename = $this->getRealPath($item['path']).'/';
            if (isset($item['filename']))
                $filename .= $item['filename'];
            else
                $filename .= $this->getRandFileName();

            if (isset($item['extension'])) {
                if (! empty($item['extension']))
                    $filename .= '.'.$item['extension'];
            } else {
                $filename .= '.'.$this->getExtension($item['name']);
            }

            if (move_uploaded_file($item['tmp_name'], $filename))
                return $filename;
        }
        return null;
    }

    protected function uploadMultipartFile($name, $item)
    {
        $file_obj = [];
        if (is_dir($item['path'])) {
            $file_item['path'] = $item['path'];
            if (isset($item['extension']))
                $file_item['extension'] = $item['extension'];
            foreach ($_FILES[$name]['tmp_name'] as $key => $tmp_path) {
                $file_item['name'] = $_FILES[$name]['name'][$key];
                $file_item['tmp_name'] = $tmp_path;
                $file_obj[] = $this->uploadSingleFile($file_item);
            }
        }
        return $file_obj;
    }

    protected function getRandFileName()
    {
        return md5(microtime().mt_rand(0, 10000));
    }

    protected function getExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    protected function isUploadFile($temp_name)
    {
        return is_uploaded_file($temp_name);
    }
}
