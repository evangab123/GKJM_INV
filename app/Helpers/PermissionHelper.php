<?php

namespace App\Helpers;

use App\Models\Pengguna;
use App\Models\Ruang;
use Illuminate\Support\Facades\Auth;

class PermissionHelper
{   
    public static function getRoomList()
    {
        $ruangs = Ruang::all();
        $permruangs = ['semua'];

        // Add all room names to the list
        foreach ($ruangs as $room) {
            $permruangs[] = strtolower($room->nama_ruang);
        }

        return $permruangs;
    }

    public static function AnyHasAccessToBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }

        $permruangs = self::getRoomList();
        $hasAccess = false;

        foreach ($permruangs as $room) {
            if (
                $pengguna->can('lihat-barang-' . $room) ||
                $pengguna->can('semua-barang-' . $room) ||
                $pengguna->can('semua-semua-' . $room)
            ) {
                $hasAccess = true;
                break;
            }
        }

        return ['access' => $hasAccess, 'room' => $permruangs];
    }


    public static function AnyCanCreateBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canMake = false;

        foreach ($permruangs as $room) {
            if (
                $pengguna->can('buat-barang-' . $room) ||
                $pengguna->can('semua-barang-' . $room) ||
                $pengguna->can('semua-semua-' . $room)
            ) {
                $canMake = true;
                break;
            }
        }

        return ['buat' => $canMake, 'room' => $permruangs];
    }
    public static function AnyCanEditBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canEdit = false;

        foreach ($permruangs as $room) {
            if (
                $pengguna->can('perbarui-barang-' . $room) ||
                $pengguna->can('semua-barang-' . $room) ||
                $pengguna->can('semua-semua-' . $room)
            ) {
                $canEdit = true;
                break;
            }
        }

        return ['edit' => $canEdit, 'room' => $permruangs];
    }

    public static function AnyCanDeleteBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canDelete = false;

        foreach ($permruangs as $room) {
            if (
                $pengguna->can('hapus-barang-' . $room) ||
                $pengguna->can('semua-barang-' . $room) ||
                $pengguna->can('semua-semua-' . $room)
            ) {
                $canDelete = true;
                break;
            }
        }

        return ['delete' => $canDelete, 'room' => $permruangs];
    }
}
