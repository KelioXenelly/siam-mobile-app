import type { Mahasiswa } from "./mahasiswa";

export type Kelas = {
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

  mahasiswas: Mahasiswa[];

  mata_kuliah?: {
    id: number;
    kode_mk: string;
    nama_mk: string;
    sks: number;
  };

  dosen?: {
    id: number;
    nidn: string;
    user: {
      id: number;
      name: string;
      email: string;
      role: string;
      is_active: boolean;
    }
  };

  ruangan?: {
    id: number;
    nama: string;
    kapasitas: number;
  };
}