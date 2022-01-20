<?php

namespace Calendar\Model;

/**
 * Model for the LeapYearController.
 */
class LeapYear {

  /**
   * @param int|NULL $year
   *
   * @return bool
   */
  public function isLeapYear(int $year = null): bool {
    if (null === $year) {
      $year = date('Y');
    }

    return $year % 400 === 0 || ($year % 4 === 0 && $year % 100 !== 0);
  }

}