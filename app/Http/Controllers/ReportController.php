<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Best selling products
     * GET /api/reports/best-selling-products
     */
    public function bestSellingProducts(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());

        $products = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                            ->join('products', 'order_items.product_id', '=', 'products.id')
                            ->whereBetween('orders.created_at', [$startDate, $endDate])
                            ->where('orders.status', 'completed')
                            ->select(
                                'products.id',
                                'products.name',
                                'products.category_id',
                                DB::raw('SUM(order_items.quantity) as total_sold'),
                                DB::raw('SUM(order_items.subtotal) as total_revenue')
                            )
                            ->groupBy('products.id', 'products.name', 'products.category_id')
                            ->orderBy('total_sold', 'desc')
                            ->limit(10)
                            ->get();

        return response()->json($products);
    }

    /**
     * Low stock products
     * GET /api/reports/low-stock-products
     */
    public function lowStockProducts(Request $request)
    {
        $threshold = $request->get('threshold', 5);

        $products = Product::where('stock', '<=', $threshold)
                          ->where('status', 'active')
                          ->with('category')
                          ->orderBy('stock', 'asc')
                          ->get();

        return response()->json($products);
    }

    /**
     * Sales report
     * GET /api/reports/sales
     */
    public function salesReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now());

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed')
                      ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Daily breakdown
        $dailyBreakdown = Order::whereBetween('created_at', [$startDate, $endDate])
                              ->where('status', 'completed')
                              ->select(
                                  DB::raw('DATE(created_at) as date'),
                                  DB::raw('COUNT(*) as orders_count'),
                                  DB::raw('SUM(total_amount) as revenue')
                              )
                              ->groupBy('date')
                              ->orderBy('date', 'asc')
                              ->get();

        return response()->json([
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'average_order_value' => $averageOrderValue
            ],
            'daily_breakdown' => $dailyBreakdown
        ]);
    }

    /**
     * Category performance
     * GET /api/reports/category-performance
     */
    public function categoryPerformance(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30));
        $endDate = $request->get('end_date', now());

        $categories = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                              ->join('products', 'order_items.product_id', '=', 'products.id')
                              ->join('categories', 'products.category_id', '=', 'categories.id')
                              ->whereBetween('orders.created_at', [$startDate, $endDate])
                              ->where('orders.status', 'completed')
                              ->select(
                                  'categories.id',
                                  'categories.name',
                                  DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                                  DB::raw('SUM(order_items.quantity) as total_items_sold'),
                                  DB::raw('SUM(order_items.subtotal) as total_revenue')
                              )
                              ->groupBy('categories.id', 'categories.name')
                              ->orderBy('total_revenue', 'desc')
                              ->get();

        return response()->json($categories);
    }
}