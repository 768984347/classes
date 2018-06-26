<?php
namespace File;

/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-22
 * Time: 15:38
 */

/**
 * 文件类
 * @example
 *  $file = new File();
 * Class File
 * @package File
 */
class File
{
    public $file_name; //文件名(不带后缀)
    public $file_dir; //文件路径目录
    public $file_content; //文件内容
    public $base_name; //文件全名(带后缀)
    public $file_extension; //文件拓展名
    protected $full_path; //文件绝对路径
    protected $file_size; //文件大小
    protected $mode;
    protected $old_full_path;

    /**
     * 设置文件绝对路径
     * File constructor.
     * @param null $path
     */
    public function __construct($path = null)
    {
        if ($path && is_file($path)) {
            $this->setPath($path);
            $this->mode = 'old';
        }
    }

    /**
     * 获取文件名
     * @return mixed
     */
    public function getFileName()
    {
        if ($this->fileInfoNotExists($this->file_name))
            $this->file_name = pathinfo($this->getFileBaseName(), PATHINFO_FILENAME);
        return $this->file_name;
    }

    /**
     * 获取文件拓展名
     * @return mixed
     */
    public function getFileExtension()
    {
        if ($this->fileInfoNotExists($this->file_extension))
            $this->file_extension = pathinfo($this->getFileBaseName(), PATHINFO_EXTENSION);
        return $this->file_extension;
    }

    /**
     * 获取文件目录
     * @return mixed
     */
    public function getFileDir()
    {
        if ($this->fileInfoNotExists($this->file_dir))
            $this->file_dir = pathinfo($this->full_path, PATHINFO_DIRNAME);
        return $this->file_dir;
    }

    /**
     * 获取文件全名
     * @return mixed|string
     */
    public function getFileBaseName()
    {
        if ($this->fileInfoNotExists($this->base_name)) {
            $this->base_name = pathinfo($this->full_path, PATHINFO_BASENAME);
        }
        return $this->base_name;
    }

    /**
     * 获取文件内容
     * @return bool|string
     */
    public function getFileContent()
    {
        if ($this->isFile() && $this->fileInfoNotExists($this->file_content))
            $this->file_content = file_get_contents($this->full_path);
        return $this->file_content;
    }

    /**
     * 获取文件大小
     * @return int
     */
    public function getFileSize()
    {
        if ($this->isFile()) {
            $this->file_size = filesize($this->full_path);
        } elseif ($this->file_content) {
            $this->file_size = strlen($this->file_content);
        }
        return $this->file_size;
    }

    /**
     * 保存文件
     * @param null $path
     * @param string $content
     * @return bool
     */
    public function save($path = null, $content = '')
    {
        if ($path)
            $this->setPath($path);
        else
            $this->setPath($this->getFullPath());
        if ($this->full_path && is_writable($this->getFileDir())) {
            if ($this->mode == 'old')
                $this->changeFileName($this->getFileBaseName());
            file_put_contents($this->full_path, $content ?: $this->getFileContent());
            return true;
        }
        return false;
    }

    /**
     * 设置文件路径
     * @param $path
     * @return $this
     */
    protected function setPath($path)
    {
        $dir = realpath(pathinfo($path, PATHINFO_DIRNAME));
        $file_name = pathinfo($path, PATHINFO_FILENAME);
        $ext_name = pathinfo($path, PATHINFO_EXTENSION);
        if ($dir && $file_name) {
            if (! $this->isRoot($dir))
                $dir = $dir.DIRECTORY_SEPARATOR;
            $this->full_path = $dir.$file_name;
            if ($ext_name)
                $this->full_path = $this->full_path.'.'.$ext_name;
            $this->initPrototype();
        }
        return $this;
    }

    /**
     * 获取文件绝对路径
     * @return mixed
     */
    public function getFullPath()
    {
        $full_path = '';
        if (! $this->full_path) {
            if ($this->file_dir) {
                $full_path = $this->file_dir.DIRECTORY_SEPARATOR;
                if ($this->base_name) {
                    $full_path = $full_path.$this->base_name;
                } elseif ($this->file_name) {
                    $full_path = $full_path.$this->file_name;
                    if ($this->file_extension)
                        $full_path = $full_path.'.'.$this->file_extension;
                }
            }
        } else {
            if ($this->isFile()) {
                $dir = $this->file_dir;
                $file_name_with_ext = $this->file_name.'.'.$this->file_extension;
                if (($this->base_name !== $file_name_with_ext) && (false === strpos($this->full_path, $file_name_with_ext)))
                    $this->base_name = $file_name_with_ext;
                if (! $this->isRoot($dir)) {
                    $dir .= DIRECTORY_SEPARATOR;
                }
                $full_path = $dir.$this->base_name;
            }
        }
        if ($full_path)
            $this->old_full_path = $this->full_path;
        $this->full_path = $full_path;
        return $this->full_path;
    }

    /**
     * 文件信息是否设置（文件是否是存在的）
     * @param $file_info
     * @return bool
     */
    protected function fileInfoNotExists($file_info)
    {
        return (! $file_info && $this->full_path);
    }

    /**
     * 修改文件全名
     * @param $name
     * @return bool
     */
    protected function changeFileName($name)
    {
        $new_full_path = pathinfo($this->full_path, PATHINFO_DIRNAME).DIRECTORY_SEPARATOR.$name;
        if (rename($this->old_full_path, $new_full_path)) {
            $this->setPath($new_full_path);
            return true;
        }
        return false;
    }

    /**
     * 当前对象抽象的是否是存在的文件
     * @return bool
     */
    protected function isFile()
    {
        return is_file($this->full_path);
    }

    /**
     * 初始化模型属性
     */
    protected function initPrototype()
    {
        $this->base_name = null;
        $this->file_name = null;
        $this->file_extension = null;
        $this->file_dir = null;
        $this->base_name = $this->getFileBaseName();
        $this->file_name = $this->getFileName();
        $this->file_extension = $this->getFileExtension();
        $this->file_content = $this->getFileContent();
        $this->file_size = $this->getFileSize();
        $this->file_dir = $this->getFileDir();
        return;
    }

    /**
     * 目录是否为是根目录
     * @param $dir
     * @return bool
     */
    protected function isRoot($dir)
    {
        return $dir == DIRECTORY_SEPARATOR;
    }

}
