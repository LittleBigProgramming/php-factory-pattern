<?php

/**
 * Class Config
 */
class Config {
    protected $data = [
        'upload' => [
            'default' => 'ft',
            'services' => [
                's3' => [
                    'key' => '123',
                    'secret' => '456',
                ],
                'ftp' => [
                    'host' => 'abc',
                ],
            ],
        ],
    ];

    /**
     * @param $keys
     * @return array|mixed
     */
    public function get($keys) {
        $data = $this->data;
        $keys = explode('.', $keys);

        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                $data = $data[$key];
                continue;
            }
        }

        return $data;
    }
}

/**
 * Class Uploader
 */
class Uploader {

    protected $adapter;

    /**
     * Uploader constructor.
     * @param $adapter
     */
    public function __construct($adapter) {
        $this->adapter = $adapter;
    }

    /**
     * @param $file
     * @param $destination
     * @return mixed
     */
    public function upload($file, $destination) {
        return $this->adapter;
    }
}

/**
 * Class S3Adapter
 */
class S3Adapter {

}

/**
 * Class FTPAdapter
 */
class FTPAdapter {

}

/**
 * Class AdapterFactory
 */
class AdapterFactory {

    /**
     * @param $config
     * @return FTPAdapter|S3Adapter
     */
    public function make($config) {
        switch ($config->get('upload.default')) {
            case 's3':
                return new S3Adapter();
                break;
            case 'ftp':
                return new FTPAdapter();
                break;
        }
    }

}

/**
 * Class UploaderFactory
 */
class UploaderFactory {

    protected $adapter;

    /**
     * UploaderFactory constructor.
     * @param AdapterFactory $adapter
     */
    public function __construct(AdapterFactory $adapter) {
        $this->adapter = $adapter;
    }

    /**
     * @param Config $config
     * @return Uploader
     */
    public function make(Config $config) {
        return new Uploader($this->adapter->make($config));
    }
}

$config = new Config();

$factory = new UploaderFactory(new AdapterFactory);
$uploader =$factory->make($config);

var_dump($uploader->upload('file.txt', 'destination.txt'));