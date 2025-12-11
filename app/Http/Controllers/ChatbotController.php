<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Pastikan nama model sesuai (Produk atau Product)

class ChatbotController extends Controller
{
    public function send(Request $request)
    {
        $message = strtolower($request->message); 
        $reply = "Saya adalah chatbot StoryGlass yang membantu merekomendasikan kacamata sesuai bentuk wajah kamu. 
Cukup ketik salah satu bentuk wajah seperti: oval, bulat, persegi, hati, berlian, segitiga, panjang, atau lonjong.";
;
        $product = null;

        // Mapping bentuk wajah ke produk
        $mapping = [
            'oval' => 'Kacamata Retro',
            'bulat' => 'Kacamata Modern',
            'persegi' => 'Kacamata Stylish',
            'hati' => 'Kacamata Stylish',
            'berlian' => 'Kacamata Retro',
            'segitiga' => 'Kacamata Retro',
            'panjang' => 'Kacamata Stylish',
            'lonjong' => 'Kacamata Modern'
        ];

        // Cari kata kunci di input user
        foreach ($mapping as $shape => $productName) {
            if (str_contains($message, $shape)) {
                $reply = "Rekomendasi kacamata untuk wajah $shape adalah:";
                $product = Product::where('nama', 'LIKE', "%$productName%")->first();
                break;
            }
        }

        return response()->json([
            'reply' => $reply,
            'product' => $product
        ]);
    }
}
