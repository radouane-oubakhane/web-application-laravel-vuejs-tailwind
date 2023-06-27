<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information de votre compte</title>
</head>
<body>
<h4>Bonjour monsieur / madam  {{ $user->nom }} {{$user->prenom}}.</h4>
<p>Félicitations, votre demande pour rejoindre notre laboratoire a été acceptée</p>
<h4>Votre compte :</h4>
<p>Email : {{$user->email}}</p>
<p>PassWord : {{$password}}</p>
</body>
</html>
