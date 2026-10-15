<?php
// Muestra (una sola vez) el aviso que dejó guardar_reserva.php
if (!empty($_SESSION['aviso'])): ?>
    <div class="aviso aviso-<?php echo $_SESSION['aviso']['tipo'] === 'ok' ? 'ok' : 'error'; ?>">
        <?php echo htmlspecialchars($_SESSION['aviso']['texto']); ?>
    </div>
<?php
    unset($_SESSION['aviso']);
endif;
