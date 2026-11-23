<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 24px; color: #222; }
        h1 { text-align: center; color: #2c3e50; font-size: 20px; margin-bottom: 4px; }
        .subtitulo { text-align: center; font-size: 12px; color: #666; margin-top: 0; }
        .fecha { text-align: right; font-size: 11px; margin-bottom: 16px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background: #e9ecef; font-size: 11px; }
        td { font-size: 11px; }
        .text-center { text-align: center; }
        .total { text-align: right; font-weight: bold; margin-top: 16px; }
        .footer { margin-top: 24px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    @php
        $roleLabels = [
            'owner' => 'Owner',
            'admin' => 'Administrator',
            'moderator' => 'Moderator',
            'member' => 'Member',
        ];
    @endphp

    <h1>{{ $titulo }}</h1>
    <p class="subtitulo">Social Networks</p>
    <p class="fecha">Generated: {{ $fecha }}</p>

    @if (in_array($status, ['active', 'inactive'], true))
        <p class="subtitulo">Filter: {{ $status === 'active' ? 'Active only' : 'Inactive only' }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px">#</th>
                <th>User</th>
                <th>Group</th>
                <th class="text-center">Role</th>
                <th class="text-center">Status</th>
                <th>Registered by</th>
                <th class="text-center">Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($membresias as $membresia)
                <tr>
                    <td class="text-center">{{ $membresia->id }}</td>
                    <td>{{ $membresia->user->name ?? 'User' }}</td>
                    <td>{{ $membresia->group->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $roleLabels[$membresia->role] ?? $membresia->role }}</td>
                    <td class="text-center">{{ $membresia->status === 'active' ? 'Active' : 'Inactive' }}</td>
                    <td>{{ $membresia->registeredBy->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $membresia->joined_at?->format('m/d/Y') ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No memberships to display.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total memberships: {{ $membresias->count() }}</p>
    <p class="footer">Document generated automatically by the system.</p>
</body>
</html>