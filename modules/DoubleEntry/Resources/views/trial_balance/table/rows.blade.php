<tr class="rp-border-top-1 row mx-0">
    <td class="col-md-6" style="padding-left: {{ $class->indents['table_rows'] }};">{{ $class->row_names[$table][$id] }}</td>
    @foreach($rows as $row)
        <td class="col-md-3 text-right">@money($row, setting('default.currency'), true)</td>
    @endforeach
</tr>
