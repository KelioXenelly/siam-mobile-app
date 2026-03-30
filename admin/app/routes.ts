import { type RouteConfig, index, route } from "@react-router/dev/routes";

export default [
  route("login", "routes/login.tsx"),
  
  route("/", "layouts/admin_layout.tsx", [
    index("routes/dashboard.tsx"),
    route("mahasiswa", "routes/mahasiswa.tsx"),
    route("dosen", "routes/dosen.tsx"),
    route("kelas", "routes/kelas.tsx"),
    route("mata_kuliah", "routes/mata_kuliah.tsx"),
  ])
] satisfies RouteConfig;
