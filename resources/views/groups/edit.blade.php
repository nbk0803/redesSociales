@extends('layouts.app')

@section('title', 'Edit Group')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Groups</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-secondary">
            <h3 class="mb-0">@yield('title')</h3>
        </div>

        <form method="POST" action="{{ route('groups.update', $group) }}">
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
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="name">Name <strong style="color:red;">(*)</strong></label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                   name="name" placeholder="For example, Laravel Developers" autocomplete="off"
                                   value="{{ old('name', $group->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="description">Description <strong style="color:red;">(*)</strong></label>
                            <textarea id="description" name="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="What is this group about?" required>{{ old('description', $group->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="privacy">Privacy <strong style="color:red;">(*)</strong></label>
                            <select id="privacy" name="privacy" class="form-control @error('privacy') is-invalid @enderror" required>
                                <option value="public" {{ old('privacy', $group->privacy) === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ old('privacy', $group->privacy) === 'private' ? 'selected' : '' }}>Private</option>
                                <option value="secret" {{ old('privacy', $group->privacy) === 'secret' ? 'selected' : '' }}>Secret</option>
                            </select>
                            @error('privacy')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="status">Status <strong style="color:red;">(*)</strong></label>
                            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $group->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $group->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
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
                        <a href="{{ route('groups.index') }}" class="btn btn-danger btn-block btn-flat">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
