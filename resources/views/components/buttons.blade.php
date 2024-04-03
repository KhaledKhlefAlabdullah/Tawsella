<!-- resources/views/components/buttons.blade.php -->

<div class="btn-group" role="group" aria-label="Driver Actions">
    @if ($showDeleteButton && $deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
        </form>
        <span class="pipe">|</span>
    @endif
    
    @if ($showEditButton && $editRoute)
        <a href="{{ $editRoute }}" class="btn btn-primary rounded" title="{{ __('Edit') }}"><i class="fas fa-pencil-alt"></i></a>
        <span class="pipe">|</span>
    @endif
    
    @if ($showDetailsButton && $showRoute)
        <a href="{{ $showRoute }}" class="btn btn-info rounded" title="{{ __('Details') }}"><i class="fas fa-info-circle"></i></a>
    @endif
</div>
