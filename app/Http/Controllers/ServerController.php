<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use Log;
use Flash;
use App\User;
use DigitalOcean;

class ServerController extends Controller
{

    /**
     * Gets all images and droplets from DigitalOcean API and displays only
     * those prefixed with $prefix
     * @return index view
     */
    public function index(Request $request) {

        $params = array();
        if(Auth::check()) {
            $params['username'] = Auth::user()->name;
        }
        $allImages = array();
        $allDroplets = array();
        try {
            $allImages = DigitalOcean::image()->getAll(['type' => 'snapshot', 'private' => true]);
            $allDroplets = DigitalOcean::droplet()->getAll();
        } catch(\Exception $e) {
            Flash::error('Error: ' . $e->getMessage());
        }

        $images = array();
        foreach($allImages as $image) {
          if (strpos($image->name, 'servman-') === 0) {
            array_push($images, $image);
          }
        }

        $droplets = array();
        foreach($allDroplets as $droplet) {
          if (strpos($droplet->name, 'servman-') === 0) {
            array_push($droplets, $droplet);
          }
        }

        $params['images'] = $images;
        $params['droplets'] = $droplets;


        return view('servman', $params);
    }

    /**
     * Initiate droplet from image provided by $imageID.
     * @param $imageID The ID of the image
     * @return previous view
     */
    public function start($imageID) {
        // Logging action
        $user = Auth::user();
        Log::info('User ' . $user->name . ' initiated droplet creation '
            . 'from image ' . $imageID);

        // Retrieve image from $imageID
        try {
            $image = DigitalOcean::image()->getById($imageID);
        } catch(\Exception $e) {
            Flash::error('Error retrieving image from ID ' . $imageID . ': ' . $e->getMessage());
            Log::error('Error retrieving image from ID ' . $imageID . ': ' . $e->getMessage());
        }

        // Create new droplet from image
        try {
            $droplet = DigitalOcean::droplet()->create($image->name, 'fra1', '1gb', $image->id);
        } catch(\Exception $e) {
            Flash::error('Error creating droplet: ' . $e->getMessage());
            Log::error('Error creating droplet: ' . $e->getMessage());
        }

        $message = 'Successfully initiated creation of server '
            . $droplet->id . ' from image ' . $image->name . ".\n"
            . 'Please wait for the server status to switch from "new" '
            . 'to "active".';
        Flash::success( $message );
        return redirect()->back();
    }

    /**
     * Reboot droplet by ID via the DigitalOcean API
     * @param $dropletId The Droplets ID
     * @return previous view
     */
    public function restart($dropletId) {

        // Logging action
        $user = Auth::user();
        Log::info('User ' . $user->name . ' rebooted Droplet with ID ' . $dropletId);

        // Reboot droplet by $dropletId
        try {
            $droplet = DigitalOcean::droplet()->reboot($dropletId);

            $message = 'Initiated reboot of server ' . $droplet->name . '('
                . $droplet->id . '). Please wait for the status to switch back '
                . 'to "active".';
            Flash::success( $message );
        } catch(\Exception $e) {
            Flash::error('Error rebooting server ' . $dropletId . ': ' . $e->getMessage());
            Log::error('Error rebooting droplet ' . $dropletId . ': ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Destroy droplet by ID via the DigitalOcean API
     * @param $dropletId The Droplets ID
     * @return previous view
     */
    public function destroy($dropletId) {

        // Logging action
        $user = Auth::user();
        Log::info('User ' . $user->name . ' destroys Droplet with ID ' . $dropletId);

        // Destroy droplet by $dropletId
        try {
            $droplet = DigitalOcean::droplet()->delete($dropletId);
            $message = 'Initiated destroy of server with ID ' . $dropletId
                . '. Please wait a few moments until the server is destroyed.';
            Flash::success( $message );
        } catch(\Exception $e) {
            Flash::error('Error destroying server ' . $dropletId . ': ' . $e->getMessage());
            Log::error('Error destroying droplet ' . $dropletId . ': ' . $e->getMessage());
        }

        return redirect()->back();
    }

}


