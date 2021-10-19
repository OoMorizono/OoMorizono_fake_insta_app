@if (session('flash_message'))
<div class="alert alert-primary">
    {{ session('flash_message') }}
</div>
@endif