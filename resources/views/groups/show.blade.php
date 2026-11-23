@extends('layouts.app')

@section('title', 'Ver Datos Del Grupo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('groups.index') }}">Grupos</a></li>
    <li class="breadcrumb-item active">{{ $group->name }}</li>
@endsection

@section('content')
    @php
        $privacyLabels = [
            'public' => ['label' => 'Público', 'class' => 'badge-success'],
            'private' => ['label' => 'Privado', 'class' => 'badge-warning'],
            'secret' => ['label' => 'Secreto', 'class' => 'badge-danger'],
        ];

        $roleLabels = [
            'owner' => 'Propietario',
            'admin' => 'Administrador',
            'moderator' => 'Moderador',
            'member' => 'Miembro',
        ];
    @endphp

    <div class="card">
        <div class="card-header bg-secondary" style="font-size: 1.75rem;font-weight: 500; line-height: 1.2; margin-bottom: 0.5rem;">
            @yield('title')
        </div>
        <div class="card-body">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">Nombre del grupo</label>
                                <p>{{ $group->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">Descripción</label>
                                <p>{{ $group->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">Privacidad</label>
                                <p>
                                    <span class="badge {{ $privacyLabels[$group->privacy]['class'] ?? 'badge-secondary' }}">
                                        {{ $privacyLabels[$group->privacy]['label'] ?? ucfirst($group->privacy) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row align-items-center">
                <div class="col-lg-2 col-xs-4">
                    <a href="{{ route('groups.index') }}" class="btn btn-danger btn-block btn-flat">Atras</a>
                </div>

                @auth
                    <div class="col-lg-10 col-xs-8 text-right">
                        @if ($isMember)
                            @unless ($isOwner)
                                <form method="POST" action="{{ route('groups.leave', $group) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-flat">
                                        <i class="fas fa-sign-out-alt"></i> Salir del grupo
                                    </button>
                                </form>
                            @endunless
                        @else
                            <form method="POST" action="{{ route('groups.join', $group) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-flat">
                                    <i class="fas fa-user-plus"></i> Unirse
                                </button>
                            </form>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Miembros</h3>
            <div class="card-tools">
                <a href="{{ route('reportes.grupo.pdf', $group) }}" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('reportes.grupo.miembros.excel', $group) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Excel
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            @if ($group->members->isEmpty())
                <p class="p-3 mb-0 text-muted">Este grupo aún no tiene miembros.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th style="width: 160px">Rol</th>
                                <th style="width: 130px">Estado</th>
                                <th style="width: 170px">Se unió</th>
                                @if ($isOwner)
                                    <th class="text-center" style="width: 160px">Acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($group->members as $member)
                                @php
                                    $isCreator = $member->id === $group->creator_id;
                                    $memberRole = $member->pivot->role;
                                    $memberStatus = $member->pivot->status;
                                    $updateFormId = 'update-member-'.$member->id;
                                @endphp
                                <tr>
                                    <td>
                                        <img src="{{ $member->photo_url }}"
                                             class="img-circle img-size-32 mr-2" alt="{{ $member->name }}" style="object-fit: cover;">
                                        {{ $member->name }}
                                    </td>
                                    <td>
                                        @if ($isOwner && ! $isCreator)
                                            <select name="role" form="{{ $updateFormId }}" class="form-control form-control-sm">
                                                @foreach (['admin', 'moderator', 'member'] as $role)
                                                    <option value="{{ $role }}" {{ $memberRole === $role ? 'selected' : '' }}>
                                                        {{ $roleLabels[$role] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="badge badge-secondary">{{ $roleLabels[$memberRole] ?? $memberRole }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($isOwner && ! $isCreator)
                                            <select name="status" form="{{ $updateFormId }}" class="form-control form-control-sm">
                                                <option value="active" {{ $memberStatus === 'active' ? 'selected' : '' }}>Activo</option>
                                                <option value="inactive" {{ $memberStatus === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                            </select>
                                        @else
                                            <span class="badge {{ $memberStatus === 'active' ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $memberStatus === 'active' ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $member->pivot->joined_at ? \Illuminate\Support\Carbon::parse($member->pivot->joined_at)->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    @if ($isOwner)
                                        <td class="text-center">
                                            @if ($isCreator)
                                                <span class="text-muted small">Propietario</span>
                                            @else
                                                <form id="{{ $updateFormId }}" action="{{ route('groups.members.update', [$group, $member]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-primary btn-sm" title="Guardar cambios">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('groups.members.destroy', [$group, $member]) }}" method="POST" class="d-inline delete-form"
                                                      data-name="{{ $member->name }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Quitar del grupo">
                                                        <i class="fas fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @if ($isOwner)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Agregar miembro</h3>
            </div>
            <form action="{{ route('groups.members.store', $group) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="user_id">Usuario</label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="">Seleccione un usuario</option>
                                    @foreach ($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="role">Rol</label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="member">Miembro</option>
                                    <option value="moderator">Moderador</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Estado</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus mr-1"></i> Agregar miembro
                    </button>
                </div>
            </form>
        </div>
    @endif

@endsection
