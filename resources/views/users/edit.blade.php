@extends('layouts.app')

@section('title', 'Edit User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-secondary">
            <h3 class="mb-0">@yield('title')</h3>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="name">Name <strong style="color:red;">(*)</strong></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                   name="name" placeholder="For example, John Doe" autocomplete="off"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="username">Username</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                                   name="username" placeholder="For example, johndoe" autocomplete="off"
                                   value="{{ old('username', $user->username) }}">
                            @error('username')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="email">Email <strong style="color:red;">(*)</strong></label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" placeholder="For example, john@example.com" autocomplete="off"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="password">New password <small class="text-muted">(leave blank to keep the current one)</small></label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="3"
                                      class="form-control @error('bio') is-invalid @enderror"
                                      placeholder="Tell us something about this user">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-lg-2 col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Update</button>
                    </div>
                    <div class="col-lg-2 col-xs-4">
                        <a href="{{ route('users.index') }}" class="btn btn-danger btn-block btn-flat">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
