<?php

namespace Modules\CustomFields\Traits;

use Modules\CustomFields\Traits\CustomFields as TFields;
use Modules\CustomFields\Events\CustomFieldLocationSortColumns;

trait LocationSortOrder
{
    use TFields;

    public function getLocationSortOrder($code)
    {
        $result = [];

        switch ($code) {
            case 'common.companies':
                $result = $this->getCompaniesColumns();
                break;
            case 'common.items':
                $result = $this->getItemsColumns();
                break;
            case 'sales.invoices':
                $result = $this->getInvoicesColumns();
                break;
            case 'sales.revenues':
                $result = $this->getRevenuesColumns();
                break;
            case 'sales.customers':
                $result = $this->getCustomersColumns();
                break;
            case 'purchases.bills':
                $result = $this->getBillsColumns();
                break;
            case 'purchases.payments':
                $result = $this->getPaymentsColumns();
                break;
            case 'purchases.vendors':
                $result = $this->getVendorsColumns();
                break;
            case 'banking.accounts':
                $result = $this->getAccountsColumns();
                break;
            case 'banking.transfers':
                $result = $this->getTransferColumns();
                break;
            default:
                $result = event(new CustomFieldLocationSortColumns($code, $result), [], true);
        }

        $custom_fields = $this->getFieldsByLocation($code)->pluck('name', 'code')->toArray();

        if ($custom_fields) {
            $result = array_merge($result, $custom_fields);
        }

        return $result;
    }

    public function getLocationDepend($code)
    {
        $result = [];

        switch ($code) {
            case 'common.companies':
                $result = [];
                break;
            case 'common.items':
                $result = [
                    'invoice' => trans_choice('general.invoices', 1)
                ];
                break;
            case 'sales.invoices':
                $result = $result = [];
                break;
            case 'sales.revenues':
                $result = $result = [];
                break;
            case 'sales.customers':
                $result = $result = [];
                break;
            case 'purchases.bills':
                $result = $result = [];
                break;
            case 'purchases.payments':
                $result = $result = [];
                break;
            case 'purchases.vendors':
                $result = $result = [];
                break;
            case 'banking.accounts':
                $result = $result = [];
                break;
            case 'banking.transfers':
                $result = $result = [];
                break;
        }

        return $result;
    }

    public function getCompaniesColumns()
    {
        return [
            'name' => trans('general.name'),
            'domain' => trans('companies.domain'),
            'email' => trans('general.email'),
            'currency' => trans_choice('general.currencies', 1),
            'address' => trans('general.address'),
            'logo' => trans('companies.logo'),
            'enabled' => trans('general.enabled')
        ];
    }

    public function getItemsColumns()
    {
        return [
            'name' => trans('general.name'),
            'description' => trans('general.description'),
            'sale_price' => trans('items.sales_price'),
            'purchase_price' => trans('items.purchase_price'),
            'tax_id' => trans_choice('general.taxes', 1),
            'category_id' => trans_choice('general.categories', 1),
            'picture' => trans_choice('general.pictures', 1),
            'enabled' => trans('general.enabled')
        ];
    }

    public function getInvoicesColumns()
    {
        return [
            'contact_id' => trans_choice('general.customers', 1),
            'currency_code' => trans_choice('general.currencies', 1),
            'invoiced_at' => trans('invoices.invoice_date'),
            'due_at' => trans('invoices.due_date'),
            'invoice_number' => trans('invoices.invoice_number'),
            'order_number' => trans('invoices.order_number'),
            'action_td' => trans('custom-fields::general.item.action'),
            'name_td' => trans('custom-fields::general.item.name'),
            'quantity_td' => trans('custom-fields::general.item.quantity'),
            'price_td' => trans('custom-fields::general.item.price'),
            'taxes_td' => trans('custom-fields::general.item.taxes'),
            'total_td' => trans('custom-fields::general.item.total'),
            'notes' => trans_choice('general.notes', 2),
            'category_id' => trans_choice('general.categories', 1),
            'recurring' => trans_choice('general.pictures', 1),
            'attachment' => trans('general.attachment')
        ];
    }

