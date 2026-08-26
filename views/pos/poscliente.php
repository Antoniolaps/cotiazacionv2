<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Display - Dual Screen POS</title>
    <!-- Use Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/pos.css') ?>">
</head>
<main>

<tbody>
<?php Auth::check(); ?>

    <?php $total = 0; ?>
    <?php foreach ($cart as $item): ?>
    <?php $total += $item['price'] * $item['qty']; ?>
    <?php endforeach; ?>

        
    <td class="pos-single-screen">
    <?php foreach ($cart as $item): ?>
    <tr>
        <td><?= e($item['name']) ?></td>
        <td><?= e($item['qty']) ?></td>
        <td><?= e($item['price']) ?></td>
    </tr>
<?php endforeach; ?>
        
    </td>
    <td class="pos-total">Total: <?= e($total) ?></td>
    <td class="pos-itbms">ITBMS: <?= e($itbms) ?></td>
    <td class="pos-subtotal">Subtotal: <?= e($subtotal) ?></td>


</tbody>



</main>

</body>
</html>