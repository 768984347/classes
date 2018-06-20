<?php
/**
 * Created by PhpStorm.
 * User: pxb
 * Date: 2018-06-20
 * Time: 9:49
 */

/**
 * 下载类
 * @example
 *     $download = new Download();
 *     $download->setFile('/file_path')->setFileName('text.txt')->start();
 *
 *     $file = file_get_contents('./img/test.jpg');
 *     $download->setFile($file)->setFileName('text.txt')->start();
 *  如果是非文件类型
 *     $download = new Download();
 *     echo 123;
 *     $download->setFileName('test.txt')->start();
 * Class Download
 */
class Download
{
    protected $file;
    protected $file_name;
    protected $file_size;

    public function __construct()
    {
        ob_start();
    }

    /**
     * 设置文件地址或者资源句柄
     * @param $file //resource or string
     * @return $this
     */
    public function setFile($file)
    {
        if (is_resource($file)) {
            $this->file = $file;
        } elseif (file_exists($file) && ! is_dir($file)) {
            $this->file = file_get_contents($file);
        }
        $this->outputFile($file);
        return $this;
    }

    /**
     * 开始下载
     * @return $this
     */
    public function start()
    {
        $size = $this->getFileSize();
        $file_name = $this->getFileName();
        header("Content-type: application/octet-stream;charset=utf8");
        header("Accept-Ranges: bytes");
        header("Accept-Length: $size");
        header("Content-Disposition: attachment; filename=".$file_name);
        ob_end_flush();
        return $this;
    }

    /**
     * 获取文件大小
     * @return int
     */
    public function getFileSize()
    {
        if (empty($this->file_size)) {
            $this->file_size = ob_get_length();
        }
        return $this->file_size;
    }

    /**
     * 设置下载文件名
     * @param $file_name
     * @return $this
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
        return $this;
    }

    /**
     * 获取文件名
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * 获取文件资源句柄
     * @return mixed
     */
    public function getFile()
    {
        if (empty($this->file)) {
            $this->file = ob_get_contents();
        }
        return $this->file;
    }

    /**
     * 把文件放置到输出流中
     * @param $file
     */
    protected function outputFile($file)
    {
        if (is_resource($file)) {
            file_put_contents('php://output', $file);
        }
        return;
    }
}