    public function getRevenuesColumns()
    {
        return [
            'paid_at' => trans('general.date'),
            'amount' => trans('general.amount'),
            'account_id' => trans_choice('general.accounts', 1),
            'contact_id' => trans_choice('general.customers', 1),
            'description' => trans('general.description'),
            'category_id' => trans_choice('general.categories', 1),
            'recurring' => trans_choice('general.pictures', 1),
            'payment_method' => trans_choice('general.payment_methods', 1),
            'reference' => trans('general.reference'),
            'attachment' => trans('general.attachment')
        ];
    }

    public function getCustomersColumns()
    {
        return [
            'name' => trans('general.name'),
            'email' => trans('general.email'),
            'tax_number' => trans('general.tax_number'),
            'currency_code' => trans_choice('general.currencies', 1),
            'phone' => trans('general.phone'),
            'website' => trans('general.website'),
            'address' => trans('general.address'),
            'enabled' => trans('general.enabled'),
            'create_user' => trans('customers.allow_login'),
        ];
    }

    public function getBillsColumns()
    {
        return [
            'contact_id' => trans_choice('general.vendors', 1),
            'currency_code' => trans_choice('general.currencies', 1),
            'billed_at' => trans('bills.bill_date'),
            'due_at' => trans('bills.due_date'),
            'bill_number' => trans('bills.bill_number'),
            'order_number' => trans('bills.order_number'),
            'action_td' => trans('custom-fields::general.item.action'),
            'name_td' => trans('custom-fields::general.item.name'),
            'quantity_td' => trans('custom-fields::general.item.quantity'),
            'price_td' => trans('custom-fields::general.item.price'),
            'taxes_td' => trans('custom-fields::general.item.taxes'),
            'total_td' => trans('custom-fields::general.item.total'),
            'notes' => trans_choice('general.notes', 2),
            'category_id' => trans_choice('general.categories', 1),
            'recurring' => trans_choice('general.pictures', 1),
            'attachment' => trans('general.attachment')
        ];
    }

    public function getPaymentsColumns()
    {
        return [
            'paid_at' => trans('general.date'),
            'amount' => trans('general.amount'),
            'account_id' => trans_choice('general.accounts', 1),
            'contact_id' => trans_choice('general.vendors', 1),
            'description' => trans('general.description'),
            'category_id' => trans_choice('general.categories', 1),
            'recurring' => trans_choice('general.pictures', 1),
            'payment_method' => trans_choice('general.payment_methods', 1),
            'reference' => trans('general.reference'),
            'attachment' => trans('general.attachment')
        ];
    }

    public function getVendorsColumns()
    {
        return [
            'name' => trans('general.name'),
            'email' => trans('general.email'),
            'tax_number' => trans('general.tax_number'),
            'currency_code' => trans_choice('general.currencies', 1),
            'phone' => trans('general.phone'),
            'website' => trans('general.website'),
            'address' => trans('general.address'),
            'logo' => trans_choice('general.pictures', 1),
            'enabled' => trans('general.enabled'),
        ];
    }

    public function getAccountsColumns()
    {
        return [
            'name' => trans('general.name'),
            'number' => trans('accounts.number'),
            'currency_code' => trans_choice('general.currencies', 1),
            'opening_balance' => trans('accounts.opening_balance'),
            'bank_name' => trans('accounts.bank_name'),
            'bank_phone' => trans('accounts.bank_phone'),
            'bank_address' => trans('accounts.bank_address'),
            'default_account' => trans('accounts.default_account'),
            'enabled' => trans('general.enabled'),
        ];
    }

    public function getTransferColumns()
    {
        return [
            'from_account_id' => trans('transfers.from_account'),
            'to_account_id' => trans('transfers.to_account'),
            'amount' => trans('general.amount'),
            'transferred_at' => trans('general.date'),
            'description' => trans('general.description'),
            'payment_method' => trans_choice('general.payment_methods', 1),
            'reference' => trans('general.reference'),
        ];
    }
}
