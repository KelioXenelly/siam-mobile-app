export type User = {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'mahasiswa' | 'dosen';
  is_active: boolean;
  nim_nidn: string;
  prodi: string;
  angkatan: string;
  password: string;

  mahasiswa?: {
    nim: string;
    prodi: string;
    angkatan: string;
  } | null;

  dosen?: {
    nidn: string;
  } | null;
}