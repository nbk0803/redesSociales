<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group #{{ $grupo->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 24px; color: #222; }
        h1 { text-align: center; color: #2c3e50; font-size: 20px; margin-bottom: 4px; }
        .subtitulo { text-align: center; font-size: 12px; color: #666; margin-top: 0; }
        .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 8px; margin-bottom: 16px; }
        .fecha { text-align: right; font-size: 11px; margin-bottom: 12px; color: #555; }
        .info { margin-bottom: 16px; }
        .info p { margin: 3px 0; }
        .info strong { display: inline-block; width: 110px; }
        h2 { font-size: 15px; color: #2c3e50; margin: 20px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; font-size: 11px; }
        th { background: #e9ecef; }
        .text-center { text-align: center; }
        .badge { font-size: 10px; }
        .total { text-align: right; font-weight: bold; margin-top: 14px; }
        .footer { margin-top: 24px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    @php
        $privacyLabels = [
            'public' => 'Public',
            'private' => 'Private',
            'secret' => 'Secret',
        ];

        $roleLabels = [
            'owner' => 'Owner',
            'admin' => 'Administrator',
            'moderator' => 'Moderator',
            'member' => 'Member',
        ];
    @endphp

    <div class="header">
        <h1>Group Details</h1>
        <p class="subtitulo">Group #{{ $grupo->id }}</p>
    </div>

    <p class="fecha">Generated: {{ $fecha }}</p>

    <div class="info">
        <p><strong>Name:</strong> {{ $grupo->name }}</p>
        <p><strong>Description:</strong> {{ $grupo->description }}</p>
        <p><strong>Privacy:</strong> {{ $privacyLabels[$grupo->privacy] ?? ucfirst($grupo->privacy) }}</p>
        <p><strong>Status:</strong> {{ $grupo->status === 'active' ? 'Active' : 'Inactive' }}</p>
        <p><strong>Creator:</strong> {{ $grupo->creator->name ?? 'No creator' }}</p>
        <p><strong>Registered by:</strong> {{ $grupo->registeredBy->name ?? 'N/A' }}</p>
        <p><strong>Created:</strong> {{ $grupo->created_at?->format('m/d/Y H:i') }}</p>
    </div>

    <h2>Group Members</h2>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 35px">#</th>
                <th>Member</th>
                <th>Username</th>
                <th>Email</th>
                <th class="text-center">Role</th>
                <th class="text-center">Status</th>
                <th class="text-center">Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grupo->members as $miembro)
                <tr>
                    <td class="text-center">{{ $miembro->id }}</td>
                    <td>{{ $miembro->name }}</td>
                    <td>{{ $miembro->username ?? 'N/A' }}</td>
                    <td>{{ $miembro->email }}</td>
                    <td class="text-center">{{ $roleLabels[$miembro->pivot->role] ?? $miembro->pivot->role }}</td>
                    <td class="text-center">{{ $miembro->pivot->status === 'active' ? 'Active' : 'Inactive' }}</td>
                    <td class="text-center">{{ $miembro->pivot->joined_at ? \Illuminate\Support\Carbon::parse($miembro->pivot->joined_at)->format('m/d/Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">This group has no members yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total members: {{ $grupo->members->count() }}</p>
    <p class="footer">Document generated automatically by the system.</p>
</body>
</html>