<?php

namespace Roomp\CRS\Periodic;

trait ChecksIfOpen {
  private $restrictions;

  public function open() {
    $this->validator->validatePresence($this->getValidatable());

    $this->addDepartureDate();

    return !(
      $this->closed() ?:
      $this->closedToDepatrure() ?:
      $this->closedToArrival() ?:
      $this->belowMinstay() ?:
      $this->exceedsMaxStay() ?:
      $this->belowMinstayOnArrival()
    );
  }

  private function addDepartureDate() {
    $this->till->addDay();
  }

  private function nights() {
    return $this->from->diffInDays($this->till);
  }

  private function restrictions($withDepatrureDate = false) {
    return $this->restrictions ?
      $this->restrictions->where('date', '<', $withDepatrureDate ? $this->till->copy()->addDay()->toDateString() : $this->till->toDateString()) :
      $this->restrictions = $this->collect();
  }

  private function closed() {
    return !$this->restrictions()->every(function($restriction) {
      return !$restriction->closed;
    });
  }

  private function closedToArrival() {
    return $this->restrictions()->pluck( 'closed_to_arrival', 'date')[$this->from->toDateString()];
  }

  private function closedToDepatrure() {
    return $this->restrictions(true)->pluck('closed_to_departure', 'date')[$this->till->toDateString()];
  }

  private function belowMinstay() {
    return !$this->restrictions()->every(function($restriction) {
      return $restriction->minstay <= 1 || $restriction->minstay <= $this->nights();
    });
  }

  private function belowMinstayOnArrival() {
    return !$this->restrictions()->every(function($restriction) {
      return $restriction->minstay_on_arrival <= 1 || $restriction->minstay_on_arrival <= $this->nights();
    });
  }

  private function exceedsMaxStay() {
    return !$this->restrictions()->every(function($restriction) {
      return $restriction->maxstay <= 1 || $restriction->maxstay >= $this->nights();
    });
  }
}