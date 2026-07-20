<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion client</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<div class="container" style="max-width: 560px; margin: 60px auto;">
    <div class="card" style="background: #fff;">
        <h2>Connexion client</h2>
        <p>Entrez votre numéro de téléphone. Si le compte n'existe pas, il sera créé automatiquement.</p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <form action="<?= base_url('/auth/login') ?>" method="post" class="d-flex" style="gap: 12px; flex-wrap: wrap;">
            <input type="text" name="numero_telephone" placeholder="Ex: 0331234567" required style="flex: 1; min-width: 240px;">
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
    </div>
    <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-secondary" style="background-color: #6c757d; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-size: 0.9em;">
                Se connecter en tant qu'admin
            </a>
</div>
</body>
</html>