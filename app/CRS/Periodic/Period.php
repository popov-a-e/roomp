<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 19.07.2018
 * Time: 15:55
 */

namespace Roomp\CRS\Periodic;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Roomp\CRS\Periodic\Models\Model;

abstract class Period {
  use HasPluckedForm, HasGroupedForm;

  protected $modelClass;

  protected $model;
  protected $validator;
  protected $query;
  protected $factory;

  public $from;
  public $till;
  public $rooms;

  private $restrictedRules = [
    'from' => 'required|date',
    'till' => 'required|date|after_or_equal:from',
    'rooms' => 'required|array',
    'rooms.*' => 'integer|min:1'
  ];

  protected $rules = [];

  protected $params = [];

  public function __construct() {
    $this->setupModel();
    $this->setupValidator();
    $this->setupQuery();
    $this->setupFactory();
  }

  protected function setupModel() {
    $this->model = app()->make($this->modelClass);
  }

  protected function setupValidator() {
    $this->validator = app()->make(Validator::class);
    $this->validator->setRules($this->getRules());
  }

  protected function setupQuery() {
    $this->query = app()->makeWith(Query::class, [
      'model' => $this->model,
      'period' => $this
    ]);

    $this->query->setOrderBy($this->model->getOrderedProperties());
  }

  protected function setupFactory() {
    $this->factory = app()->makeWith(Factory::class, [
      'period' => $this
    ]);
  }

  private function getRules() : array {
    return array_merge(
      $this->restrictedRules,
      $this->rules
    );
  }

  public function getModel() : Model {
    return $this->model;
  }

  public function getAttributes() : array {
    return $this->model->getProperties();
  }

  public function getParams(): array {
    return $this->params;
  }

  public function getValidatable(): array {
    $from = $this->from;
    $till = $this->till;
    $rooms = $this->rooms;

    return array_merge(compact('from', 'till', 'rooms'), $this->params);
  }

  public function collect() : Collection {
    $this->validator->validate($this->getValidatable());

    //Load the data from database to the factory
    //Factory would replace missing values with default (empty) ones

    return $this->factory
      ->load($this->query->get())
      ->build()
      ->collect();
  }

  public function get() : array {
    return $this->collect()->toArray();
  }

  public function set($value): array {
    // All parameters must be present if you try to put any change into database
    $this->validator->validatePresence($this->getValidatable());

    // Fetch the existing data from database
    $this->get();

    // Fill the rows with given values
    $data = $this->factory->build()->fill($value)->get();

    // Delete old rows from database and replace with generated
    $this->query->replaceWith($data);

    $this->event();

    // Return generated rows
    return $data;
  }

  private function event() {
    //event(new PeriodChanged($this));
  }

  private function from(Carbon $from) : Period {
    $this->from = $from->startOfDay();

    return $this;
  }

  private function till(Carbon $till) : Period {
    $this->till = $till->startOfDay();

    return $this;
  }

  private function atRoom(int $roomID) : Period {
    $this->atRooms([$roomID]);

    return $this;
  }

  private function atRooms(array $roomIDs) : Period {
    $this->rooms = $roomIDs;

    return $this;
  }

  protected function setParam(string $key, $value) : void {
    $this->params[$key] = $value;
  }

  public static function __callStatic($method, $parameters) {
    return (new static)->$method(...$parameters);
  }

  public function __call($method, $parameters) {
    if (!method_exists($this, $method)) throw new \Exception("Method $method does not exist");

    return $this->$method(...$parameters);
  }
}