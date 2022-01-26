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
   * @return string
   */
  public function index($year): string {

    $leapYear = new LeapYear();

    if ($leapYear->isLeapYear($year)) {
      return '<p>This is a leap year!</p>';
    }
     else {
       return '<p>This is not leap year.</p>';
     }

  }

}