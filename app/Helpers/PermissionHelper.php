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
            $permruangs[] = $room->nama_ruang;
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
        $ruangs=[];

        foreach ($permruangs as $room) {
            if (
                $pengguna->can('lihat-barang-' . strtolower($room)) ||
                $pengguna->can('semua-barang-' . strtolower($room)) ||
                $pengguna->can('semua-semua-' . strtolower($room))
            ) {
                $hasAccess = true;
                $ruangs[]=$room;
            }
        }
        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['access' => $hasAccess, 'room' => $ruangs];
    }


    public static function AnyCanCreateBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canMake = false;
        $ruangs=[];
        foreach ($permruangs as $room) {
            if (
                $pengguna->can('buat-barang-' . strtolower($room)) ||
                $pengguna->can('semua-barang-' . strtolower($room)) ||
                $pengguna->can('semua-semua-' . strtolower($room))
            ) {
                $canMake = true;
                $ruangs[]=$room;
            }
        }
        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['buat' => $canMake, 'room' => $ruangs];
    }
    public static function AnyCanEditBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canEdit = false;
        $ruangs = [];

        foreach ($permruangs as $room) {
            if (
                $pengguna->can('perbarui-barang-' . strtolower($room)) ||
                $pengguna->can('semua-barang-' . strtolower($room)) ||
                $pengguna->can('semua-semua-' . strtolower($room))
            ) {
                $canEdit = true;
                $ruangs[]=$room;
            }
        }
        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['edit' => $canEdit, 'room' => $ruangs];
    }

    public static function AnyCanDeleteBarang()
    {
        $pengguna = Auth::user();
        if (!$pengguna) {
            return false;
        }
        $permruangs = self::getRoomList();
        $canDelete = false;
        $ruangs=[];

        foreach ($permruangs as $room) {
            if (
                $pengguna->can('hapus-barang-' . strtolower($room)) ||
                $pengguna->can('semua-barang-' . strtolower($room))||
                $pengguna->can('semua-semua-' . strtolower($room))
            ) {
                $canDelete = true;
                $ruangs[]=$room;
            }
        }
        if (count($ruangs) === 1 && in_array('semua', $ruangs, true)) {
            $ruangs = [];
        }

        return ['delete' => $canDelete, 'room' => $ruangs];
    }
}
