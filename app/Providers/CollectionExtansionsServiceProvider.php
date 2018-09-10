<?php

namespace Roomp\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\HigherOrderCollectionProxy;
use Illuminate\Support\ServiceProvider;
use Roomp\CRS\Periodic\Models\Model;

class CollectionExtansionsServiceProvider extends ServiceProvider {
  public function boot() {

    Collection::macro('rejectEmpty', function() {
      return $this->filter(function($value) {
        return $value !== null;
      });
    });

    Collection::macro('toDateInterval', function() {
      $prev = null;

      return $this->reduce(function($agg, Model $el) use (&$prev) {
        if ($prev !== null && $prev->getValue() === $el->getValue()) {
          $agg[count($agg) - 1]['till'] = $el->date;
        } else {
          $agg[] = array_merge([
            'from' => $el->date,
            'till' => $el->date
          ], collect($el)->except('date')->toArray());
        }

        $prev = $el;

        return $agg;
      });
    });

    Collection::macro('collapseTimes', function($n) {
      if ($n <= 0) return $this;
      return $this->collapse()->collapseTimes($n - 1);
    });

    Collection::macro('proxyMap', function(\Closure $closure) {
      return new HigherOrderCollectionProxy($this->map($closure), 'map');
    });

    Collection::proxy('proxyMap');

    Collection::macro('proxyFilter', function(\Closure $closure) {
      return new HigherOrderCollectionProxy($this->filter($closure), 'filter');
    });

    Collection::proxy('proxyFilter');

    Collection::macro('fill', function(string $key, $value) {
      return $this->map(function($el) use ($key, $value) {
        $el[$key] = $value;

        return $el;
      });
    });

    Collection::macro('zipKey', function(string $key) {
      return $this->map(function($value) use ($key) {
        return [$key => $value];
      });
    });

    Collection::macro('zipArray', function(string $key, array $arr) {
      return $this->map(function($value, $index) use ($key, $arr) {
        $value[$key] = $arr[$index] ?? null;

        return $value;
      });
    });

    Collection::macro('removeKeys', function(...$keys) {
      if (is_array($keys[0])) $keys = $keys[0];

      return $this->map(function($value) use ($keys) {
        foreach ($keys as $key) {
          if (is_array($value)) unset($value[$key]);
          else unset($value->$key);
        }

        return $value;
      });
    });

    Collection::macro('distribute', function($key, array $values) {
      $data = $this->clone();
      $result = collect([]);

      foreach ($values as $value) {
        $result = $result->merge($data->fill($key, $value));
      }

      return $result;
    });

    Collection::macro('clone', function() {
      return $this->mapWithKeys(function($value, $key) {
        return [$key => $value];
      });
    });
  }

  public function register() {
  }
}
