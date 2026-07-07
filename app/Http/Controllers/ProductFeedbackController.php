<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;

class ProductFeedbackController extends Controller
{
    public function index()
    {
        $query = Product::withCount('reviews')
            ->with(['reviews.user', 'category'])
            ->has('reviews')
            ->orderByDesc('reviews_count');

        $products = $query->paginate(10);

        $summary = [
            'products_with_reviews' => Product::has('reviews')->count(),
            'total_comments' => Review::count(),
        ];

        return view('admin.product-feedback.index', compact('products', 'summary'));
    }
}
