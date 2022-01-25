<?php

namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the leap_year route.
 */
class LeapYearController {

  /**
   * @param $year
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function index($year): Response {

    $leapYear = new LeapYear();

    if ($leapYear->isLeapYear($year)) {
      return new Response('<p>This is a leap year!</p>');
    }
    return new Response('<p>This is not leap year.</p>');
  }

}