<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('produit')->paginate(10); // Récupère les stocks avec pagination

        return view('admin.stock.index', compact('stocks'));
    }
}
