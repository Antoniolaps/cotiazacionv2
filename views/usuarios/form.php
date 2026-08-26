<?php require __DIR__ . '/../layouts/header.php'; ?>

<style>
/* ================================================================
   FORMULARIO DE USUARIO — DISEÑO PREMIUM
================================================================ */
.usr-form-page {
    max-width: 720px;
    margin: 0 auto;
}

.usr-form-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 28px;
}
.usr-form-header .form-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    box-shadow: 0 4px 12px rgba(99,102,241,.3);
}
.usr-form-header h1 {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}
.usr-form-header p {
    color: #64748b;
    font-size: .85rem;
    margin: 0;
}

.usr-form-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
}

/* Sección dentro de la card */
.form-section {
    padding: 24px 28px;
    border-bottom: 1px solid #f1f5f9;
}
.form-section:last-child { border-bottom: none; }
.form-section-title {
    font-size: .72rem;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.form-section-title i { color: #6366f1; font-size: .85rem; }

/* Grid de campos */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.form-grid .full { grid-column: 1 / -1; }

/* Campo individual */
.field-wrap { display: flex; flex-direction: column; gap: 5px; }
.field-label {
    font-size: .78rem;
    font-weight: 700;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 4px;
}
.field-label .req { color: #ef4444; font-size: .85rem; }
.field-label .opt { color: #94a3b8; font-size: .72rem; font-weight: 500; }

.field-input {
    padding: 9px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: .88rem;
    transition: all .2s;
    color: #0f172a;
    background: #f8fafc;
    width: 100%;
}
.field-input:focus {
    outline: none;
    border-color: #6366f1;
    background: white;
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.field-input.has-icon { padding-left: 36px; }
.field-input-wrap {
    position: relative;
}
.field-input-wrap i {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}

/* Select con estilo */
select.field-input {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    padding-right: 30px;
}

/* Password show/hide */
.pwd-wrap { position: relative; }
.pwd-wrap .field-input { padding-right: 40px; }
.btn-pwd-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    transition: color .2s;
}
.btn-pwd-toggle:hover { color: #475569; }

/* Password strength */
.pwd-strength {
    height: 3px;
    border-radius: 2px;
    background: #e2e8f0;
    overflow: hidden;
    margin-top: 4px;
}
.pwd-strength-bar {
    height: 100%;
    border-radius: 2px;
    transition: all .3s;
    width: 0%;
}
.pwd-hint { font-size: .72rem; color: #94a3b8; margin-top: 2px; }

/* Toggle activo */
.toggle-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    background: #f8fafc;
    cursor: pointer;
    transition: all .2s;
    user-select: none;
}
.toggle-wrap:hover { border-color: #6366f1; background: rgba(99,102,241,.03); }
.toggle-switch {
    position: relative;
    width: 40px;
    height: 22px;
    flex-shrink: 0;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute;
    inset: 0;
    background: #cbd5e1;
    border-radius: 22px;
    transition: .25s;
}
.toggle-slider::before {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: white;
    top: 3px;
    left: 3px;
    transition: .25s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.toggle-switch input:checked + .toggle-slider { background: #6366f1; }
.toggle-switch input:checked + .toggle-slider::before { transform: translateX(18px); }
.toggle-text { flex: 1; }
.toggle-text strong { font-size: .875rem; font-weight: 700; color: #0f172a; }
.toggle-text p { font-size: .75rem; color: #64748b; margin: 0; }

/* Avatar preview */
.usr-avatar-preview {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.1rem;
    color: white;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    margin: 0 auto 16px;
    transition: all .2s;
}

/* Rol badges preview */
.role-preview {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
    margin-top: 4px;
}

/* Footer de la card */
.form-footer {
    padding: 18px 28px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    align-items: center;
}
.btn-cancel-usr {
    padding: 9px 18px;
    border-radius: 8px;
    border: 1.5px solid #e2e8f0;
    background: white;
    color: #64748b;
    font-size: .875rem;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s;
}
.btn-cancel-usr:hover { border-color: #cbd5e1; color: #334155; background: #f8fafc; }

.btn-save-usr {
    padding: 9px 22px;
    border-radius: 8px;
    border: none;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    font-size: .875rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    box-shadow: 0 4px 12px rgba(99,102,241,.25);
}
.btn-save-usr:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }
.btn-save-usr:active { transform: none; }

/* Responsive */
@media (max-width: 600px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .full { grid-column: 1; }
    .form-section { padding: 18px; }
    .form-footer { flex-direction: column-reverse; }
    .btn-save-usr, .btn-cancel-usr { width: 100%; justify-content: center; text-align: center; }
}
</style>

<?php
$isEdit = !empty($row['id']);
$rolColors = ['admin'=>'#6366f1','vendedor'=>'#10b981','almacen'=>'#f59e0b','cliente'=>'#06b6d4'];
$rolIcons  = ['admin'=>'bi-shield-fill','vendedor'=>'bi-cart-fill','almacen'=>'bi-boxes','cliente'=>'bi-person-fill'];
$currentRol = '';
foreach ($roles as $r_opt) {
    if (($row['rol_id'] ?? '') == $r_opt['v']) { $currentRol = strtolower($r_opt['l']); break; }
}
$initials = $isEdit ? strtoupper(mb_substr($row['nombre'] ?? '?', 0, 1)) : '?';
?>

<div class="usr-form-page">

    <!-- Header -->
    <div class="usr-form-header">
        <div class="form-icon">
            <i class="bi bi-<?= $isEdit ? 'person-gear' : 'person-plus-fill' ?>"></i>
        </div>
        <div>
            <h1><?= $isEdit ? 'Editar Usuario' : 'Nuevo Usuario' ?></h1>
            <p><?= $isEdit
                ? 'Modifica la información de ' . e($row['nombre'])
                : 'Crea un nuevo acceso al sistema' ?></p>
        </div>
    </div>

    <form method="post"
          action="<?= url('?r=usuarios/' . ($isEdit ? 'update' : 'store')) ?>"
          id="userForm" novalidate>
        <?= csrf_field() ?>
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= e($row['id']) ?>">
        <?php endif; ?>

        <div class="usr-form-card">

            <!-- Sección 1: Identidad -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-person-badge"></i> Información Personal
                </div>

                <!-- Preview avatar -->
                <div style="text-align:center; margin-bottom:20px">
                    <div class="usr-avatar-preview" id="avatar-preview" style="background:<?= $rolColors[$currentRol] ?? '#6366f1' ?>22; color:<?= $rolColors[$currentRol] ?? '#6366f1' ?>; border: 2px solid <?= $rolColors[$currentRol] ?? '#6366f1' ?>33; width:60px;height:60px;margin:0 auto 8px">
                        <span id="avatar-initials"><?= $initials ?></span>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field-wrap full">
                        <label class="field-label" for="nombre">
                            Nombre completo <span class="req">*</span>
                        </label>
                        <div class="field-input-wrap">
                            <i class="bi bi-person"></i>
                            <input class="field-input has-icon" type="text" id="nombre" name="nombre"
                                   value="<?= e($row['nombre'] ?? '') ?>"
                                   placeholder="Ej: Juan Pérez"
                                   required>
                        </div>
                    </div>



                    <div class="field-wrap">
                        <label class="field-label" for="telefono">
                            Teléfono <span class="opt">(opcional)</span>
                        </label>
                        <div class="field-input-wrap">
                            <i class="bi bi-phone"></i>
                            <input class="field-input has-icon" type="tel" id="telefono" name="telefono"
                                   value="<?= e($row['telefono'] ?? '') ?>"
                                   placeholder="507-6xxx-xxxx">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Credenciales -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-key"></i> Credenciales de Acceso
                </div>

                <div class="form-grid">
                    <div class="field-wrap">
                        <label class="field-label" for="username">
                            Usuario <span class="req">*</span>
                        </label>
                        <div class="field-input-wrap">
                            <i class="bi bi-at"></i>
                            <input class="field-input has-icon" type="text" id="username" name="username"
                                   value="<?= e($row['username'] ?? '') ?>"
                                   placeholder="nombre_usuario"
                                   required autocomplete="username">
                        </div>
                    </div>

                    <div class="field-wrap">
                        <label class="field-label" for="password">
                            Contraseña
                            <?php if ($isEdit): ?>
                                <span class="opt">(dejar vacío para no cambiar)</span>
                            <?php else: ?>
                                <span class="req">*</span>
                            <?php endif; ?>
                        </label>
                        <div class="pwd-wrap">
                            <input class="field-input" type="password" id="password" name="password"
                                   placeholder="<?= $isEdit ? 'Nueva contraseña...' : 'Mínimo 6 caracteres' ?>"
                                   <?= !$isEdit ? 'required' : '' ?>
                                   autocomplete="new-password">
                            <button type="button" class="btn-pwd-toggle" id="pwdToggle" tabindex="-1">
                                <i class="bi bi-eye" id="pwdIcon"></i>
                            </button>
                        </div>
                        <div class="pwd-strength"><div class="pwd-strength-bar" id="pwdBar"></div></div>
                        <div class="pwd-hint" id="pwdHint">
                            <?= $isEdit ? 'Solo rellena si quieres cambiar la contraseña' : 'Usa al menos 6 caracteres' ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Rol y Estado -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="bi bi-shield-check"></i> Rol y Permisos
                </div>

                <div class="form-grid">
                    <div class="field-wrap">
                        <label class="field-label" for="rol_id">Rol del sistema</label>
                        <select class="field-input" id="rol_id" name="rol_id">
                            <option value="">— Sin rol asignado —</option>
                            <?php foreach ($roles as $r_opt): ?>
                                <option value="<?= e($r_opt['v']) ?>"
                                    <?= ($row['rol_id'] ?? '') == $r_opt['v'] ? 'selected' : '' ?>>
                                    <?= ucfirst(e($r_opt['l'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div id="rol-preview-wrap" style="margin-top:6px;<?= $currentRol ? '' : 'display:none' ?>">
                            <span class="role-preview" id="rol-preview"
                                  style="background:<?= ($rolColors[$currentRol] ?? '#94a3b8') ?>18; color:<?= ($rolColors[$currentRol] ?? '#64748b') ?>">
                                <i class="bi <?= $rolIcons[$currentRol] ?? 'bi-person' ?>" id="rol-icon"></i>
                                <span id="rol-preview-text"><?= ucfirst($currentRol) ?></span>
                            </span>
                        </div>
                    </div>

                    <div class="field-wrap" style="justify-content:flex-end">
                        <label class="field-label">Estado de la cuenta</label>
                        <label class="toggle-wrap" for="estado">
                            <div class="toggle-switch">
                                <input type="checkbox" id="estado" name="estado" value="1"
                                       <?= ($row['estado'] ?? 1) ? 'checked' : '' ?>>
                                <span class="toggle-slider"></span>
                            </div>
                            <div class="toggle-text">
                                <strong id="estado-label"><?= ($row['estado'] ?? 1) ? 'Cuenta Activa' : 'Cuenta Inactiva' ?></strong>
                                <p>El usuario <?= ($row['estado'] ?? 1) ? 'puede' : 'no puede' ?> iniciar sesión</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="form-footer">
                <a href="<?= url('?r=usuarios/index') ?>" class="btn-cancel-usr">
                    <i class="bi bi-arrow-left" style="margin-right:5px"></i> Cancelar
                </a>
                <button type="submit" class="btn-save-usr">
                    <i class="bi bi-check-lg"></i>
                    <?= $isEdit ? 'Guardar Cambios' : 'Crear Usuario' ?>
                </button>
            </div>

        </div><!-- /.usr-form-card -->
    </form>
</div>

<script>
/* ── Avatar preview desde el nombre ── */
const nombreInput = document.getElementById('nombre');
const avatarEl    = document.getElementById('avatar-initials');
nombreInput.addEventListener('input', function() {
    const parts = this.value.trim().split(' ').filter(Boolean);
    const initials = parts.length >= 2
        ? (parts[0][0] + parts[1][0]).toUpperCase()
        : (parts[0]?.[0] ?? '?').toUpperCase();
    avatarEl.textContent = initials;
});

/* ── Password toggle ── */
const pwdInput = document.getElementById('password');
const pwdIcon  = document.getElementById('pwdIcon');
document.getElementById('pwdToggle').addEventListener('click', function() {
    const isText = pwdInput.type === 'text';
    pwdInput.type = isText ? 'password' : 'text';
    pwdIcon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
});

/* ── Password strength ── */
const pwdBar  = document.getElementById('pwdBar');
const pwdHint = document.getElementById('pwdHint');
pwdInput.addEventListener('input', function() {
    const v = this.value;
    let score = 0;
    if (v.length >= 6)  score++;
    if (v.length >= 10) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^a-zA-Z0-9]/.test(v)) score++;

    const levels = [
        { pct: '20%',  color: '#ef4444', label: 'Muy débil' },
        { pct: '40%',  color: '#f97316', label: 'Débil' },
        { pct: '60%',  color: '#eab308', label: 'Moderada' },
        { pct: '80%',  color: '#22c55e', label: 'Fuerte' },
        { pct: '100%', color: '#10b981', label: 'Muy fuerte ✓' },
    ];
    const lv = v.length ? (levels[Math.min(score - 1, 4)] || levels[0]) : null;
    if (lv) {
        pwdBar.style.width = lv.pct;
        pwdBar.style.background = lv.color;
        pwdHint.textContent = lv.label;
        pwdHint.style.color = lv.color;
    } else {
        pwdBar.style.width = '0%';
        pwdHint.textContent = '<?= $isEdit ? 'Solo rellena si quieres cambiar la contraseña' : 'Usa al menos 6 caracteres' ?>';
        pwdHint.style.color = '#94a3b8';
    }
});

/* ── Rol preview dinámico ── */
const rolSelect   = document.getElementById('rol_id');
const rolPreview  = document.getElementById('rol-preview');
const rolIcon     = document.getElementById('rol-icon');
const rolText     = document.getElementById('rol-preview-text');
const rolWrap     = document.getElementById('rol-preview-wrap');
const avatarPrev  = document.getElementById('avatar-preview');

const rolData = {
    <?php foreach ($roles as $r_opt):
        $rn = strtolower($r_opt['l']);
        $rc = $rolColors[$rn] ?? '#94a3b8';
        $ri = $rolIcons[$rn] ?? 'bi-person';
    ?>
    '<?= e($r_opt['v']) ?>': {
        label: '<?= ucfirst(e($r_opt['l'])) ?>',
        color: '<?= $rc ?>',
        icon:  '<?= $ri ?>'
    },
    <?php endforeach; ?>
};

rolSelect.addEventListener('change', function() {
    const v = this.value;
    if (!v || !rolData[v]) { rolWrap.style.display = 'none'; return; }
    const d = rolData[v];
    rolPreview.style.background = d.color + '18';
    rolPreview.style.color      = d.color;
    rolIcon.className = 'bi ' + d.icon;
    rolText.textContent = d.label;
    rolWrap.style.display = '';

    // Update avatar color too
    avatarPrev.style.background = d.color + '22';
    avatarPrev.style.color      = d.color;
    avatarPrev.style.borderColor = d.color + '33';
});

/* ── Estado toggle label ── */
const estadoCheck  = document.getElementById('estado');
const estadoLabel  = document.getElementById('estado-label');
estadoCheck.addEventListener('change', function() {
    estadoLabel.textContent = this.checked ? 'Cuenta Activa' : 'Cuenta Inactiva';
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
