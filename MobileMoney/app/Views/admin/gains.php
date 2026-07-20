<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suivi des Gains</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Tableau de bord des gains</h1>
            <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-secondary">Retour au Dashboard</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Gains totaux</h5>
                <p class="display-4 mb-0">
                    <?= esc(number_format((float) ($total_gains ?? 0), 2, ',', ' ')) ?> Ar
                </p>
            </div>
        </div>

        <div class="row mt-4">
            <?php $operations = [
                ['name' => 'Retrait', 'value' => (float) ($gains_by_operation['Retrait'] ?? 0)],
                ['name' => 'Transfert', 'value' => (float) ($gains_by_operation['Transfert'] ?? 0)],
            ]; ?>
            <?php $maxValue = max(1.0, ...array_column($operations, 'value')); ?>
            <?php foreach ($operations as $operation): ?>
                <?php $percent = $maxValue > 0 ? round((($operation['value'] / $maxValue) * 100), 0) : 0; ?>
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h5 class="card-title mb-0"><?= esc($operation['name']) ?></h5>
                                <strong><?= esc(number_format($operation['value'], 2, ',', ' ')) ?> Ar</strong>
                            </div>
                            <div class="progress" style="height: 22px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= esc($percent) ?>%;" aria-valuenow="<?= esc($percent) ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= esc($percent) ?>%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>