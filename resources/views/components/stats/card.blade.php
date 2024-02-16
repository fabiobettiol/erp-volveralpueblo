@props([
    'id' => '',
    'title' => '',
    'icon' => '',
    'value',
    'lens' => true
])

<div class="card shadow-sm my-4">
    <div class="card-header">
        <div class="d-flex flex-row ">
            <h5 class="flex-grow-1 h6">{{ $title }}</h5>
            @if ($lens)
                <a id="{{ $id }}" href="#">
                    <i class="m-1 fa-solid fa-magnifying-glass"></i>
                </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="d-flex text-center">
                    <i class="fa {{ $icon }}"></i>
                    <p id="{{ $title }}" class="h4 my-1"><strong>{{ $value }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>



