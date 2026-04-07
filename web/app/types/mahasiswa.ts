export type Mahasiswa = {
  id: number;
  nim: string;
  name: string;
  prodi_id: number;
  prodi? : {
    id: number;
    kode_prodi: string;
    nama_prodi: string;
    jenjang: string;
    is_active: boolean;
  }
  user?: {
    id: number;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
  }
}