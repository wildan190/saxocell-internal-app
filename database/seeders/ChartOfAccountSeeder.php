<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // ASSETS
            ['code' => '1000', 'name' => 'Cash on Hand', 'type' => 'asset', 'category' => 'cash'],
            ['code' => '1100', 'name' => 'BCA Bank', 'type' => 'asset', 'category' => 'bank'],
            ['code' => '1110', 'name' => 'Mandiri Bank', 'type' => 'asset', 'category' => 'bank'],
            ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset', 'category' => 'receivable'],
            ['code' => '1300', 'name' => 'Inventory Asset', 'type' => 'asset', 'category' => 'inventory'],
            ['code' => '1500', 'name' => 'Fixed Assets', 'type' => 'asset', 'category' => 'fixed_asset'],
            
            // LIABILITIES
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'category' => 'payable'],
            ['code' => '2100', 'name' => 'Taxes Payable', 'type' => 'liability', 'category' => 'tax'],
            
            // EQUITY
            ['code' => '3000', 'name' => 'Opening Balance Equity', 'type' => 'equity', 'category' => 'other'],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity', 'category' => 'other'],
            
            // REVENUE
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'revenue', 'category' => 'operating_revenue'],
            ['code' => '4100', 'name' => 'Other Income', 'type' => 'revenue', 'category' => 'other'],
            
            // EXPENSES
            ['code' => '5000', 'name' => 'Cost of Goods Sold (COGS)', 'type' => 'expense', 'category' => 'operating_expense'],
            ['code' => '6000', 'name' => 'Salaries & Wages', 'type' => 'expense', 'category' => 'operating_expense'],
            ['code' => '6100', 'name' => 'Rent Expense', 'type' => 'expense', 'category' => 'operating_expense'],
            ['code' => '6200', 'name' => 'Utilities (Electricity/Water)', 'type' => 'expense', 'category' => 'operating_expense'],
            ['code' => '6300', 'name' => 'Marketing & Advertising', 'type' => 'expense', 'category' => 'operating_expense'],
            ['code' => '6900', 'name' => 'Miscellaneous Expense', 'type' => 'expense', 'category' => 'other'],
        ];

        foreach ($accounts as $account) {
            \App\Models\Account::updateOrCreate(['code' => $account['code']], $account);
        }

        // Link Bank Accounts
        $bca = \App\Models\Account::where('code', '1100')->first();
        \App\Models\BankAccount::updateOrCreate(['account_id' => $bca->id], [
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'Saxocell Internal',
            'currency' => 'IDR'
        ]);
    }
}
