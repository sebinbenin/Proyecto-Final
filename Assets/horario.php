<?php
// Dibuja la grilla de horarios de un curso (filas = bloques horarios, columnas = lunes a viernes).
// Si $editable es true, cada clase muestra un enlace para borrarla (solo lo usa el administrador).

function mostrar_horario($conexion, $curso_id, $titulo, $editable = false) {
    $stmt = $conexion->prepare("SELECT horarios.id, dia, hora_inicio, hora_fin, materia, usuarios.nombre AS profesor
        FROM horarios LEFT JOIN usuarios ON horarios.profesor_id = usuarios.id
        WHERE horarios.curso_id = ? ORDER BY hora_inicio, dia");
    $stmt->execute([$curso_id]);

    $bloques = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $clase) {
        $clave = substr($clase['hora_inicio'], 0, 5) . ' a ' . substr($clase['hora_fin'], 0, 5);
        $bloques[$clave][$clase['dia']][] = $clase;
    }

    $dias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes'];
    ?>
    <div class="horario-contenedor">
        <table class="horario">
            <thead>
                <tr><th colspan="6" class="horario-titulo"><?= htmlspecialchars($titulo) ?></th></tr>
                <tr>
                    <th>Horarios</th>
                    <?php foreach ($dias as $dia): ?><th><?= $dia ?></th><?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bloques as $hora => $clases_por_dia): ?>
                    <tr>
                        <td class="horario-hora"><?= $hora ?></td>
                        <?php foreach ($dias as $numero => $dia): ?>
                            <td>
                                <?php foreach ($clases_por_dia[$numero] ?? [] as $clase): ?>
                                    <div class="horario-clase">
                                        <?= htmlspecialchars($clase['materia']) ?>
                                        <?php if ($clase['profesor']): ?><small>(<?= htmlspecialchars($clase['profesor']) ?>)</small><?php endif; ?>
                                        <?php if ($editable): ?>
                                            <a class="horario-borrar" href="horarios_admin.php?accion=borrar&id=<?= $clase['id'] ?>&curso_id=<?= (int) $curso_id ?>" data-confirmar="¿Borrar?" title="Borrar">✕</a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$bloques): ?>
                    <tr><td colspan="6" class="horario-vacio">Este curso todavía no tiene horarios cargados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
