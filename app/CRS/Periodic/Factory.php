<?php

namespace Roomp\CRS\Periodic;

use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class Factory {
  protected $rows;
  protected $loaded = [];

  protected $params = [];

  private $period;

  public function __construct(Period $period) {
    $this->period = $period;
  }

  private function buildPeriod() {
    return collect(CarbonPeriod::start($this->period->from)->setEndDate($this->period->till)->toArray())
      ->map(function ($date) {
        return $date->toDateString();
      })->zipKey('date');
  }

  public function build() {
    return $this
      ->stub()
      ->factory()
      ->fillParams()
      ->fillLoadedData();
  }

  private function stub() {
    $this->rows = collect([]);
    $period = $this->buildPeriod();

    foreach ($this->period->rooms as $roomID) {
      $rows = $period->clone();

      foreach ($this->extractParams($roomID) as $key => $values) {
        $rows = $rows->distribute($key, $values);
      }

      $this->rows = $this->rows->merge($rows->fill('room_id', $roomID));
    }

    return $this;
  }

  private function factory() {
    $this->rows->transform(function ($row) {
      return $this->period->getModel()::factory(array_merge($row, $this->period->getParams()));
    });

    return $this;
  }

  private function fillValue($value) {
    $this->rows->transform(function ($el) use ($value) {
      return $el->setValue($value);
    });

    return $this;
  }

  private function getLoadedRow($row) {
    return $this->loaded->first(function ($loadedRow) use ($row) {
      return $loadedRow->is($row);
    });
  }

  private function extractParams($roomID): array {
    $params = $this->period->getParams();

    foreach ($this->period->getAttributes() as $attribute) {
      if (in_array($attribute, $params)) {
        $this->params[$attribute] = [$params[$attribute]];
        continue;
      }

      $keys = $this->loaded->where('room_id', $roomID)->keyBy($attribute)->keys()->toArray();

      if (count($keys) > 0) $this->params[$attribute] = $keys;
    }

    return $this->params;
  }

  private function fillParams() {
    $params = $this->period->getParams();

    $this->rows->transform(function ($row) use ($params) {
      foreach ($params as $param => $value) {
        $row->$param = $value;
      }

      return $row;
    });

    return $this;
  }

  private function fillLoadedData() {
    $this->rows->transform(function ($row) {
      return $this->getLoadedRow($row) ?: $row;
    });

    return $this;
  }

  public function load(Collection $rows) {
    $this->loaded = $rows->removeKeys('id', 'hotel_id', 'currency');

    return $this;
  }

  public function fill($value) {
    return $this->fillValue($value);
  }

  public function get() {
    return $this->collect()->toArray();
  }

  public function collect() {
    return $this->rows;
  }
}