<!-- resources/views/components/buttons.blade.php -->

<div class="btn-group" role="group" aria-label="Driver Actions">
    @if ($showDeleteButton && $deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" style="display: inline;" id="deleteForm{{$deleteRoute}}">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-danger" title="{{ __('Delete') }}" onclick="confirmDelete('{{$deleteRoute}}');"><i class="bi bi-trash-fill"></i></button>
        </form>
    @endif

    @if ($showEditButton && $editRoute)
        <span class="pipe">|</span>
        <a href="{{ $editRoute }}" class="btn btn-primary rounded" title="{{ __('Edit') }}"><i class="bi bi-pencil"></i></a>
    @endif

    @if ($showDetailsButton && $showRoute)
        <span class="pipe">|</span>
        <a href="{{ $showRoute }}" class="btn btn-info rounded" title="{{ __('Details') }}"><i class="bi bi-info-circle-fill"></i></a>
    @endif
</div>
