import React, { useEffect, useState } from "react";
import { motion, AnimatePresence } from "motion/react";
import {
  Plus,
  Search,
  MoreVertical,
  Edit,
  Trash2,
  Eye,
  UserPlus,
  Shield,
  GraduationCap,
  X,
  Check,
} from "lucide-react";
import api from "~/lib/api";
import type { User } from "~/types/user";
import type { Role } from "~/types/role";

export default function UsersPage() {
  const [users, setUsers] = useState<User[]>([]);
  const [searchTerm, setSearchTerm] = useState("");
  const [filterRole, setFilterRole] = useState<Role | "all">("all");

  // Modal states
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState<"create" | "edit" | "view">(
    "create",
  );
  const [selectedUser, setSelectedUser] = useState<User | null>(null);

  // Form states
  const [formData, setFormData] = useState<Partial<User>>({});

  const filteredUsers = users.filter((user) => {
    // 1. Ambil nilai NIM atau NIDN secara dinamis
    const nimNidn = user.mahasiswa?.nim || user.dosen?.nidn || "";

    const matchesSearch =
      user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      // 2. Gunakan variabel nimNidn yang sudah kita buat di atas
      nimNidn.toLowerCase().includes(searchTerm.toLowerCase()) ||
      user.email.toLowerCase().includes(searchTerm.toLowerCase());

    const matchesRole = filterRole === "all" || user.role === filterRole;

    return matchesSearch && matchesRole;
  });

  const handleOpenModal = (mode: "create" | "edit" | "view", user?: User) => {
    setModalMode(mode);
    console.log("Opening modal in mode:", mode, "with user:", user);
    if (user) {
      setSelectedUser(user);
      setFormData({ 
        ...user, 
        nim_nidn: user.mahasiswa?.nim || user.dosen?.nidn || "",
        prodi: user.mahasiswa?.prodi || "",
        angkatan: user.mahasiswa?.angkatan || "",
      });
    } else {
      setSelectedUser(null);
      setFormData({ role: "mahasiswa", is_active: true });
    }
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
    setSelectedUser(null);
    setFormData({});
  };

  const handleSave = async () => {
    // Ambil role dan nilai input nim_nidn dari form
    const role = (formData.role as Role) || "mahasiswa";
    const inputNimNidn = formData.nim_nidn || "";

    if (modalMode === "create") {
      const newUser = {
        name: formData.name || "",
        email: formData.email || "",
        role: role,
        nim: role === "mahasiswa" ? inputNimNidn : null,
        nidn: role === "dosen" ? inputNimNidn : null,
        prodi: role === "mahasiswa" ? formData.prodi || null : null,
        angkatan: role === "mahasiswa" ? formData.angkatan || null : null,
      };

      try {
        const res = await api.post("/register", newUser);
        const createdUser = res.data.data;
        setUsers([...users, createdUser]);
      } catch (error) {
        console.error("Gagal Membuat Pengguna Baru:", error);
      }
    } else if (modalMode === "edit" && selectedUser) {
      const updatedUser = {
        ...selectedUser,
        name: formData.name || selectedUser.name,
        email: formData.email || selectedUser.email,
        role: formData.role || selectedUser.role,
        nim: role === "mahasiswa" ? inputNimNidn : null,
        nidn: role === "dosen" ? inputNimNidn : null,
        prodi: role === "mahasiswa" ? formData.prodi || selectedUser.mahasiswa?.prodi || null : null,
        angkatan: role === "mahasiswa" ? formData.angkatan || selectedUser.mahasiswa?.angkatan || null : null,
        is_active: formData.is_active !== undefined ? formData.is_active : selectedUser.is_active,
        password: formData.password || undefined, // Hanya kirim password jika diisi
      };

      try {
        const res = await api.put(`/users/${selectedUser.id}`, updatedUser);
        const updatedUserFromServer = res.data.data;
        setUsers(
          users.map((u) => (u.id === selectedUser.id ? updatedUserFromServer : u)),
        );
      } catch (error) {
        console.error("Gagal Memperbarui Pengguna:", error);
      }
    }
    handleCloseModal();
  };

  const handleDelete = async (id: number) => {
    if (window.confirm("Are you sure you want to delete this user?")) {
      try {
        await api.delete(`/users/${id}`);
        setUsers(users.filter((user) => user.id !== id));
      } catch (error) {
        console.error("Gagal menghapus pengguna:", error);
      }
    }
  };

  const getRoleBadge = (role: Role) => {
    switch (role) {
      case "admin":
        return (
          <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 border border-purple-200">
            <Shield className="w-3.5 h-3.5" /> Admin
          </span>
        );
      case "dosen":
        return (
          <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">
            <GraduationCap className="w-3.5 h-3.5" /> Dosen
          </span>
        );
      case "mahasiswa":
        return (
          <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
            <UserPlus className="w-3.5 h-3.5" /> Mahasiswa
          </span>
        );
    }
  };

  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const res = await api.get("/users");
        setUsers(res.data.data);
      } catch (error) {
        console.error("Failed to fetch users:", error);
        setUsers([]);
      }
    };

    fetchUsers();
  }, []);

  return (
    <div className="flex flex-col gap-6 h-full">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-800 tracking-tight">
            Manajemen Pengguna
          </h1>
          <p className="text-sm text-slate-500 mt-1">
            Kelola data seluruh pengguna sistem: Admin, Dosen, dan Mahasiswa.
          </p>
        </div>

        <button
          onClick={() => handleOpenModal("create")}
          className="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-medium text-sm hover:bg-blue-700 focus:ring-4 focus:ring-blue-600/20 transition-all shadow-sm"
        >
          <Plus className="w-4 h-4" />
          <span>Tambah Pengguna</span>
        </button>
      </div>

      {/* Filters & Search */}
      <div className="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-4">
        <div className="relative flex-1">
          <Search className="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Cari berdasarkan nama, NIM/NIP, atau email..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
          />
        </div>
        <div className="flex gap-2 shrink-0">
          {(["all", "mahasiswa", "dosen", "admin"] as const).map((role) => (
            <button
              key={role}
              onClick={() => setFilterRole(role)}
              className={`px-4 py-2.5 rounded-xl text-sm font-medium capitalize transition-all ${
                filterRole === role
                  ? "bg-slate-800 text-white shadow-sm"
                  : "bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100"
              }`}
            >
              {role === "all" ? "Semua Role" : role}
            </button>
          ))}
        </div>
      </div>

      {/* Users Table */}
      <div className="bg-white border border-slate-200 rounded-2xl shadow-sm flex-1 overflow-hidden flex flex-col">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="border-b border-slate-200 bg-slate-50/50">
                <th className="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider w-12">
                  No
                </th>
                <th className="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                  Informasi Pengguna
                </th>
                <th className="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                  Role
                </th>
                <th className="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                  NIM / NIDN
                </th>
                <th className="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                  Status
                </th>
                <th className="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-200">
              {filteredUsers.length === 0 ? (
                <tr>
                  <td
                    colSpan={6}
                    className="px-6 py-12 text-center text-slate-500"
                  >
                    Tidak ada data pengguna yang ditemukan.
                  </td>
                </tr>
              ) : (
                filteredUsers.map((user, index) => (
                  <tr
                    key={user.id}
                    className="hover:bg-slate-50/50 transition-colors group"
                  >
                    <td className="px-6 py-4 text-sm text-slate-500">
                      {index + 1}
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex flex-col">
                        <span className="font-medium text-slate-900">
                          {user.name}
                        </span>
                        <span className="text-sm text-slate-500">
                          {user.email}
                        </span>
                      </div>
                    </td>
                    <td className="px-6 py-4">{getRoleBadge(user.role)}</td>
                    <td className="px-6 py-4 text-sm font-medium text-slate-700">
                      {user.mahasiswa?.nim || user.dosen?.nidn || "-"}
                    </td>
                    <td className="px-6 py-4">
                      <span
                        className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border ${
                          user.is_active == true
                            ? "bg-green-50 text-green-700 border-green-200"
                            : "bg-slate-100 text-slate-600 border-slate-200"
                        }`}
                      >
                        <span
                          className={`w-1.5 h-1.5 rounded-full ${user.is_active == true ? "bg-green-500" : "bg-slate-400"}`}
                        ></span>
                        {user.is_active == true ? "Aktif" : "Nonaktif"}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-start gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button
                          onClick={() => handleOpenModal("view", user)}
                          className="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                          title="Detail"
                        >
                          <Eye className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => handleOpenModal("edit", user)}
                          className="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                          title="Edit"
                        >
                          <Edit className="w-4 h-4" />
                        </button>
                        <button
                          onClick={() => handleDelete(user.id)}
                          className="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                          title="Hapus"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal CRUD */}
      <AnimatePresence>
        {isModalOpen && (
          <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={handleCloseModal}
              className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
            />
            <motion.div
              initial={{ opacity: 0, scale: 0.95, y: 10 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.95, y: 10 }}
              className="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100"
            >
              <div className="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 className="text-lg font-semibold text-slate-800">
                  {modalMode === "create"
                    ? "Tambah Pengguna Baru"
                    : modalMode === "edit"
                      ? "Edit Pengguna"
                      : "Detail Pengguna"}
                </h3>
                <button
                  onClick={handleCloseModal}
                  className="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>

              <div className="p-6 space-y-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div className="space-y-1.5 sm:col-span-2">
                    <label className="text-sm font-medium text-slate-700">
                      Nama Lengkap
                    </label>
                    <input
                      type="text"
                      value={formData.name || ""}
                      onChange={(e) =>
                        setFormData({ ...formData, name: e.target.value })
                      }
                      disabled={modalMode === "view"}
                      className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:opacity-70 disabled:bg-slate-100"
                      placeholder="Masukkan nama lengkap"
                    />
                  </div>

                  <div className="space-y-1.5">
                    <label className="text-sm font-medium text-slate-700">
                      Role
                    </label>
                    <select
                      value={formData.role || "mahasiswa"}
                      onChange={(e) =>
                        setFormData({
                          ...formData,
                          role: e.target.value as Role,
                        })
                      }
                      disabled={modalMode === "view"}
                      className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:opacity-70 disabled:bg-slate-100 capitalize"
                    >
                      <option value="mahasiswa">Mahasiswa</option>
                      <option value="dosen">Dosen</option>
                      <option value="admin">Admin</option>
                    </select>
                  </div>

                  <div className="space-y-1.5">
                    <label className="text-sm font-medium text-slate-700">
                      NIM / NIDN
                    </label>
                    <input
                      type="text"
                      value={formData.nim_nidn || ""}
                      onChange={(e) =>
                        setFormData({ ...formData, nim_nidn: e.target.value })
                      }
                      disabled={modalMode === "view" || formData.role === "admin"}
                      className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:opacity-70 disabled:bg-slate-100"
                      placeholder={
                        formData.role === "mahasiswa"
                          ? "NIM..."
                          : formData.role === "dosen"
                            ? "NIDN..."
                            : "-"
                      }
                    />
                  </div>

                  <div className="space-y-1.5 sm:col-span-2">
                    <label className="text-sm font-medium text-slate-700">
                      Email
                    </label>
                    <input
                      type="email"
                      value={formData.email || ""}
                      onChange={(e) =>
                        setFormData({ ...formData, email: e.target.value })
                      }
                      disabled={modalMode === "view"}
                      className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:opacity-70 disabled:bg-slate-100"
                      placeholder="email@univ.ac.id"
                    />
                  </div>

                  {
                    formData.role === "mahasiswa" && (
                      <>
                      <div className="space-y-1.5">
                        <label className="text-sm font-medium text-slate-700">
                          Prodi
                        </label>
                        <input
                          type="text"
                          value={formData.prodi || ""}
                          onChange={(e) =>
                            setFormData({ ...formData, prodi: e.target.value })
                          }
                          disabled={modalMode === "view"}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:opacity-70 disabled:bg-slate-100"
                          placeholder="Prodi..."
                        />
                      </div>

                      <div className="space-y-1.5">
                        <label className="text-sm font-medium text-slate-700">
                          Angkatan
                        </label>
                        <input
                          type="text"
                          value={formData.angkatan || ""}
                          onChange={(e) =>
                            setFormData({ ...formData, angkatan: e.target.value })
                          }
                          disabled={modalMode === "view"}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:opacity-70 disabled:bg-slate-100"
                          placeholder="Angkatan..."
                        />
                      </div>
                      </>
                    )
                  }

                  {modalMode !== "create" && (
                    <>
                      <div className="space-y-1.5">
                        <label className="text-sm font-medium text-slate-700">
                          Status
                        </label>
                        <select
                          value={formData.is_active ? "true" : "false"}
                          onChange={(e) =>
                            setFormData({
                              ...formData,
                              // 2. Ubah kembali string dari dropdown menjadi boolean untuk disimpan ke state
                              // Jika e.target.value adalah "true", maka is_active akan bernilai boolean true
                              is_active: e.target.value === "true",
                            })
                          }
                          disabled={modalMode === "view"}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:opacity-70 disabled:bg-slate-100"
                        >
                          <option value="true">Aktif</option>
                          <option value="false">Nonaktif</option>
                        </select>
                      </div>
                      <div className="space-y-1.5">
                        <label className="text-sm font-medium text-slate-700">
                          Password
                        </label>
                        <input
                          type="password"
                          value={formData.password || ""}
                          onChange={(e) =>
                            setFormData({ ...formData, password: e.target.value })
                          }
                          disabled={modalMode === "view"}
                          className="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:opacity-70 disabled:bg-slate-100"
                          placeholder={
                            modalMode === "edit"
                              ? "Password baru"
                              : "Masukkan password..."
                          }
                        />
                      </div>
                    </>
                  )}
                </div>
              </div>

              <div className="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3">
                <button
                  onClick={handleCloseModal}
                  className="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors"
                >
                  {modalMode === "view" ? "Tutup" : "Batal"}
                </button>
                {modalMode !== "view" && (
                  <button
                    onClick={handleSave}
                    className="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl font-medium text-sm hover:bg-blue-700 focus:ring-4 focus:ring-blue-600/20 transition-all shadow-sm"
                  >
                    <Check className="w-4 h-4" />
                    <span>Simpan Data</span>
                  </button>
                )}
              </div>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </div>
  );
}
