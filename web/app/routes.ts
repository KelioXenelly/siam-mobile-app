import { type RouteConfig, index, route } from "@react-router/dev/routes";

export default [
  route("login", "routes/auth/login.tsx"),
  
  route("admin", "layouts/admin_layout.tsx", [
    route("dashboard", "routes/admin/dashboard.tsx"),
    route("mahasiswa", "routes/admin/mahasiswa.tsx"),
    route("dosen", "routes/admin/dosen.tsx"),
    route("kelas", "routes/admin/kelas.tsx"),
    route("mata_kuliah", "routes/admin/mata_kuliah.tsx"),
  ])
] satisfies RouteConfig;
