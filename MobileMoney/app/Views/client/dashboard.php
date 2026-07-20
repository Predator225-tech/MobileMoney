<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace client</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<div class="container">
    <?php $client = $client ?? ['numero_telephone' => '']; ?>
    <?php $balance = $balance ?? 0; ?>
    <?php $history = $history ?? []; ?>

    <div class="header d-flex">
        <div>
            <h3>Espace client</h3>
            <p>Numéro: <?= esc($client['numero_telephone']) ?></p>
        </div>
        <a href="<?= base_url('/auth/logout') ?>" class="btn btn-outline">Déconnexion</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card" style="margin-bottom: 20px;">
        <h4>Solde disponible</h4>
        <div style="font-size: 2rem; font-weight: 700; color: #1d4ed8;"><?= esc(number_format((float) $balance, 2, ',', ' ')) ?> Ar</div>
    </div>

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <div class="card">
            <h4>Dépôt</h4>
            <form action="<?= base_url('/client/deposit') ?>" method="post" class="d-flex" style="gap: 10px; flex-wrap: wrap;">
                <input type="number" step="0.01" min="1" name="montant" placeholder="Montant" required>
                <button type="submit" class="btn btn-success">Valider</button>
            </form>
        </div>

        <div class="card">
            <h4>Retrait</h4>
            <form action="<?= base_url('/client/withdraw') ?>" method="post" class="d-flex" style="gap: 10px; flex-wrap: wrap;">
                <input type="number" step="0.01" min="1" name="montant" placeholder="Montant" required>
                <button type="submit" class="btn btn-success">Valider</button>
            </form>
        </div>

        <div class="card">
            <h4>Transfert</h4>
            <form action="<?= base_url('/client/transfer') ?>" method="post" class="d-flex" style="gap: 10px; flex-wrap: wrap;">
                <input type="text" name="numero_destination" placeholder="Téléphone destinataire" required>
                <input type="number" step="0.01" min="1" name="montant" placeholder="Montant" required>
                <button type="submit" class="btn btn-success">Transférer</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h4>Historique récent</h4>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Opération</th>
                    <th>Montant</th>
                    <th>Frais</th>
                    <th>Destinataire</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($history)): ?>
                    <tr><td colspan="5" style="text-align:center;">Aucune transaction.</td></tr>
                <?php endif; ?>
                <?php foreach ($history as $row): ?>
                    <tr>
                        <td><?= esc($row['date_transaction']) ?></td>
                        <td><?= esc($row['operation_nom'] ?? '') ?></td>
                        <td><?= esc($row['montant']) ?> Ar</td>
                        <td><?= esc($row['frais']) ?> Ar</td>
                        <td><?= esc($row['numero_destination'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>