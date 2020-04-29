@if (!empty($class->row_values[$table]))
<div class="table-responsive overflow-auto mt-4">
    <table class="table table-hover align-items-center rp-border-collapse">
        @include($class->views['table.header'])
        <tbody>
            @foreach($class->row_values[$table] as $id => $rows)
                @include($class->views['table.rows'])
            @endforeach
        </tbody>
        @include($class->views['table.footer'])
    </table>
</div>
@endif
