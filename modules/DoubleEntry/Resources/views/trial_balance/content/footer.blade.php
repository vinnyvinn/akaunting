<div class="table-responsive overflow-auto mt-4 mb-4">
    <table class="table align-items-center rp-border-collapse">
        <tfoot>
            <tr class="rp-border-top-1 row mx-0">
                <th class="col-md-6 border-top-0 text-left text-uppercase">{{ trans_choice('general.totals', 1) }}</th>
                <th class="col-md-3 border-top-0 text-right">@money($class->footer_totals['debit'], setting('default.currency'), true)</th>
                <th class="col-md-3 border-top-0 text-right">@money($class->footer_totals['credit'], setting('default.currency'), true)</th>
            </tr>
        </tfoot>
    </table>
</div>
