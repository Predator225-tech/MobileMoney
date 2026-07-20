<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Opérateur</title>
    <!-- Lien vers Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h1 class="mb-4">Tableau de bord Opérateur</h1>

        <div class="row">
            <!-- Carte : Voir les gains -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Gains</h5>
                        <p class="card-text">Consulter le total des commissions perçues.</p>
                        <a href="/admin/gains" class="btn btn-primary">Voir les gains</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Préfixes</h5>
                        <p class="card-text">Gestion des préfixes téléphoniques valides.</p>
                        <a href="/admin/prefixes" class="btn btn-primary">Gérer les préfixes</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Types d'opérations</h5>
                        <p class="card-text">CRUD des opérations utilisées par les clients et les barèmes.</p>
                        <a href="<?= base_url('/admin/types-operations') ?>" class="btn btn-primary">Gérer les opérations</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Barèmes de frais</h5>
                        <p class="card-text">Consultation et modification des frais fixes par tranche.</p>
                        <a href="/admin/baremes" class="btn btn-primary">Gérer les barèmes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>