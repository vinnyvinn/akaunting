@push('stylesheet')
    <style type="text/css">
        .item-quantity-stock {
            float: right;
            margin-top: -20px;
        }
    </style>
@endpush

@push('scripts_start')
    <script src="{{ asset('public/js/modules/inventory-invoice.js?v=' . version('short')) }}"></script>
@endpush
