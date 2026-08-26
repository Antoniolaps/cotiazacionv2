<div class="card border-0 shadow-sm rounded-3 h-100 border-start border-4 border-{{ $color }}">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">{{ $title }}</span>
                <h3 class="fw-bold my-1 text-dark">{{ $value }}</h3>
            </div>
            <div class="fs-1 text-{{ $color }} opacity-75">
                <i class="bi {{ $icon }}"></i>
            </div>
        </div>
    </div>
</div>
