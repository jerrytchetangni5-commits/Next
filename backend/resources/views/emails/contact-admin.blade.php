<!DOCTYPE html>
<html>
<head>
    <title>Nouveau message de contact</title>
</head>
<body>
    <h1>Nouveau message de {{ $user->first_name }} {{ $user->last_name }}</h1>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Téléphone :</strong> {{ $user->phone_number ?? 'Non renseigné' }}</p>
    <p><strong>Message :</strong></p>
    <p>{{ $message }}</p>
</body>
</html>