@extends('layouts.app')

@section('title', 'Membresías')

@section('breadcrumb')
    <li class="breadcrumb-item active">Membresías</li>
@endsection

@section('content')
    @php
        $roleLabels = [
            'owner' => 'Propietario',
            'admin' => 'Administrador',
            'moderator' => 'Moderador',
            'member' => 'Miembro',
        ];
    @endphp

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Membresías de grupos</h3>
            <div class="card-tools">
                <a href="{{ route('memberships.index') }}"
                   class="btn btn-sm {{ $status ? 'btn-default' : 'btn-primary' }}">
                    Todas ({{ $counts['all'] }})
                </a>
                <a href="{{ route('memberships.index', ['status' => 'active']) }}"
                   class="btn btn-sm {{ $status === 'active' ? 'btn-success' : 'btn-default' }}">
                    Activas ({{ $counts['active'] }})
                </a>
                <a href="{{ route('memberships.index', ['status' => 'inactive']) }}"
                   class="btn btn-sm {{ $status === 'inactive' ? 'btn-secondary' : 'btn-default' }}">
                    Inactivas ({{ $counts['inactive'] }})
                </a>
                <a href="{{ route('reportes.membresias.pdf', array_filter(['status' => $status])) }}"
                   class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('reportes.membresias.excel', array_filter(['status' => $status])) }}"
                   class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            @if ($memberships->isEmpty())
                <p class="p-3 mb-0 text-muted">No hay membresías para mostrar.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px">#</th>
                                <th>Usuario</th>
                                <th>Grupo</th>
                                <th style="width: 130px">Rol</th>
                                <th style="width: 110px">Estado</th>
                                <th>Registrado por</th>
                                <th style="width: 140px">Se unió</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($memberships as $membership)
                                <tr>
                                    <td>{{ $membership->id }}</td>
                                    <td>
                                        <strong>{{ $membership->user->name ?? 'Usuario' }}</strong>
                                        @if ($membership->user?->username)
                                            <span class="text-muted small">({{ $membership->user->username }})</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($membership->group)
                                            <a href="{{ route('groups.show', $membership->group) }}">
                                                {{ $membership->group->name }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $roleLabels[$membership->role] ?? $membership->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $membership->status === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $membership->status === 'active' ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>
                                    <td>{{ $membership->registeredBy->name ?? '—' }}</td>
                                    <td>
                                        {{ $membership->joined_at ? $membership->joined_at->format('d/m/Y H:i') : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="card-footer">
            {{ $memberships->links() }}
        </div>
    </div>
@endsection
