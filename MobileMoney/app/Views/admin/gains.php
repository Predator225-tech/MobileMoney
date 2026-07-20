<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suivi des Gains</title>
    <!-- Lien vers Bootstrap pour faire joli -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <h1>Tableau de bord Opérateur</h1>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Gains Totaux</h5>
            <p class="card-text display-4">
                <?= esc($total_gains ?? 0) ?> Ar
            </p>
            <a href="/admin/dashboard" class="btn btn-secondary">Retour au Dashboard</a>
        </div>
    </div>
</body>
</html>