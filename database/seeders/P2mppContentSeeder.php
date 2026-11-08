<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Page;
use Illuminate\Database\Seeder;

class P2mppContentSeeder extends Seeder
{
    private const SOURCE = 'https://p2mpp.polinema.ac.id';

    public function run(): void
    {
        $pages = [
            [
                'slug' => 'visi-misi',
                'title' => 'Visi dan Misi',
                'title_en' => 'Vision and Mission',
                'source' => '/tampilan/visimisi',
                'content' => '',
                'content_en' => '',
                'is_published' => false,
            ],
            [
                'slug' => 'kebijakan-mutu',
                'title' => 'Kebijakan Mutu',
                'title_en' => 'Quality Policy',
                'source' => '/tampilan/quality',
                'content' => '<p>Kebijakan mutu POLINEMA mencakup peningkatan kualitas pendidikan, penelitian dan pengabdian, kerja sama, pengembangan mahasiswa, serta tata kelola dan layanan.</p><ul><li>Pendidikan berfokus pada evaluasi dan perbaikan berkelanjutan, mutu program studi, dan pengembangan sivitas akademika.</li><li>Penelitian, pengabdian, dan kerja sama diarahkan pada kegiatan inovatif serta kemitraan dengan pemangku kepentingan.</li><li>Pengembangan mahasiswa meliputi kemampuan akademik, kesejahteraan, bakat, karier, dan jejaring alumni.</li><li>Tata kelola menekankan sasaran mutu, prosedur dan pengendalian dokumen, citra institusi, layanan, serta sistem informasi.</li></ul>',
                'content_en' => '<p>POLINEMA quality policy covers improvements in education, research and community service, partnerships, student development, governance, and services.</p><ul><li>Education focuses on continuous review, study-program quality, and academic community development.</li><li>Research, community service, and partnerships support innovative work and stakeholder collaboration.</li><li>Student development includes academic skills, wellbeing, talent, careers, and alumni networks.</li><li>Governance emphasizes quality targets, documented procedures, institutional reputation, services, and information systems.</li></ul>',
            ],
            [
                'slug' => 'tugas-dan-fungsi-utama',
                'title' => 'Tugas dan Fungsi Utama',
                'title_en' => 'Main Duties and Functions',
                'source' => '/tampilan/duties',
                'content' => '<p>Kantor Jaminan Mutu (KJM), yang kini dikenal sebagai Pusat Penjaminan Mutu dan Pengembangan Pembelajaran (P2MPP), merupakan wujud komitmen Politeknik Negeri Malang untuk menjaga dan meningkatkan kualitas pendidikan.</p><h2>Riwayat Sistem Manajemen Mutu</h2><p>Dalam pelaksanaan KJM, Politeknik Negeri Malang memperoleh sertifikat ISO 9001:2008 untuk implementasi sistem manajemen mutu. Cakupannya meliputi layanan akademik program Diploma III pada jurusan/program studi Teknik Listrik, Teknik Elektronika, Teknik Telekomunikasi, Manajemen Informatika, Teknik Mesin, Teknik Sipil, Teknik Kimia, Akuntansi, dan Administrasi Niaga, serta unit pendukung BAUK, BAAK, dan unit pelayanan teknis (UPT). Informasi ini merupakan catatan historis dan tidak menyatakan status sertifikasi saat ini.</p><h2>Dokumen Mutu</h2><p>Dokumen yang dihasilkan dalam sistem pengelolaan mutu terdiri dari:</p><ul><li><strong>Level I:</strong> Pedoman Mutu.</li><li><strong>Level II:</strong> Prosedur Mutu.</li><li><strong>Level III:</strong> Instruksi kerja atau Standard Operating Procedure (SOP).</li><li><strong>Level IV:</strong> Formulir isian, catatan mutu, dan rekaman mutu.</li></ul><h2>Kegiatan KJM</h2><ul><li>Audit Internal.</li><li>Pengendalian Dokumen ISO.</li><li>Pengukuran Kepuasan Pelanggan.</li><li>Pengendalian Matriks Kompetensi.</li><li>Workshop Layanan Prima.</li><li>Diseminasi dan Publikasi KJM.</li><li>Seminar ISO 9001:2008.</li><li>Pelatihan Audit Internal.</li></ul>',
                'content_en' => '<p>The Quality Assurance Office (KJM), now known as the Center for Quality Assurance and Learning Development (P2MPP), reflects Malang State Polytechnic’s commitment to maintaining and improving the quality of education.</p><h2>Quality Management System History</h2><p>During KJM’s implementation, Malang State Polytechnic received ISO 9001:2008 certification for its quality management system. Its scope covered academic services for Diploma III programs in Electrical Engineering, Electronics Engineering, Telecommunications Engineering, Informatics Management, Mechanical Engineering, Civil Engineering, Chemical Engineering, Accounting, and Business Administration, as well as the BAUK, BAAK, and technical service units (UPT). This is historical information and does not state the current certification status.</p><h2>Quality Documents</h2><p>Documents produced as part of the quality management system include:</p><ul><li><strong>Level I:</strong> Quality Manual.</li><li><strong>Level II:</strong> Quality Procedures.</li><li><strong>Level III:</strong> Work instructions or Standard Operating Procedures (SOPs).</li><li><strong>Level IV:</strong> Forms, quality notes, and quality records.</li></ul><h2>KJM Activities</h2><ul><li>Internal Audit.</li><li>ISO Document Control.</li><li>Customer Satisfaction Measurement.</li><li>Competency Matrix Control.</li><li>Excellent Service Workshop.</li><li>KJM Dissemination and Publication.</li><li>ISO 9001:2008 Seminar.</li><li>Internal Auditor Training.</li></ul>',
            ],
            [
                'slug' => 'standar-kualitas-internal',
                'title' => 'Standar Kualitas Internal',
                'title_en' => 'Internal Quality Standards',
                'source' => '/tampilan/spmi_list',
                'content' => '<p>Situs sumber mengelompokkan 42 standar mutu internal POLINEMA ke dalam empat kelompok.</p><ul><li>8 standar pembelajaran.</li><li>8 standar penelitian.</li><li>8 standar pengabdian kepada masyarakat.</li><li>12 standar internal derivatif, termasuk perpustakaan, sistem informasi, kemahasiswaan, kerja sama, dan e-learning.</li></ul>',
                'content_en' => '<p>The source site groups POLINEMA’s 42 internal quality standards into four areas.</p><ul><li>8 learning standards.</li><li>8 research standards.</li><li>8 community-service standards.</li><li>12 derivative internal standards, including library, information systems, student affairs, partnerships, and e-learning.</li></ul>',
            ],
            [
                'slug' => 'kepuasan-pelanggan',
                'title' => 'Kepuasan Pelanggan',
                'title_en' => 'Customer Satisfaction',
                'source' => '/tampilan/customer',
                'content' => '<p>Materi kepuasan pelanggan pada situs P2MPP mencakup mahasiswa, orang tua, dosen dan staf, serta mitra. Data mahasiswa dikelompokkan menurut jurusan dan lokasi program, termasuk PDD dan PSDKU.</p><p>Ringkasan ini tidak memuat angka hasil survei; lihat situs sumber untuk data dan grafik per kelompok.</p>',
                'content_en' => '<p>P2MPP’s customer-satisfaction section covers students, parents, lecturers and staff, and partners. Student materials are organized by department and program location, including PDD and PSDKU campuses.</p><p>This summary does not reproduce survey figures; consult the source site for group-specific data and charts.</p>',
            ],
            [
                'slug' => 'akreditasi-institusi',
                'title' => 'Akreditasi Institusi',
                'title_en' => 'Institutional Accreditation',
                'source' => '/tampilan/accreditation#institution',
                'content' => '<p>Menurut daftar akreditasi yang ditampilkan P2MPP, Politeknik Negeri Malang memperoleh peringkat Unggul pada 2025 dengan masa berlaku hingga 4 Juni 2030. Halaman sumber juga menyediakan tautan ke sertifikat dan riwayat akreditasi institusi.</p>',
                'content_en' => '<p>According to the accreditation listing on P2MPP, Politeknik Negeri Malang received an Unggul rating in 2025, valid through 4 June 2030. The source page also links to the certificate and earlier institutional accreditation records.</p>',
            ],
            [
                'slug' => 'akreditasi-program-studi',
                'title' => 'Akreditasi Program Studi',
                'title_en' => 'Study Program Accreditation',
                'source' => '/tampilan/accreditation#prodi',
                'content' => '<p>Situs P2MPP memuat daftar akreditasi program studi beserta nomor sertifikat, peringkat, dan masa berlakunya untuk beberapa jenjang pendidikan. Rujuk tabel resmi untuk status dan dokumen terbaru setiap program.</p>',
                'content_en' => '<p>P2MPP lists study-program accreditation certificates, ratings, and validity periods across multiple education levels. Refer to the official table for each program’s latest status and documents.</p>',
            ],
        ];

        foreach ($pages as $data) {
            $source = self::SOURCE.$data['source'];
            $content = $data['content'].'<p><small>Sumber: <a href="'.$source.'">situs resmi P2MPP</a></small></p>';
            $contentEn = $data['content_en'].'<p><small>Source: <a href="'.$source.'">official P2MPP site</a></small></p>';

            Page::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title' => $data['title'],
                    'title_en' => $data['title_en'],
                    'content' => $content,
                    'content_en' => $contentEn,
                    'is_published' => $data['is_published'] ?? true,
                ],
            );
        }

        Page::updateOrCreate(
            ['slug' => 'visi-misi-polinema'],
            [
                'title' => 'Visi dan Misi Polinema',
                'title_en' => 'POLINEMA Vision and Mission',
                'content' => '<p class="text-sm text-slate-500">Informasi institusi Polinema, bukan visi-misi KJM.</p><h2 class="mb-3 mt-8 text-xl font-bold text-slate-900">Visi</h2><blockquote class="my-0 border-l-4 border-sky-200 bg-sky-50 px-6 py-6 text-slate-700 italic md:px-9 md:py-7"><p class="m-0 leading-8">Menjadi Lembaga Pendidikan Tinggi Vokasi yang Unggul dalam Persaingan Global</p></blockquote><h2 class="mb-3 mt-6 text-xl font-bold text-slate-900">Misi</h2><blockquote class="my-0 border-l-4 border-sky-200 bg-sky-50 px-6 py-6 text-slate-700 italic md:px-9 md:py-7"><ol class="m-0 list-decimal space-y-1 pl-5 leading-7"><li class="pl-1">Menyelenggarakan dan mengembangkan pendidikan tinggi vokasi yang unggul, inovatif, dan berdaya saing global</li><li class="pl-1">Menyelenggarakan penelitian terapan dan pengabdian kepada masyarakat yang bermanfaat bagi pengembangan ilmu pengetahuan dan teknologi serta kesejahteraan masyarakat</li><li class="pl-1">Menyelenggarakan sistem pengelolaan pendidikan dengan berdasar pada prinsip-prinsip tata kelola yang baik</li><li class="pl-1">Mengembangkan kerja sama yang saling menguntungkan dengan berbagai pihak baik di dalam maupun di luar negeri pada bidang-bidang yang relevan</li></ol></blockquote><p class="mt-6 text-xs text-slate-500"><small>Sumber: <a class="font-semibold text-indigo-700 underline decoration-indigo-200 underline-offset-2 hover:text-orange-600" href="https://www.polinema.ac.id/profil/visi-misi/">situs resmi Polinema</a></small></p>',
                'content_en' => '<p class="text-sm text-slate-500">Institutional information for POLINEMA, not a KJM vision and mission.</p><h2 class="mb-3 mt-8 text-xl font-bold text-slate-900">Vision</h2><blockquote class="my-0 border-l-4 border-sky-200 bg-sky-50 px-6 py-6 text-slate-700 italic md:px-9 md:py-7"><p class="m-0 leading-8">To become a vocational higher education institution that excels in global competition</p></blockquote><h2 class="mb-3 mt-6 text-xl font-bold text-slate-900">Mission</h2><blockquote class="my-0 border-l-4 border-sky-200 bg-sky-50 px-6 py-6 text-slate-700 italic md:px-9 md:py-7"><ol class="m-0 list-decimal space-y-1 pl-5 leading-7"><li class="pl-1">Provide and develop excellent, innovative vocational higher education with global competitiveness</li><li class="pl-1">Conduct applied research and community service that benefit science, technology, and community welfare</li><li class="pl-1">Organize education management based on good governance principles</li><li class="pl-1">Develop mutually beneficial cooperation with relevant domestic and international partners</li></ol></blockquote><p class="mt-6 text-xs text-slate-500"><small>Source: <a class="font-semibold text-indigo-700 underline decoration-indigo-200 underline-offset-2 hover:text-orange-600" href="https://www.polinema.ac.id/profil/visi-misi/">official POLINEMA website</a></small></p>',
                'is_published' => true,
            ],
        );

        Page::updateOrCreate(
            ['slug' => 'jurusan-polinema'],
            [
                'title' => 'Jurusan dan Program Studi Polinema',
                'title_en' => 'POLINEMA Departments and Study Programs',
                'content' => '<p>Politeknik Negeri Malang berdiri pada 9 Februari 1982. Pada awal berdirinya, Polinema memiliki empat jurusan: Teknik Listrik, Teknik Elektronika, Teknik Mesin, dan Teknik Sipil. Seiring dengan tuntutan dunia global, jurusan di Polinema terus bertambah.</p><p>Saat ini Polinema memiliki tujuh jurusan dengan 46 program studi pada jenjang Diploma II (D-II), Diploma III (D-III), Diploma IV/Sarjana Terapan (D-IV), dan Magister Terapan (S-2).</p><h2>Program Studi Kampus Utama</h2><ul><li>1 program studi D-II.</li><li>11 program studi D-III.</li><li>17 program studi D-IV.</li><li>4 program studi Magister Terapan (S-2).</li></ul><h2>Program Studi di Luar Kampus Utama (PSDKU)</h2><ul><li><strong>Kediri:</strong> 3 program studi D-III dan 3 program studi D-IV.</li><li><strong>Lumajang:</strong> 3 program studi D-III dan 1 program studi D-IV.</li><li><strong>Pamekasan:</strong> 1 program studi D-III dan 2 program studi D-IV.</li></ul><p>Polinema memiliki tujuh jurusan:</p><ul><li>Teknik Elektro</li><li>Teknik Mesin</li><li>Teknik Sipil</li><li>Akuntansi</li><li>Administrasi Niaga</li><li>Teknik Kimia</li><li>Teknologi Informasi</li></ul><p><small>Sumber: <a href="https://www.polinema.ac.id/jurusan/">situs resmi Polinema</a></small></p>',
                'content_en' => '<p>Malang State Polytechnic was established on February 9, 1982. At its founding, POLINEMA had four departments: Electrical Engineering, Electronics Engineering, Mechanical Engineering, and Civil Engineering. Its departments have continued to grow in response to global needs.</p><p>POLINEMA now has seven departments and 46 study programs at Diploma II (D-II), Diploma III (D-III), Diploma IV/Applied Bachelor (D-IV), and Applied Master’s (S-2) levels.</p><h2>Main Campus Study Programs</h2><ul><li>1 D-II study program.</li><li>11 D-III study programs.</li><li>17 D-IV study programs.</li><li>4 Applied Master’s (S-2) study programs.</li></ul><h2>Off-Campus Main Programs (PSDKU)</h2><ul><li><strong>Kediri:</strong> 3 D-III and 3 D-IV study programs.</li><li><strong>Lumajang:</strong> 3 D-III and 1 D-IV study program.</li><li><strong>Pamekasan:</strong> 1 D-III and 2 D-IV study programs.</li></ul><p>POLINEMA has seven departments:</p><ul><li>Electrical Engineering</li><li>Mechanical Engineering</li><li>Civil Engineering</li><li>Accounting</li><li>Business Administration</li><li>Chemical Engineering</li><li>Information Technology</li></ul><p><small>Source: <a href="https://www.polinema.ac.id/jurusan/">official POLINEMA website</a></small></p>',
                'is_published' => true,
            ],
        );

        foreach ([
            ['Pekerti', 'Pekerti', 'pekerti'],
            ['Applied Approach', 'Applied Approach', 'applied-approach'],
            ['Perangkat dan Media Pembelajaran bagi Dosen', 'Instructional Tools and Media for Lecturers', 'perangkat-media-pembelajaran'],
            ['Penyusunan Kurikulum', 'Curriculum Development', 'penyusunan-kurikulum'],
        ] as [$name, $nameEn, $slug]) {
            Course::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'name_en' => $nameEn,
                    'description' => 'Program ini tercantum pada menu pelatihan P2MPP.',
                    'description_en' => 'This program is listed in P2MPP’s training menu.',
                    'facilities' => null,
                    'facilities_en' => null,
                ],
            );
        }

        $category = DocumentCategory::updateOrCreate(
            ['slug' => 'dokumen-p2mpp'],
            [
                'name' => 'Dokumen P2MPP',
                'name_en' => 'P2MPP Documents',
                'description' => 'Dokumen resmi yang dipublikasikan oleh P2MPP.',
                'description_en' => 'Official documents published by P2MPP.',
            ],
        );

        $documents = [
            ['SPMI 2013', 'SPMI_2013.pdf', '/assets/uploads/files/SPMI_2013.pdf'],
            ['SPMI 2015', 'SPMI_2015.pdf', '/assets/uploads/files/SPMI_2015.pdf'],
            ['SPMI 2017', 'SPMI_2017.pdf', '/assets/uploads/files/SPMI_2017.pdf'],
            ['SPMI 2020', 'SPMI_2020.pdf', '/assets/uploads/files/SPMI_2020.pdf'],
            ['SPMI 2021', 'SPMI_2021.pdf', '/assets/uploads/files/SPMI_2021.pdf'],
            ['Sertifikat Akreditasi Institusi 2016', 'sertifikat-akreditasi-institusi-2016.pdf', '/assets/uploads/akreditasi/12e6a-0-sertif-akred_polinema.pdf'],
            ['Sertifikat Akreditasi Institusi 2018', 'sertifikat-akreditasi-institusi-2018.pdf', '/assets/uploads/akreditasi/04e76-sertifikat-apt-polinema-2018-2023-1.pdf'],
            ['Sertifikat Akreditasi Institusi 2023', 'sertifikat-akreditasi-institusi-2023.pdf', '/assets/uploads/akreditasi/6b43e-ssertifikat-polinema-b.pdf'],
            ['Sertifikat Akreditasi Institusi 2025', 'sertifikat-akreditasi-institusi-2025.pdf', '/assets/uploads/akreditasi/88a31-ssertifikat-80286-4a9225cd9e2542aaa910dfe344cd1577_sign.pdf'],
        ];

        foreach ($documents as [$title, $fileName, $path]) {
            $url = self::SOURCE.$path;
            Document::updateOrCreate(
                ['file_path' => $url],
                [
                    'document_category_id' => $category->id,
                    'title' => $title,
                    'title_en' => $title,
                    'description' => 'Dokumen resmi P2MPP dari situs sumber.',
                    'description_en' => 'Official P2MPP document from the source website.',
                    'file_name' => $fileName,
                    'file_type' => 'application/pdf',
                    'is_public' => true,
                ],
            );
        }

        $this->command?->info('Ringkasan konten P2MPP berhasil disinkronkan.');
    }
}
