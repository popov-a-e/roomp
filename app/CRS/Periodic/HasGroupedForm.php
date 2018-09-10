<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 25.07.2018
 * Time: 13:46
 */

namespace Roomp\CRS\Periodic;

trait HasGroupedForm {
  public function group($collect = false) {
    return $this->collect()->reduce(function(&$data, $row) use ($collect) {
      $nest = &$data;

      foreach ($this->getOrder() as $column) {
        if (!isset($nest[$row->$column])) $nest[$row->$column] = $collect ? collect([]) : [];

        $nest = &$nest[$row->$column];
      }

      $nest[$row->date] = $row->getValue();

      return $data;
    }, []);
  }

  protected function getOrder() {
    $order = $this->query->orderBy;

    array_shift($order);

    return $order;
  }
}