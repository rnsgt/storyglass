<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $addresses = $user->addresses()->latest()->get();
        
        return view('profile.index', compact('user', 'addresses'));
    }

    /**
     * Tampilkan form edit profil
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profil user
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update nama dan email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Jika user ingin ganti password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }
            
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    // Tampilkan form tambah alamat
    public function createAddress()
    {
        return view('profile.addresses.create');
    }

    /**
     * Simpan alamat baru
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'nama_penerima' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Jika ini adalah alamat pertama atau diset sebagai default
        if ($request->has('is_default') || $user->addresses()->count() === 0) {
            // Set semua alamat lain jadi bukan default
            $user->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $user->addresses()->create($validated);

        return redirect()->route('profile.index')->with('success', 'Alamat berhasil ditambahkan!');
    }

    // Tampilkan form edit alamat
    public function editAddress(UserAddress $address)
    {
        // Pastikan alamat ini milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('profile.addresses.edit', compact('address'));
    }

    // Update alamat
    public function updateAddress(Request $request, UserAddress $address)
    {
        // Pastikan alamat ini milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'nama_penerima' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'is_default' => 'boolean',
        ]);

        // Jika diset sebagai default
        if ($request->has('is_default')) {
            // Set semua alamat lain jadi bukan default
            /** @var User $authUser */
            $authUser = Auth::user();
            $authUser->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $address->update($validated);

        return redirect()->route('profile.index')->with('success', 'Alamat berhasil diperbarui!');
    }

    // Hapus alamat
    public function destroyAddress(UserAddress $address)
    {
        // Pastikan alamat ini milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $isDefault = $address->is_default;
        $address->delete();

        // Jika yang dihapus adalah alamat default, set alamat pertama sebagai default
        if ($isDefault) {
            /** @var User $authUser */
            $authUser = Auth::user();
            $firstAddress = $authUser->addresses()->first();
            if ($firstAddress) {
                $firstAddress->update(['is_default' => true]);
            }
        }

        return redirect()->route('profile.index')->with('success', 'Alamat berhasil dihapus!');
    }

    // Set alamat sebagai default
    public function setDefaultAddress(UserAddress $address)
    {
        // Pastikan alamat ini milik user yang login
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        // Set semua alamat lain jadi bukan default
        /** @var User $authUser */
        $authUser = Auth::user();
        $authUser->addresses()->update(['is_default' => false]);
        
        // Set alamat ini sebagai default
        $address->update(['is_default' => true]);

        return redirect()->route('profile.index')->with('success', 'Alamat default berhasil diubah!');
    }
}
