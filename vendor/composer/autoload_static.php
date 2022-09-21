<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcd0170752a83b5d65c74e9d2fc04bd7e
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'GravityWP\\LicenseHandler\\' => 25,
        ),
        'A' => 
        array (
            'Appsero\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'GravityWP\\LicenseHandler\\' => 
        array (
            0 => __DIR__ . '/..' . '/gravitywp/license-handler/src',
        ),
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitcd0170752a83b5d65c74e9d2fc04bd7e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcd0170752a83b5d65c74e9d2fc04bd7e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitcd0170752a83b5d65c74e9d2fc04bd7e::$classMap;

        }, null, ClassLoader::class);
    }
}
