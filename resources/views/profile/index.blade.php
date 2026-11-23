@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="mb-4">Mi perfil</h2>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 text-center">
                            <img id="photoPreview" src="{{ $user->photo_url }}"
                                 class="img-circle elevation-2" style="width: 96px; height: 96px; object-fit: cover;" alt="Foto de perfil">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biografía</label>
                            <textarea name="bio" rows="4" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto de perfil</label>
                            <input type="file" id="photo" name="photo"
                                   class="form-control-file custom-file" accept="image/*"
                                   onchange="document.getElementById('photoPreview').src = window.URL.createObjectURL(this.files[0])">
                            @error('photo')
                                <span class="text-danger d-block">{{ $message }}</span>
                            @enderror
                            @if($user->photo)
                                <small class="text-muted d-block mt-1">Archivo actual: {{ $user->photo }}</small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
