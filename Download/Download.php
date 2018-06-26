<?php
namespace Download;
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
 *     $download->setFile('/file_path')->setDownloadFileName('text.txt')->start();
 *
 *     $file = file_get_contents('./img/test.jpg');
 *     $download->setFile($file)->setDownloadFileName('text.txt')->start();
 *  如果是非文件类型
 *     $download = new Download();
 *     echo 123;
 *     $download->setDownloadFileName('test.txt')->start();
 * Class Download
 */
class Download
{
    protected $file;
    protected $file_name;
    protected $file_size;

    public function __construct()
    {
        ob_start(); //打开输出缓冲控制
    }

    /**
     * 设置文件绝对路径或者字符串
     * @param $file string
     * @return $this
     */
    public function setFile($file)
    {
        if (file_exists($file) && ! is_dir($file)) {
            $this->setDownloadFileName($file);
            $this->file = file_get_contents($file);
        } elseif (is_string($file)) {
            $this->file = $file;
        }
        $this->outputFile($this->file);
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
     * 获取下载文件大小
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
    public function setDownloadFileName($file_name)
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
        return pathinfo($this->file_name, PATHINFO_BASENAME);
    }

    /**
     * 获取文件内容
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
     * 把文件输出到输出流中
     * @param $file
     */
    protected function outputFile($file)
    {
        if (is_string($file)) {
            ob_clean(); //清空缓冲区
            file_put_contents('php://output', $file);
        }
        return;
    }
}
