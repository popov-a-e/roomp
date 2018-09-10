<?php

namespace Roomp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Roomp\Http\Controllers\Controller;
use Roomp\Repositories\PromoCodeRepository;

class PromoCodeController extends Controller {
  private $repository;

  public function __construct(PromoCodeRepository $repository) {
    $this->repository = $repository;
  }

  public function all(Request $request) {
    $withDeactivated = (bool)$request->input('all');
    return $this->repository->all($withDeactivated);
  }

  public function get($id) {
    return $this->repository->get($id);
  }

  public function set(Request $request) {
    return $this->repository->set($request->all());
  }

  public function setForce(Request $request) {
    return $this->repository->set($request->all(), true);
  }

  public function activate($id) {
    $this->repository->setDeactivatedTo($id, false);
  }

  public function deactivate($id) {
    $this->repository->setDeactivatedTo($id, true);
  }

  public function delete($id) {
    $this->repository->delete($id);
  }
}
