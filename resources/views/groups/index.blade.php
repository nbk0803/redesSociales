@extends('layouts.app')

@section('title', 'Grupos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Grupos</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de grupos</h3>
            <div class="card-tools">
                <a href="{{ route('reportes.grupos.pdf') }}" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('reportes.grupos.excel') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
                <a href="{{ route('groups.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nuevo grupo
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            @if ($groups->isEmpty())
                <p class="p-3 mb-0 text-muted">Aún no hay grupos registrados.</p>
            @else
                <div class="table-responsive">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Creador</th>
                            <th>Privacidad</th>
                            <th>Miembros</th>
                            <th>Estado</th>
                            <th class="text-center" style="width: 160px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groups as $group)
                            <tr>
                                <td>{{ $group->id }}</td>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->creator->name ?? '—' }}</td>
                                <td>
                                    @if ($group->privacy === 'public')
                                        <span class="badge badge-success">Público</span>
                                    @elseif ($group->privacy === 'private')
                                        <span class="badge badge-warning">Privado</span>
                                    @else
                                        <span class="badge badge-danger">Secreto</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-primary">{{ $group->members_count }}</span></td>
                                <td>
                                    <input data-type="group" data-id="{{ $group->id }}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="Inactive" {{ $group->status === 'active' ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    @php $isOwner = $group->creator_id === auth()->id(); @endphp
                                    <a href="{{ route('groups.show', $group) }}" class="btn btn-info btn-sm" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('groups.edit', $group->id) }}"
                                       class="btn btn-info btn-sm {{ $isOwner ? '' : 'disabled' }}"
                                       title="{{ $isOwner ? 'Edit' : 'Only the creator can edit' }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline delete-form"
                                          data-name="{{ $group->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="{{ $isOwner ? 'Eliminar' : 'Solo el creador puede eliminar' }}"
                                                {{ $isOwner ? '' : 'disabled' }}>
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
            {{ $groups->links() }}
        </div>
    </div>
@endsection
