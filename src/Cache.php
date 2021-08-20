<?php


namespace Shenhou\Dingtalk;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Cache extends DingTalk
{
    private static $type = 'file';
    private static $cache = null;

    public function __construct($config = [])
    {
        parent::__construct($config);
        self::buildCache();
    }

    public static function buildCache()
    {
        self::$cache = new FilesystemAdapter('', 0, "cache");
    }

    /**
     * 判断缓存是否存在
     * @param string $name 缓存名字
     * @return bool 是否存在
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function has($name)
    {
        if (self::$cache==null){
            self::buildCache();
        }
        return self::$cache->hasItem($name);
    }

    /**
     * 获取缓存值
     * @param string $name 缓存名称
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function get($name)
    {
        if (self::$cache==null){
            self::buildCache();
        }
        $data = self::$cache->getItem($name);
        return $data->get();
    }

    /**
     * 设置缓存
     * @param string $name 缓存名称
     * @param mixed $value 要设置的值
     * @param int $expire 缓存时长，单位秒
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function set($name, $value, $expire = 0)
    {
        if (self::$cache==null){
            self::buildCache();
        }
        $demoOne = self::$cache->getItem($name);
        $demoOne->set($value);
        if (!empty($expire)) {
            $demoOne->expiresAfter($expire);
        }
        self::$cache->save($demoOne);

    }
}