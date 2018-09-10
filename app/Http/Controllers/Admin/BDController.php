<?php

namespace Roomp\Http\Controllers\Admin;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Roomp\Http\Controllers\Controller;
use Roomp\Repositories\BDRepository;


class BDController extends Controller {
  use ValidatesRequests;

  private $repository;

  public function __construct(BDRepository $repository) {
    $this->repository = $repository;
  }

  public function all() {
    return $this->repository->all();
  }

  public function find($id) {
    return $this->repository->find($id);
  }

  public function update(Request $request) {
    $this->validate($request, [
      'name' => 'required',
      'email' => [
        'email',
        'required',
        Rule::unique('business_developers')->ignore($request->input('id'))
      ],
      'amo_id' => 'numeric'
    ]);

    return $this->repository->update($request->only('name', 'phone', 'email', 'amo_id'), $request->input('id'), $request->input('password'));
  }
}
