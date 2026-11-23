<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User #{{ $user->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 24px; color: #222; }
        h1 { text-align: center; color: #2c3e50; font-size: 20px; margin-bottom: 4px; }
        .subtitulo { text-align: center; font-size: 12px; color: #666; margin-top: 0; }
        .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 8px; margin-bottom: 16px; }
        .fecha { text-align: right; font-size: 11px; margin-bottom: 12px; color: #555; }
        .info { margin-bottom: 16px; }
        .info p { margin: 3px 0; }
        .info strong { display: inline-block; width: 120px; }
        h2 { font-size: 15px; color: #2c3e50; margin: 20px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; font-size: 11px; }
        th { background: #e9ecef; }
        .text-center { text-align: center; }
        .total { text-align: right; font-weight: bold; margin-top: 14px; }
        .footer { margin-top: 24px; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h1>User Details</h1>
        <p class="subtitulo">User #{{ $user->id }}</p>
    </div>

    <p class="fecha">Generated: {{ $fecha }}</p>

    <div class="info">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Username:</strong> {{ $user->username ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Bio:</strong> {{ $user->bio ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $user->status === 'active' ? 'Active' : 'Inactive' }}</p>
        <p><strong>Registered:</strong> {{ $user->created_at?->format('m/d/Y H:i') }}</p>
        <p><strong>Email Verified:</strong> {{ $user->email_verified_at ? $user->email_verified_at->format('m/d/Y H:i') : 'No' }}</p>
    </div>

    <h2>Groups</h2>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 35px">#</th>
                <th>Group</th>
                <th>Role</th>
                <th class="text-center">Status</th>
                <th class="text-center">Joined</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($user->memberGroups as $group)
                <tr>
                    <td class="text-center">{{ $group->id }}</td>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->pivot->role }}</td>
                    <td class="text-center">{{ $group->pivot->status === 'active' ? 'Active' : 'Inactive' }}</td>
                    <td class="text-center">{{ $group->pivot->joined_at ? \Illuminate\Support\Carbon::parse($group->pivot->joined_at)->format('m/d/Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">User is not a member of any group.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total groups: {{ $user->memberGroups->count() }}</p>
    <p class="footer">Document generated automatically by the system.</p>
</body>
</html>