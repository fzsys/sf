<?php

use App\Core;
use Calendar\Controller\LeapYearController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @covers App\Core
 */
Class CoreTest extends TestCase {

  /**
   * @return void
   */
  public function testNotFoundHandling() {
    $core = $this->getCoreForException(new ResourceNotFoundException());

    $response = $core->handle(new Request());

    $this->assertEquals(404, $response->getStatusCode());
  }

  public function testErrorHandling() {
    $core = $this->getCoreForException(new \RuntimeException());

    $response = $core->handle(new Request());

    $this->assertEquals(500, $response->getStatusCode());
  }


  public function testControllerResponse() {
    $matcher = $this->createMock(UrlMatcherInterface::class);

    $matcher
      ->expects($this->once())
      ->method('match')
      ->will($this->returnValue([
        '_route' => 'is_leap_year/{year}',
        'year' => '2000',
        '_controller' => [new LeapYearController(), 'index'],
      ]));

    $matcher
      ->expects($this->once())
      ->method('getContext')
      ->will($this->returnValue($this->createMock(RequestContext::class)));

    $controllerResolver = new ControllerResolver();
    $argumentResolver = new ArgumentResolver();

    $core = new Core($matcher, $controllerResolver, $argumentResolver);

    $response = $core->handle(new Request());

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertStringContainsString('This is a leap year!', $response->getContent());
  }

  /**
   * @param \Exception $exception
   *
   * @return \App\Core
   */
  private function getCoreForException(\Exception $exception): Core {
    $matcher = $this->createMock(UrlMatcherInterface::class);

    $matcher
      ->expects($this->once())
      ->method('match')
      ->will($this->throwException($exception));

    $matcher
      ->expects($this->once())
      ->method('getContext')
      ->will($this->returnValue(
        $this->createMock(RequestContext::class))
      );

    $controllerResolver = $this->createMock(ControllerResolverInterface::class);
    $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

    return new Core($matcher, $controllerResolver, $argumentResolver);
  }


}