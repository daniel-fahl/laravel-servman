<?php

namespace App\Http\Controllers;

//use App\User;
use DigitalOcean;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{

    public function index() {
        $allImages = DigitalOcean::image()
            ->getAll(['type' => 'snapshot', 'private' => true]);
        $allDroplets = DigitalOcean::droplet()->getAll();

        return view('servman', [
            'allImages' => $allImages,
            'allDroplets' => $allDroplets
          ]);
    }

    public function start($imageID) {
        $image = DigitalOcean::image()->getById($imageID);
        $droplet = DigitalOcean::droplet()->create($image->name, 'fra1', '1gb', $image->id);
        return redirect()->back();
    }

    public function restart($dropletId) {
        $droplet = DigitalOcean::droplet()->reboot($dropletId);
        return redirect()->back();
    }

    public function destroy($dropletId) {
        $droplet = DigitalOcean::droplet()->delete($dropletId);
        return redirect()->back();
    }

}


