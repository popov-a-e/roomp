<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 23.07.2018
 * Time: 16:13
 */

namespace Roomp\CRS\Periodic;

use Illuminate\Support\Facades\DB;
use Roomp\CRS\Periodic\Models\Availability as AvailabilityModel;
use Roomp\CRS\Periodic\Models\Model;

class Query {
  private $query;

  private $period;
  private $model;

  public $orderBy = [];

  public function __construct(Model $model, Period $period) {
    $this->period = $period;
    $this->model = $model;
  }

  public function get() {
    $this->process();

    return $this->query->get();
  }

  public function cursor() {
    $this->process();

    return $this->query->cursor();
  }

  public function replaceWith(array $rows) {
    $this->process();

    $this->query->delete();

    $this->prepare()->insert($rows);
  }

  public function setOrderBy(array $columns = []) {
    $this->orderBy = $columns;
  }

  private function process() {
    $this->prepare();
    $this->applyFilters();
    $this->sort();
  }

  private function prepare() {
    $this->query = $this->model->query();

    return $this->query;
  }

  private function applyFilters() {
    $this->filterDates();
    $this->filterRooms();
    $this->filterParams();
  }

  private function filterDates() {
    $from = $this->period->from;
    $till = $this->period->till;

    $this->query->whereBetween('date', [$from, $till]);
  }

  private function filterRooms() {
    $this->query->whereIn('room_id', $this->period->rooms);
  }

  private function filterParams() {
    $params = $this->period->getParams();

    foreach ($params as $key => $value) {
      $this->query->where($key, $value);
    }
  }

  private function sort() {
    foreach (array_reverse($this->orderBy) as $col) {
      $this->query->orderBy($col);
    }

    return $this;
  }
}