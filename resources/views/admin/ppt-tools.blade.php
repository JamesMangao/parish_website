{{--
    Legacy include — use ppt-tools-buttons + ppt-tools-modal inside a pptAutomation() x-data scope instead.
    Kept for reference; do not @include this file inside an existing admin layout.
--}}
<div x-data="pptAutomation()"
     @keydown.window="handleKeyDown($event)"
     @mousemove.window="onDrag($event)"
     @mouseup.window="stopDrag()"
     class="flex flex-wrap items-center gap-3">
    @include('admin.ppt-tools-buttons')
</div>
@include('admin.ppt-tools-modal')
