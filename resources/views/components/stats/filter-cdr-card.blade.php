@props([
    'cdr',
    'cdrs'
])

@if (is_null($cdr) )
    <input id="cdr-id" type="hidden" name="cdr" value="">
@else
    <input id="cdr-id" type="hidden" name="cdr" value="{{ $cdr->id }}">
@endif

<input id="cdr-id" type="hidden" name="cdr" value="{{ empty($cdr) ? '' : $cdr->id }}">
<div class="card filters shadow-sm my-4">
    <div class="card-body">
        <div class="d-flex flex-row">
            <span class="flex-grow-1 m-0 leyenda">CDRs</span>
        </div>
        <div class="row py-2">
            <div class="col-md-3">
                <select id="cdr-select" class="form-select">
                    <option value="0">Selecciona un CDR</option>
                    @foreach ($cdrs as $cdr)
                        <option value="{{ $cdr->id }}">{{ $cdr->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>


