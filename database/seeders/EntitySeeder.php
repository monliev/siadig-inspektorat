<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EntitySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Entity::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- 1. Data Induk Level 1 (OPD & Kecamatan) ---
        $topLevelEntities = [
            ['name' => 'Sekretariat Daerah', 'type' => 'OPD'],
            ['name' => 'Sekretariat DPRD', 'type' => 'OPD'],
            ['name' => 'Inspektorat', 'type' => 'OPD'],
            ['name' => 'Dinas Pendidikan, Pemuda dan Olahraga', 'type' => 'OPD'],
            ['name' => 'Dinas Kesehatan, Pengendalian Penduduk dan Keluarga Berencana', 'type' => 'OPD'],
            ['name' => 'Dinas Pekerjaan Umum dan Penataan Ruang', 'type' => 'OPD'],
            ['name' => 'Dinas Perumahan, Kawasan Permukiman dan Lingkungan Hidup', 'type' => 'OPD'],
            ['name' => 'Satuan Polisi Pamong Praja dan Kebakaran', 'type' => 'OPD'],
            ['name' => 'Dinas Sosial, Pemberdayaan Perempuan dan Perlindungan Anak', 'type' => 'OPD'],
            ['name' => 'Dinas Perindustrian dan Tenaga Kerja', 'type' => 'OPD'],
            ['name' => 'Dinas Pertanian dan Pangan', 'type' => 'OPD'],
            ['name' => 'Dinas Kependudukan Dan Pencatatan Sipil', 'type' => 'OPD'],
            ['name' => 'Dinas Pemberdayaan Masyarakat dan Desa', 'type' => 'OPD'],
            ['name' => 'Dinas Perhubungan', 'type' => 'OPD'],
            ['name' => 'Dinas Komunikasi dan Informatika', 'type' => 'OPD'],
            ['name' => 'Dinas Koperasi Dan Usaha Mikro dan Perdagangan', 'type' => 'OPD'],
            ['name' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu', 'type' => 'OPD'],
            ['name' => 'Dinas Kearsipan dan Perpustakaan', 'type' => 'OPD'],
            ['name' => 'Dinas Perikanan', 'type' => 'OPD'],
            ['name' => 'Dinas Pariwisata dan Kebudayaan', 'type' => 'OPD'],
            ['name' => 'Dinas Peternakan', 'type' => 'OPD'],
            ['name' => 'Badan Perencanaan Pembangunan, Penelitian dan Pengembangan Daerah', 'type' => 'OPD'],
            ['name' => 'Badan Keuangan Daerah', 'type' => 'OPD'],
            ['name' => 'Badan Kepegawaian Daerah', 'type' => 'OPD'],
            ['name' => 'Badan Penanggulangan Bencana Daerah', 'type' => 'OPD'],
            ['name' => 'Badan Kesatuan Bangsa dan Politik', 'type' => 'OPD'],
            ['name' => 'RSUD Dr. Soedomo', 'type' => 'OPD'],
            ['name' => 'Kecamatan Bendungan', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Dongko', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Durenan', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Gandusari', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Kampak', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Karangan', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Munjungan', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Panggul', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Pogalan', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Pule', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Suruh', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Trenggalek', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Tugu', 'type' => 'Kecamatan'],
            ['name' => 'Kecamatan Watulimo', 'type' => 'Kecamatan'],
        ];
        foreach ($topLevelEntities as $data) {
            Entity::create($data);
        }

        // --- 2. Data Anak Level 2 (Bagian, Puskesmas, RSUD) ---
        $setda = Entity::where('name', 'Sekretariat Daerah')->first();
        $dinkes = Entity::where('name', 'Dinas Kesehatan, Pengendalian Penduduk dan Keluarga Berencana')->first();

        $bagianList = ['Bagian Administrasi Pembangunan', 'Bagian Hukum', 'Bagian Kesejahteraan Rakyat', 'Bagian Organisasi', 'Bagian Pemerintahan', 'Bagian Pengadaan Barang dan Jasa', 'Bagian Perekonomian dan Sumber Daya Alam'];
        foreach ($bagianList as $name) {
            Entity::create(['name' => $name, 'type' => 'OPD', 'parent_id' => $setda->id]);
        }

        $dinkesAnak = ['RSUD PANGGUL', 'Puskesmas Panggul', 'Puskesmas Bodag', 'Puskesmas Munjungan', 'Puskesmas Watulimo', 'Puskesmas Slawe', 'Puskesmas Kampak', 'Puskesmas Dongko', 'Puskesmas Pandean', 'Puskesmas Pule', 'Puskesmas Karangan', 'Puskesmas Suruh', 'Puskesmas Gandusari', 'Puskesmas Karanganyar', 'Puskesmas Durenan', 'Puskesmas Baruharjo', 'Puskesmas Pogalan', 'Puskesmas Ngulankulon', 'Puskesmas Trenggalek', 'Puskesmas Rejowinangun', 'Puskesmas Tugu', 'Puskesmas Pucanganak', 'Puskesmas Bendungan'];
        foreach ($dinkesAnak as $name) {
            Entity::create(['name' => $name, 'type' => 'OPD', 'parent_id' => $dinkes->id]);
        }

        // --- 3. Data Desa (Anak dari Kecamatan) ---
        $kecamatanLookup = Entity::where('type', 'Kecamatan')->pluck('id', 'name');
        $desaList = [
            'Bendungan,Botoputih', 'Bendungan,Depok', 'Bendungan,Dompyong', 'Bendungan,Masaran', 'Bendungan,Sengon', 'Bendungan,Srabah', 'Bendungan,Sumurup', 'Bendungan,Surenlor',
            'Dongko,Cakul', 'Dongko,Dongko', 'Dongko,Ngerdani', 'Dongko,Pandean', 'Dongko,Petung', 'Dongko,Pringapus', 'Dongko,Salamwates', 'Dongko,Siki', 'Dongko,Sumberbening', 'Dongko,Watuagung',
            'Durenan,Baruharjo', 'Durenan,Durenan', 'Durenan,Gador', 'Durenan,Kamulan', 'Durenan,Karanganom', 'Durenan,Kendalrejo', 'Durenan,Malasan', 'Durenan,Ngadisuko', 'Durenan,Pakis', 'Durenan,Pandean', 'Durenan,Panggungsari', 'Durenan,Semarum', 'Durenan,Sumberejo', 'Durenan,Sumbergayam',
            'Gandusari,Gandusari', 'Gandusari,Jajar', 'Gandusari,Karanganyar', 'Gandusari,Krandegan', 'Gandusari,Melis', 'Gandusari,Ngrayung', 'Gandusari,Sukorame', 'Gandusari,Sukorejo', 'Gandusari,Widoro', 'Gandusari,Wonoanti', 'Gandusari,Wonorejo',
            'Kampak,Bendoagung', 'Kampak,Bogoran', 'Kampak,Karangrejo', 'Kampak,Ngadimulyo', 'Kampak,Senden', 'Kampak,Sugihan', 'Kampak,Timahan',
            'Karangan,Buluagung', 'Karangan,Jati', 'Karangan,Jatiprahu', 'Karangan,Karangan', 'Karangan,Kayen', 'Karangan,Kedungsigit', 'Karangan,Kerjo', 'Karangan,Ngentrong', 'Karangan,Salamrejo', 'Karangan,Sukowetan', 'Karangan,Sumber', 'Karangan,Sumberingin',
            'Munjungan,Bangun', 'Munjungan,Bendoroto', 'Munjungan,Besuki', 'Munjungan,Craken', 'Munjungan,Karangturi', 'Munjungan,Masaran', 'Munjungan,Munjungan', 'Munjungan,Ngulungkulon', 'Munjungan,Ngulungwetan', 'Munjungan,Sobo', 'Munjungan,Tawing',
            'Panggul,Banjar', 'Panggul,Barang', 'Panggul,Besuki', 'Panggul,Bodag', 'Panggul,Depok', 'Panggul,Gayam', 'Panggul,Karangtengah', 'Panggul,Kertosono', 'Panggul,Manggis', 'Panggul,Nglebeng', 'Panggul,Ngrambingan', 'Panggul,Ngrencak', 'Panggul,Panggul', 'Panggul,Sawahan', 'Panggul,Tangkil', 'Panggul,Terbis', 'Panggul,Wonocoyo',
            'Pogalan,Bendorejo', 'Pogalan,Gembleb', 'Pogalan,Kedunglurah', 'Pogalan,Ngadirejo', 'Pogalan,Ngadirenggo', 'Pogalan,Ngetal', 'Pogalan,Ngulankulon', 'Pogalan,Ngulanwetan', 'Pogalan,Pogalan', 'Pogalan,Wonocoyo',
            'Pule,Joho', 'Pule,Jombok', 'Pule,Karanganyar', 'Pule,Kembangan', 'Pule,Pakel', 'Pule,Pule', 'Pule,Puyung', 'Pule,Sidomulyo', 'Pule,Sukokidul', 'Pule,Tanggaran',
            'Suruh,Gamping', 'Suruh,Mlinjon', 'Suruh,Nglebo', 'Suruh,Ngrandu', 'Suruh,Puru', 'Suruh,Suruh', 'Suruh,Wonokerto',
            'Trenggalek,Ngantru', 'Trenggalek,Tamanan', 'Trenggalek,Kelutan', 'Trenggalek,Sumbergedong', 'Trenggalek,Surodakan', 'Trenggalek,Sambirejo', 'Trenggalek,Parakan', 'Trenggalek,Rejowinangun', 'Trenggalek,Dawuhan', 'Trenggalek,Sukosari', 'Trenggalek,Karangsoko', 'Trenggalek,Ngares', 'Trenggalek,Sumberdadi',
            'Tugu,Banaran', 'Tugu,Dermosari', 'Tugu,Duren', 'Tugu,Gading', 'Tugu,Gondang', 'Tugu,Jambu', 'Tugu,Ngepeh', 'Tugu,Nglinggis', 'Tugu,Nglongsor', 'Tugu,Prambon', 'Tugu,Pucanganak', 'Tugu,Sukorejo', 'Tugu,Tegaren', 'Tugu,Tumpuk', 'Tugu,Winong',
            'Watulimo,Dukuh', 'Watulimo,Gemaharjo', 'Watulimo,Karanggandu', 'Watulimo,Margomulyo', 'Watulimo,Ngembel', 'Watulimo,Pakel', 'Watulimo,Prigi', 'Watulimo,Sawahan', 'Watulimo,Slawe', 'Watulimo,Tasikmadu', 'Watulimo,Watuagung', 'Watulimo,Watulimo',
        ];
        foreach ($desaList as $item) {
            list($kecamatanName, $desaName) = explode(',', $item);
            $kecamatanId = $kecamatanLookup['Kecamatan ' . Str::title($kecamatanName)] ?? null;
            if ($kecamatanId) {
                $prefixedDesaName = (Str::startsWith(strtolower($desaName), 'kelurahan')) ? Str::title($desaName) : 'Desa ' . Str::title($desaName);
                Entity::create(['name' => $prefixedDesaName, 'type' => 'Desa', 'parent_id' => $kecamatanId]);
            }
        }
    }
}