<?php

namespace Roomp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Roomp\Http\Controllers\Controller;
use Roomp\Repositories\CommonRepository;

class MetaController extends Controller {
  private $repository;

  public function __construct(CommonRepository $repository) {
    $this->repository = $repository;
  }

  public function getMeta(Request $request) {
    $resource = ((object)$request->all());

    return $this->repository->getNameByResource($resource);
  }
}
