<?php

//require __DIR__ . '/vendor/autoload.php';

//use DigitalOceanV2\Adapter\GuzzleAdapter;
//use DigitalOceanV2\DigitalOceanV2;

//include_once 'config.php';

// create an adapter with DigitalOcean access token
// and initialize DigitalOceanV2 API object
//$adapter = new GuzzleAdapter($apikey);
//$digitalocean = new DigitalOceanV2($adapter);
//
//$userInfo = $digitalocean->account()->getUserInformation();
//
// Get all available images
//$allImages = $digitalocean->image()->getAll(['type' => 'snapshot', 'private' => true]);
$images = array();
foreach($allImages as $image) {
  if (strpos($image->name, 'servman-') === 0) {
    array_push($images, $image);
  }
}
//
// Get all manageabe running droplets
//$allDroplets = $digitalocean->droplet()->getAll();
$droplets = array();
foreach($allDroplets as $droplet) {
  array_push($droplets, $droplet);
}



?>

@extends('layouts.app')

@section('content')

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Server Management</h1>
        <p>This website can be used as a management console for game servers. Select one of the snapshots you want to create a server from, and get your gaming started.</p>
        <p>And as always: <b>Get your Godmode on!</b></p>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h3>Images</h3>
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Image Name</th>
                <th>Date Created</th>
                <th>Setup Server</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($images as $image)
              <tr>
                <td>{{ $image->id }}</td>
                <td>{{ str_replace('servman-', '', $image->name) }}</td>
                <td>{{ $image->createdAt }}</td>
                <td><a class="btn btn-success" href="#" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">

        <div class="col-md-12">
          <h3>Servers</h3>
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Server Name</th>
                <th>Server IP</th>
                <th>Server Status</th>
                <th>Date Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($droplets as $droplet)
              <tr>
                <td>{{ $droplet->id }}</td>
                <td>{{ str_replace('servman-', '', $droplet->name) }}</td>
                <td>{{ $droplet->networks[0]->ipAddress }}</td>
                <td>{{ $droplet->status }}</td>
                <td>{{ $droplet->createdAt }}</td>
                <td>
                  <a class="btn btn-warning" href="#" role="button">Restart</a>
                  <a class="btn btn-danger" href="#" role="button">Destroy</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; <a href="http://godmodex.de/">GodmodeX Germany</a></p>
      </footer>

    </div>

@endsection


