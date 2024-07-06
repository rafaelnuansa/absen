<button wire:click="toggleDarkMode" class="btn btn-icon text-white fs-30">
    @if($darkModeActive)
        <i class="ri-sun-line fs-24"></i>
    @else
        <i class="ri-moon-line fs-24"></i>
    @endif
</button>
