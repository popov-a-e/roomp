<?php

namespace Roomp\Http\Resources\Hotels;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelVerboseResource extends JsonResource {
  private $hotelFields = [
    'id',
    'address_ru',
    'reception_email',
    'reception_phone',
    'regular_name',
    'channel_id',
    'photos',
    'photos_hub',
    'description_ru',
    'lat', 'lng',
    'hotelier_id',
    'status',
    'disabled'
  ];

  private const CM_MAX_STANDBY = 47; //Max time in hours, after that the channel manager connection is considered broken

  private function getHotelFields() {
    return $this->resource->only($this->hotelFields);
  }

  private function getCityData() {
    return [
      'city' => $this->city->ru ?? 'Не задано'
    ];
  }

  private function isAtPreLaunchStage() {
    return $this->status === 'signed';
  }

  private function getHotelData() {
    return array_merge($this->getHotelFields(), [
      'name' => $this->ru,
      'comment' => $this->lastLog->message ?? '',
      'fund' => $this->rooms->sum('number'),
      'fullness' => $this->isAtPreLaunchStage() ? $this->getFullness() : null,
      'stage_id' => $this->lead->last_stage_id ?? null
    ]);
  }

  private function getChannelManagerData() {
    return [
      'channel_manager' => $this->channel->name ?? 'Нет',
      'channel_manager_active' => Carbon::parse($this->hotelier->last_activity ?? '1970-01-01')->diffInHours(Carbon::now()) < self::CM_MAX_STANDBY
    ];
  }

  private function getConnectionData() {
    return [
      'bcom_active' => $this->bookingConnection->last_active ?? null,
      'bcom_exists' => $this->bookingConnection->channel_confirmed ?? false,
    ];
  }

  private function getOfferData() {
    return [
      'offer_accepted' => !!($this->offer->accept_date ?? false) ? 'Принята' : 'Не принята'
    ];
  }

  private function getFullness() {
    $rooms = $this->rooms;

    $hasRooms = $rooms->count() > 0;
    $hasAmenities = $this->amenities->count() > 0;
    $hasDescription = !!$this->description_ru;
    $hasCoordinates = $this->lat && $this->lng;
    $hasPhotos = !!$this->photos;
    $hasPhotosHub = !!$this->photos_hub;
    $hasRoomAmenities = $hasRooms && $rooms->every->hasAmenities();
    $hasRoomPhotos = $hasRooms && $rooms->every->hasPhotos();

    return [
      'Удобства в отеле' => $hasAmenities,
      'Описание' => $hasDescription,
      'Координаты' => $hasCoordinates,
      'Фото' => $hasPhotos,
      'Сортировка фото' => $hasPhotosHub,
      'Комнаты' => $hasRooms,
      'Удобства в комнатах' => $hasRoomAmenities,
      'Фото в комнатах' => $hasRoomPhotos
    ];
  }

  public function toArray($request) {
    return array_merge(
      $this->getHotelData(),
      $this->getChannelManagerData(),
      $this->getConnectionData(),
      $this->getOfferData(),
      $this->getCityData()
    );
  }
}
