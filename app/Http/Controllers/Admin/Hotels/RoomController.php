<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Illuminate\Http\Request;
use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Rooms\AdminShort;
use Roomp\Hotels\Rooms\Room;
use Roomp\Http\Resources\Rooms\Admin as AdminRoom;

class RoomController extends Controller {
  public function index(Hotel $hotel) {
    return AdminShort::collection($hotel->rooms()->with('allotments', 'amenities')->get());
  }

  public function store(Hotel $hotel, Request $request) {
    $room = $hotel->rooms()->create($request->toArray());

    $room->amenities()->attach($request->amenities ?? []);
    $room->allotments()->attach($request->allotments ?? []);

    return $room;
  }

  public function show(Hotel $hotel, Room $room) {
    return new AdminRoom($room);
  }

  public function update(Request $request, Hotel $hotel, Room $room) {
    $room->fill($request->toArray())->save();

    if ($request->amenities) {
      $room->amenities()->detach();
      $room->amenities()->attach($request->amenities);
    }

    if ($request->allotments) {
      $room->allotments()->detach();
      $room->allotments()->attach($request->allotments);
    }

    return $room;
  }

  public function destroy(Hotel $hotel, Room $room) {
    $reservationCodes = $room->occupancies()->with('reservation')->get()->map->reservation->map->code;

    if ($reservationCodes->count() > 0) return response($reservationCodes->implode(', '), 400);

    $room->delete();

    return response();
  }
}
