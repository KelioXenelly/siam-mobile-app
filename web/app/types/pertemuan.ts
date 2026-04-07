import type { Mahasiswa } from "./mahasiswa";

export type Pertemuan = {
  id: number;
  kelas_id: number;
  pertemuan_ke: number;
  tanggal: string;
  topik: string;
  started_at: string;
  ended_at: string;
  status: 'Terjadwal' | 'Berlangsung' | 'Selesai';

  kelas?: {
    id: number;
    kode_kelas: string;
    mata_kuliah_id: string;
    dosen_id: string;
    ruangan_id: string;
    semester: number;
    tahun_ajaran: string;
    hari: string;
    jam_mulai: string;
    jam_selesai: string;
    kapasitas: number;
    ruangan?: {
      id: number;
      nama: string;
      kapasitas: number;
    };
    mata_kuliah?: {
      id: number;
      kode_mk: string;
      nama_mk: string;
      sks: number;
    };
  }

  mahasiswas: Mahasiswa[];
}