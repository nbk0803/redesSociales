@extends('layouts.app')

@section('title', 'Usuarios')

@section('breadcrumb')
    <li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de usuarios</h3>
            <div class="card-tools">
                <a href="{{ route('reportes.usuarios.pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('reportes.usuarios.excel') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus mr-1"></i> Nuevo usuario
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            @if ($users->isEmpty())
                <p class="p-3 mb-0 text-muted">Aún no hay usuarios registrados.</p>
            @else
                <div class="table-responsive">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Grupos</th>
                            <th>Registrado</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 160px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <img src="{{ $user->photo_url }}"
                                         class="img-circle img-size-32 mr-2" alt="{{ $user->name }}" style="object-fit: cover;">
                                    {{ $user->name }}
                                </td>
                                <td>{{ $user->username ?? '—' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse ($user->memberGroups as $group)
                                        <span class="badge badge-info">{{ $group->name }}</span>
                                    @empty
                                        <span class="text-muted">Sin grupos</span>
                                    @endforelse
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <input data-type="user" data-id="{{ $user->id }}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ $user->status === 'active' ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline delete-form"
                                          data-name="{{ $user->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @endif
        </div>

        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
@endsection
