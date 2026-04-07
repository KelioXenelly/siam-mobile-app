export type Dosen = {
  id: number;
  nidn: string;
  user?: {
    id: number;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
  }
}