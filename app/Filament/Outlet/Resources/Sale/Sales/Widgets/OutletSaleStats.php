<?php

namespace App\Filament\Outlet\Resources\Sale\Sales\Widgets;

use App\Models\Sale\SaleItem;
use App\Models\Sale\SaleReturnItem;
use App\Support\Traits\HasSalesWidgetFilters;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class OutletSaleStats extends StatsOverviewWidget
{
    use HasWidgetShield, InteractsWithPageFilters, HasSalesWidgetFilters;

    protected static bool $isLazy = true;

    public ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 2;

    protected int | array | null $columns = 4;

    protected ?object $salesAggregates = null;

    protected ?object $salesReturnsAggregates = null;

    protected ?object $salesItemAggregates = null;

    protected ?object $salesReturnItemAggregates = null;

    protected ?object $topSellingProductData = null;

    protected ?object $mostSoldProductData = null;

    protected ?object $mostReturnedProductData = null;

    protected function getSalesAggregates(): object
    {
        if ($this->salesAggregates) {
            return $this->salesAggregates;
        }

        return $this->salesAggregates = $this->getFilteredSalesQuery()
            ->selectRaw('
            COUNT(*) as total_count,
            COALESCE(SUM(total), 0) as total_amount,
            COALESCE(SUM(grand_total), 0) as grand_total_amount,
            COALESCE(SUM(discount_amount), 0) as total_discount,
            COALESCE(SUM(delivery_charges), 0) as total_delivery,
            COALESCE(SUM(tax_charges), 0) as total_tax
        ')
            ->first();
    }

    protected function getSalesReturnAggregates()
    {
        if ($this->salesReturnsAggregates) {
            return $this->salesReturnsAggregates;
        }

        return $this->salesReturnsAggregates = $this->getFilteredSalesReturnQuery()
            ->selectRaw('
             COALESCE(SUM(grand_total), 0) as grand_total_amount,
             COALESCE(SUM(delivery_charges), 0) as total_delivery,
             COALESCE(SUM(discount_amount), 0) as total_discount,
             COALESCE(SUM(tax_charges), 0) as total_tax,
             COALESCE(COUNT(*), 0) as total_count
            ')
            ->first();
    }

    protected function getSalesItemAggregates(): object
    {
        if ($this->salesItemAggregates) {
            return $this->salesItemAggregates;
        }

        return $this->salesItemAggregates = SaleItem::query()
            ->joinSub(
                $this->getFilteredSalesQuery()->select('id'),
                'filtered_sales',
                fn($join) => $join->on('sale_items.sale_id', '=', 'filtered_sales.id')
            )
            ->selectRaw('
            COALESCE(SUM(sale_items.cost * sale_items.qty), 0) as total_cost,
            COALESCE(SUM(sale_items.rate * sale_items.qty), 0) as total_price
        ')
            ->first();
    }

    protected function getSalesReturnItemAggregates(): object
    {
        if ($this->salesReturnItemAggregates) {
            return $this->salesReturnItemAggregates;
        }

        return $this->salesReturnItemAggregates = SaleReturnItem::query()
            ->joinSub(
                $this->getFilteredSalesReturnQuery()->select('id'),
                'filtered_sale_returns',
                fn($join) => $join->on('sale_return_items.sale_return_id', '=', 'filtered_sale_returns.id')
            )
            ->selectRaw('
            COALESCE(SUM(sale_return_items.cost * sale_return_items.qty), 0) as total_cost,
            COALESCE(SUM(sale_return_items.rate * sale_return_items.qty), 0) as total_price
        ')
            ->first();
    }

    public function buildInventoryQuery(Builder $query)
    {
        return $query->with([
            'product' => fn($q) => $q->with(['category', 'brand', 'unit']),
        ])
            ->select(['product_id'])
            ->selectRaw('ABS(SUM(qty)) as qty_sold')
            ->groupBy('product_id')
            ->orderByDesc('qty_sold')
            ->get();
    }

    protected function getTopSellingProductsData()
    {
        if ($this->topSellingProductData) {
            return $this->topSellingProductData;
        }

        return $this->topSellingProductData = $this->buildInventoryQuery(
            $this->getFilteredInventoryQuery()
        );
    }

    protected function mostSoldProductsData()
    {
        if ($this->mostSoldProductData) {
            return $this->mostSoldProductData;
        }

        return $this->mostSoldProductData = $this->buildInventoryQuery(
            $this->getFilteredInventoryQueryForSale()
        );
    }

    protected function mostReturnedProductsData()
    {
        if ($this->mostReturnedProductData) {
            return $this->mostReturnedProductData;
        }

        return $this->mostReturnedProductData = $this->buildInventoryQuery(
            $this->getFilteredInventoryQueryForSaleReturn()
        );
    }

    protected function getSalesCount(): int
    {
        return (int) $this->getSalesAggregates()->total_count;
    }

    protected function getSalesReturnCount(): int
    {
        return (int) $this->getSalesReturnAggregates()->total_count;
    }

    protected function getSalesTotalAmount(): float
    {
        return (float) $this->getSalesAggregates()->total_amount;
    }

    protected function getSalesGrandTotalAmount(): float
    {
        return (float) $this->getSalesAggregates()->grand_total_amount;
    }

    protected function getSalesDiscount(): float
    {
        return (float) $this->getSalesAggregates()->total_discount - $this->getSalesReturnsDiscount();
    }

    protected function getSalesReturnsDiscount(): float
    {
        return (float) $this->getSalesReturnAggregates()->total_discount;
    }

    protected function getSalesDeliveryCharges(): float
    {
        return (float) $this->getSalesAggregates()->total_delivery - $this->getSalesReturnsDeliveryCharges();
    }

    protected function getSalesReturnsDeliveryCharges(): float
    {
        return (float) $this->getSalesReturnAggregates()->total_delivery;
    }

    protected function getSalesTaxCharges(): float
    {
        return (float) $this->getSalesAggregates()->total_tax - $this->getSalesReturnsTaxCharges();
    }

    protected function getSalesReturnsTaxCharges(): float
    {
        return (float) $this->getSalesReturnAggregates()->total_tax;
    }

    public function getSalesCost(): float
    {
        return (float) ($this->getSalesItemAggregates()->total_cost - $this->getSalesReturnCost());
    }

    public function getSalesPrice(): float
    {
        return (float) ($this->getSalesItemAggregates()->total_price - $this->getSalesReturnPrice());
    }

    public function getSalesReturnCost(): float
    {
        return (float) $this->getSalesReturnItemAggregates()->total_cost;
    }

    public function getSalesReturnPrice(): float
    {
        return (float) $this->getSalesReturnItemAggregates()->total_price;
    }

    public function getSalesGrossProfit(): float
    {
        return (float) ($this->getSalesPrice() - $this->getSalesCost());
    }

    protected function getSalesReturnGrandTotalAmount(): float
    {
        return (float) $this->getSalesReturnAggregates()->grand_total_amount;
    }

    public function getSalesNetProfit(): float
    {
        return (float) ($this->getSalesGrossProfit() - $this->getSalesDiscount());
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Sales', $this->getSalesCount())
                ->icon(Heroicon::ShoppingCart)
                ->color('primary')
                ->description('Number of sales'),

            Stat::make('Total Sales Returns', $this->getSalesReturnCount())
                ->icon(Heroicon::ShoppingCart)
                ->color('primary')
                ->description('Number of sale returns'),

            Stat::make('Total Sales Amount', currency_format($this->getSalesGrandTotalAmount()))
                ->icon(Heroicon::CurrencyDollar)
                ->color('success')
                ->description('Grand total of all sales'),

            Stat::make('Total Sale Returns', currency_format($this->getSalesReturnGrandTotalAmount()))
                ->icon(Heroicon::ArrowUturnRight)
                ->color('success')
                ->descriptionIcon(Heroicon::InformationCircle)
                ->description('Total Sales Returned'),

            Stat::make('Total Sales Delivery Charges', currency_format($this->getSalesDeliveryCharges()))
                ->icon(Heroicon::Document)
                ->color('info')
                ->description('Delivery charges for sales'),

            Stat::make('Total Sales Tax Charges', currency_format($this->getSalesTaxCharges()))
                ->icon(Heroicon::DocumentText)
                ->color('warning')
                ->description('Tax applied on sales'),

            Stat::make('Total Receivable Sales Amount', currency_format($this->getSalesGrandTotalAmount() - $this->getSalesReturnGrandTotalAmount()))
                ->icon(Heroicon::CurrencyDollar)
                ->color('success')
                ->description('[Sales - Sales Returns]'),

            Stat::make('Total Cost of Products Sold (COGS)', currency_format($this->getSalesCost()))
                ->icon(Heroicon::ArchiveBox)
                ->color('warning')
                ->description('Across all sales'),

            Stat::make('Total Value of Products Sold', currency_format($this->getSalesPrice()))
                ->icon(Heroicon::Banknotes)
                ->color('primary')
                ->description('Across all sales'),

            Stat::make('Gross Profit From Sales', currency_format($this->getSalesGrossProfit()))
                ->icon(Heroicon::Banknotes)
                ->color('success')
                ->descriptionIcon(Heroicon::InformationCircle)
                ->description('Profit generated from sales (Selling Price - Cost Price )'),

            Stat::make('Total Sales Discount', currency_format($this->getSalesDiscount()))
                ->icon(Heroicon::Tag)
                ->color('danger')
                ->description('Discounts applied to sales'),

            Stat::make('Net Profit From Sales', currency_format($this->getSalesNetProfit()))
                ->icon(Heroicon::Banknotes)
                ->color('success')
                ->descriptionIcon(Heroicon::InformationCircle)
                ->description('Profit generated from sales [ (Selling Price - Cost Price) - Discounts ]'),

            Stat::make('Best Selling Product', $this->getTopSellingProductsData()->first()?->product?->name)
                ->description("Net quantity: {$this->getTopSellingProductsData()->first()?->qty_sold} {$this->getTopSellingProductsData()->first()?->product?->unit?->symbol}"),

            Stat::make('Most Returned Product', $this->mostReturnedProductsData()->first()?->product?->name)
                ->description("Total returned: {$this->mostReturnedProductsData()->first()?->qty_sold} {$this->mostReturnedProductsData()->first()?->product?->unit?->symbol}"),
        ];
    }
}
