<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class                    => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class                => ['dev' => true, 'test' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class                              => ['all' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class           => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class                     => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class         => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class                      => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class     => ['all' => true],
    Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle::class               => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class                          => ['all' => true],
    Snc\RedisBundle\SncRedisBundle::class                                    => ['all' => true],
    ApiPlatform\Core\Bridge\Symfony\Bundle\ApiPlatformBundle::class          => ['all' => true],
    Nelmio\CorsBundle\NelmioCorsBundle::class                                => ['all' => true],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => ['all' => true],
    Liuggio\StatsDClientBundle\LiuggioStatsDClientBundle::class              => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class                        => ['all' => true],
];
