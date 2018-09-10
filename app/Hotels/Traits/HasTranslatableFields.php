<?php

namespace Roomp\Hotels\Traits;

trait HasTranslatableFields {
  public function getNameAttribute() {
    return $this->getAttributes()['name'] ?? $this->getAttribute(app()->getLocale());
  }

  public function __get($key) {
    if (in_array($key, $this->translatable ?? [])) return $this->getAttribute($key . '_' . app()->getLocale());

    return parent::__get($key);
  }
}