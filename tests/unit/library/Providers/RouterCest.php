<?php

namespace Niden\Tests\unit\library\Providers;

use Niden\Logger;
use Niden\Providers\ConfigProvider;
use Niden\Providers\LoggerProvider;
use Niden\Providers\RouterProvider;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\RouterInterface;
use \UnitTester;

class RouterCest
{
    /**
     * @param UnitTester $I
     */
    public function checkRegistration(UnitTester $I)
    {
        $diContainer = new FactoryDefault();
        $application = new Micro($diContainer);
        $diContainer->setShared('application', $application);
        $provider    = new ConfigProvider();
        $provider->register($diContainer);
        $provider    = new RouterProvider();
        $provider->register($diContainer);

        /** @var RouterInterface $router */
        $router = $application->getRouter();
        $routes = $router->getRoutes();
        $expected = [
            ['POST', '/login'],
            ['POST', '/companies'],
            ['GET',  '/cpompanies'],
            ['GET',  '/cpompanies/{typeId:[0-9]+}'],
            ['GET',  '/individualtypes'],
            ['GET',  '/individualtypes/{typeId:[0-9]+}'],
            ['GET',  '/producttypes'],
            ['GET',  '/producttypes/{typeId:[0-9]+}'],
            ['GET',  '/users'],
            ['GET',  '/users/{userId:[0-9]+}'],
        ];

        $I->assertEquals(10, count($routes));
        foreach ($routes as $index => $route) {
            $I->assertEquals($expected[$index][0], $route->getHttpMethods());
            $I->assertEquals($expected[$index][1], $route->getPattern());
        }
   }
}
