<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string|max:1000',
        ]);

        // Kirim email (optional)
        // Pastikan MAIL_ sudah dikonfigurasi di .env
        Mail::raw($request->message, function ($msg) use ($request) {
            $msg->to('support@sonvape.com')
                ->subject('Pesan dari ' . $request->name)
                ->replyTo($request->email);
        });

        return back()->with('success', 'Pesan Anda berhasil dikirim!');
    }
}
