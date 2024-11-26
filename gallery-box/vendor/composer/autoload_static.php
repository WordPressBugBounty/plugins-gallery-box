<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita0289a3c75f92a1bd9b232672e84fe9d
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Appsero\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Appsero\\' => 
        array (
            0 => __DIR__ . '/..' . '/appsero/client/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita0289a3c75f92a1bd9b232672e84fe9d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita0289a3c75f92a1bd9b232672e84fe9d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita0289a3c75f92a1bd9b232672e84fe9d::$classMap;

        }, null, ClassLoader::class);
    }
}
