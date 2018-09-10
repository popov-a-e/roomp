<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Illuminate\Http\Request;
use Roomp\Hotels\Hotel;
use Roomp\Hotels\Organizations\ContactPerson;
use Roomp\Http\Controllers\Controller;

class ContactFaceController extends Controller {
  private $hotels;

  public function __construct(ContactPerson $hotel) {
    $this->hotels = $hotel;
  }

  public function index(Hotel $hotel) {
    return $hotel->contacts;
  }

  public function store(Hotel $hotel, Request $request) {
    return $hotel->organization->contacts()->create($request->toArray());
  }

  public function update(Hotel $hotel, Request $request, ContactPerson $contact) {
    $contact->fill($request->toArray())->save();

    return $contact;
  }

  public function destroy(Hotel $hotel, ContactPerson $contact) {
    $contact->delete();
  }
}
