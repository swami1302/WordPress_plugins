<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit32d304ef0061bae499b66b6f13d88ead
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Fbt\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Fbt\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit32d304ef0061bae499b66b6f13d88ead::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit32d304ef0061bae499b66b6f13d88ead::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit32d304ef0061bae499b66b6f13d88ead::$classMap;

        }, null, ClassLoader::class);
    }
}
