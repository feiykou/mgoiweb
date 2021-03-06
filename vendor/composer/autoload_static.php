<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1202bc0000968e7425c14c2b30069d96
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\composer\\' => 15,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
        'W' => 
        array (
            'WangYu\\' => 7,
        ),
        'L' =>
        array (
            'LinCmsTp\\' => 9,
            'LinCmsTp5\\admin\\' => 16,
            'LinCmsTp5\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application',
        ),
        'WangYu\\' => 
        array (
            0 => __DIR__ . '/..' . '/wangyu/reflex-core/src',
        ),
        'LinCmsTp\\' => 
        array (
            0 => __DIR__ . '/..' . '/lin-cms-tp/validate-core/src',
            1 => __DIR__ . '/..' . '/lin-cms-tp/utils-core/src',
        ),
        'LinCmsTp5\\admin\\' =>
        array (
            0 => __DIR__ . '/..' . '/lin-cms-tp5/admin-core/src',
        ),
        'LinCmsTp5\\' =>
        array (
            0 => __DIR__ . '/..' . '/lin-cms-tp5/base-core/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1202bc0000968e7425c14c2b30069d96::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1202bc0000968e7425c14c2b30069d96::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
