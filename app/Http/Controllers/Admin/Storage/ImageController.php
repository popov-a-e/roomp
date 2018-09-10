<?php

namespace Roomp\Http\Controllers\Admin\Storage;

use Roomp\Events\ImageUploaded;
use Roomp\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller {
  public function uploadPhoto(Request $request) {
    $name = explode('/',$request->file('images.0')->storePublicly('hotels/photos/raw'));

    $name = $name[count($name) - 1];

    event(new ImageUploaded($name));

    return $name;
  }


  public function uploadMap(Request $request) {
    $name = explode('/',$request->file('images.0')->storePublicly('hotels/maps'));

    $name = $name[count($name) - 1];

    return $name;
  }
}