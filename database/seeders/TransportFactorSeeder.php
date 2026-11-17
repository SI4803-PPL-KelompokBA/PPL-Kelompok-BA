<?php
namespace Database\Seeders;

/**
 * Sumber: UK Government GHG Conversion Factors for Company Reporting 2025
 * (DESNZ/DEFRA, Juni 2025), kategori Road Freight. GWP: AR5.
 * Hanya emisi pembakaran bahan bakar (tanpa well-to-tank). Scope 3.
 * Catatan: rata-rata Inggris, bukan data kendaraan Indonesia.
 */
class TransportFactorSeeder extends FactorSeeder
{
    protected array $rows = [
        // DEFRA 2025: "Average van - (up to 3.5 tonnes) - freighting goods"
        ['Transportasi Lokal', 'Pickup / Van Kecil (s.d. 3,5 ton)', 3, 0.63823, 'kg CO2e/ton-km'],

        // DEFRA 2025: "Road freight diesel rigids HGV average Laden"
        ['Transportasi Lokal', 'Truk Rigid Diesel (HGV, beban rata-rata)', 3, 0.19748, 'kg CO2e/ton-km'],

        // TODO (PM Kias): belum ada angka per ton-km yang terverifikasi. Jangan isi dummy.
        // ['Transportasi Lokal', 'Truk Engkel',               3, ???, 'kg CO2e/ton-km'],
        // ['Transportasi Lokal', 'Truk Tronton / Artikulasi', 3, ???, 'kg CO2e/ton-km'],
    ];
}