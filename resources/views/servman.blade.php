<?php

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
                <td><a class="btn btn-success" href="{{ route("server.start", $image->id) }}" role="button">
                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a></td>
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
                  <a class="btn btn-warning" href="{{ route("server.restart", $droplet->id) }}" role="button">Restart</a>
                  <a class="btn btn-danger delete" href="{{ route("server.destroy", $droplet->id) }}" role="button">Destroy</a>
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


