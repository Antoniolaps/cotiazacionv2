<style>
/* ================================================================
   CRUD FORMULARIO — DISEÑO PREMIUM
================================================================ */
.crud-form-page { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
.crud-form-header { display: flex; align-items: center; gap: 14px; margin-bottom: 28px; }
.crud-form-header .form-icon {
    width: 46px; height: 46px; border-radius: 12px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.3rem; box-shadow: 0 4px 12px rgba(99,102,241,.3);
}
.crud-form-header h1 { font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0; }
.crud-form-header p { color: #64748b; font-size: .85rem; margin: 0; }

.crud-form-card {
    background: white; border: 1px solid #e2e8f0;
    border-radius: 14px; overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
}
.form-section { padding: 24px 28px; border-bottom: 1px solid #f1f5f9; }
.form-section:last-child { border-bottom: none; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; }

.field-wrap { display: flex; flex-direction: column; gap: 5px; }
.field-label { font-size: .78rem; font-weight: 700; color: #475569; display: flex; gap: 4px; }
.field-label .req { color: #ef4444; }

.field-input {
    padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 8px;
    font-size: .88rem; transition: all .2s; color: #0f172a; background: #f8fafc; width: 100%;
}
.field-input:focus { outline: none; border-color: #6366f1; background: white; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }

select.field-input {
    cursor: pointer; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px;
}

/* Tom Select Premium Overrides */
.ts-control {
    padding: 9px 12px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: .88rem;
    background: #f8fafc;
    box-shadow: none !important;
}
.ts-control.focus {
    border-color: #6366f1;
    background: white;
    box-shadow: 0 0 0 3px rgba(99,102,241,.1) !important;
}
.ts-dropdown {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
}

.toggle-wrap {
    
    display: flex; align-items: center; gap: 12px; padding: 12px;
    border: 1.5px solid #e2e8f0; border-radius: 8px; background: #f8fafc;
    cursor: pointer; transition: all .2s; user-select: none;
}
.toggle-wrap:hover { border-color: #6366f1; background: rgba(99,102,241,.03); }
.toggle-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; inset: 0; background: #cbd5e1; border-radius: 22px; transition: .25s; }
.toggle-slider::before {
    content: ''; position: absolute; width: 16px; height: 16px; border-radius: 50%;
    background: white; top: 3px; left: 3px; transition: .25s; box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.toggle-switch input:checked + .toggle-slider { background: #6366f1; }
.toggle-switch input:checked + .toggle-slider::before { transform: translateX(18px); }
.toggle-text { flex: 1; font-size: .875rem; font-weight: 700; color: #0f172a; }

.form-footer {
    padding: 18px 28px; background: #f8fafc; border-top: 1px solid #f1f5f9;
    display: flex; gap: 10px; justify-content: flex-end; align-items: center;
}
.btn-cancel-crud {
    padding: 9px 18px; border-radius: 8px; border: 1.5px solid #e2e8f0;
    background: white; color: #64748b; font-size: .875rem; font-weight: 600; text-decoration: none; transition: all .2s;
}
.btn-cancel-crud:hover { border-color: #cbd5e1; color: #334155; background: #f8fafc; }

.btn-save-crud {
    padding: 9px 22px; border-radius: 8px; border: none;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white; font-size: .875rem; font-weight: 700; cursor: pointer;
    transition: all .2s; display: inline-flex; align-items: center; gap: 7px;
    box-shadow: 0 4px 12px rgba(99,102,241,.25);
}
.btn-save-crud:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }
</style>

<div class="crud-form-page">
    <div class="crud-form-header">
        <div class="form-icon">
            <i class="bi bi-<?= !empty($row['id']) ? 'pencil-square' : 'plus-lg' ?>"></i>
        </div>
        <div>
            <h1><?= e($title) ?></h1>
            <p><?= !empty($row['id']) ? 'Modifica la información del registro' : 'Crea un nuevo registro en el sistema' ?></p>
        </div>
    </div>

    <form method="post" action="<?= url('?r=' . $crud->module . '/' . (!empty($row['id']) ? 'update' : 'store')) ?>">
        <?= csrf_field() ?>
        <?php if (!empty($row['id'])): ?><input type="hidden" name="id" value="<?= e($row['id']) ?>"><?php endif; ?>
        
        <div class="crud-form-card">
            <div class="form-section">
                <div class="form-grid">
                    <?php foreach ($crud->fields as $col => $f):
                        $val = $row[$col] ?? ($f['default'] ?? '');
                        $type = $f['type'] ?? 'text';
                        $req = !empty($f['required']) ? 'required' : ''; 
                        $isFull = ($type === 'textarea');
                    ?>
                        <div class="field-wrap" <?= $isFull ? '' : '' ?>>
                            <label class="field-label">
                                <?= e($f['label']) ?>
                                <?php if ($req): ?><span class="req">*</span><?php endif; ?>
                            </label>
                            
                            <?php if ($type === 'textarea'): ?>
                                <textarea class="field-input" name="<?= e($col) ?>" rows="3" <?= $req ?>><?= e((string)$val) ?></textarea>
                            
                            <?php elseif ($type === 'select'): ?>
                                <select class="field-input searchable-select" name="<?= e($col) ?>" <?= $req ?>>
                                    <option value="">— Selecciontar —</option>
                                    <?php foreach ($f['options'] as $o):
                                        $ov = is_array($o) ? $o['v'] : $o;
                                        $ol = is_array($o) ? $o['l'] : $o; ?>
                                        <option value="<?= e((string)$ov) ?>" <?= (string)$val === (string)$ov ? 'selected' : '' ?>><?= e((string)$ol) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            
                            <?php elseif ($type === 'checkbox'): ?>
                                <label class="toggle-wrap disabled">
                                    <div class="toggle-switch">
                                        <input type="checkbox" name="<?= e($col) ?>" value="1" <?= $val ? 'checked' : '' ?>>
                                        <span class="toggle-slider"></span>
                                    </div>
                                    <div class="toggle-text">
                                        <?= e($f['label']) ?>
                                    </div>
                                </label>
                            
                            <?php else: ?>
                                <input class="field-input" type="<?= e($type) ?>" name="<?= e($col) ?>" value="<?= e((string)$val) ?>" <?= $req ?>
                                    <?= isset($f['step']) ? 'step="'.e($f['step']).'"' : '' ?>>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-footer">
                <a href="<?= url('?r='.$crud->module.'/index') ?>" class="btn-cancel-crud">
                    <i class="bi bi-arrow-left" style="margin-right:5px"></i> Cancelar
                </a>
                <button type="submit" class="btn-save-crud">
                    <i class="bi bi-check-lg"></i> Guardar Registro
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof TomSelect !== 'undefined') {
        document.querySelectorAll('.searchable-select').forEach(function(el) {
            new TomSelect(el, {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "— Seleccionar o buscar —"
            });
        });
    }
});
</script>
