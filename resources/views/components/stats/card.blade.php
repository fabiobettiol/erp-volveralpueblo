@props([
    'title' => '',
    'icon' => '',
    'value',
])

<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $title }}</h5>
        <div class="card-text">
            <p><i class="fa {{ $icon }}"></i>{{ $value }}</p>
        </div>
    </div>
</div>


