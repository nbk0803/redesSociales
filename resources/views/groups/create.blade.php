@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="mb-4">Crear grupo</h2>

                    <form method="POST" action="{{ route('groups.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nombre del grupo</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Privacidad</label>
                            <select name="privacy" class="form-select" required>
                                <option value="public">Público</option>
                                <option value="private">Privado</option>
                                <option value="secret">Secreto</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Guardar grupo</button>
                            <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
