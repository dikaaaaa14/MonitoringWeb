@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add New Station</h5>
                    <a href="{{ route('station.add') }}" class="btn btn-sm btn-secondary">Back to Stations</a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('station.store') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Station Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="location" class="col-md-4 col-form-label text-md-right">Location</label>
                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control @error('location') is-invalid @enderror" name="location" value="{{ old('location') }}" required>
                                @error('location')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="channel_id" class="col-md-4 col-form-label text-md-right">ThingSpeak Channel ID</label>
                            <div class="col-md-6">
                                <input id="channel_id" type="text" class="form-control @error('channel_id') is-invalid @enderror" name="channel_id" value="{{ old('2954806') }}" required>
                                @error('channel_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    The Channel ID from your ThingSpeak account
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="api_key" class="col-md-4 col-form-label text-md-right">ThingSpeak API Key</label>
                            <div class="col-md-6">
                                <input id="api_key" type="text" class="form-control @error('api_key') is-invalid @enderror" name="api_key" value="{{ old('SFWJ5UAULE9O6S0L') }}" required>
                                @error('api_key')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    The Read API Key from your ThingSpeak channel
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Field Mapping</label>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-2">ThingSpeak field mapping:</p>
                                        <ul class="list-unstyled mb-0">
                                            <li>Field 1 - Temperature</li>
                                            <li>Field 2 - Humidity</li>
                                            <li>Field 3 - pH Level</li>
                                            <li>Field 4 - Gas Level</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Add Station
                                </button>
                                <a href="{{ route('station.list') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">How to Find ThingSpeak Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Step 1: Log in to ThingSpeak</h6>
                        <p>Go to <a href="https://thingspeak.com" target="_blank">thingspeak.com</a> and log in to your account.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Step 2: Find Your Channel</h6>
                        <p>From the My Channels page, select the channel you want to connect.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Step 3: Get Channel ID</h6>
                        <p>The Channel ID is displayed on the channel page or in the URL.</p>
                        <p class="text-muted"><small>Example URL: https://thingspeak.com/channels/<strong>1234567</strong>/</small></p>
                    </div>
                    
                    <div>
                        <h6>Step 4: Get API Key</h6>
                        <p>Click on the "API Keys" tab and copy the "Read API Key".</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection