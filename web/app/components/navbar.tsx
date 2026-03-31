import { Search, Bell, ChevronDown } from "lucide-react";
import { useLocation } from "react-router";
import { useAuth } from "~/context/auth_context";

export default function Navbar() {
  const { user } = useAuth();
  const location = useLocation();

  const getPageTitle = () => {
    const path = location.pathname;
    if (path === "/") return "Dashboard";
    if (path === "/mahasiswa") return "Manajemen Mahasiswa";
    if (path === "/dosen") return "Manajemen Dosen";
    if (path === "/matakuliah") return "Mata Kuliah";
    if (path === "/kelas") return "Kelas";
    if (path === "/pertemuan") return "Pertemuan";
    return "Admin Panel";
  };

  return (
    <header className="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0 z-10">
      <h1 className="text-xl font-semibold text-slate-800">{getPageTitle()}</h1>

      <div className="flex items-center gap-6">
        <div className="relative hidden md:block">
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Search everything..."
            className="pl-9 pr-4 py-2 bg-slate-100 border-none rounded-full text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all"
          />
        </div>

        <button className="relative p-2 text-slate-400 hover:text-slate-600 transition-colors">
          <Bell className="w-5 h-5" />
          <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
        </button>

        <div className="h-8 w-px bg-slate-200"></div>

        <button className="flex items-center gap-3 hover:opacity-80 transition-opacity">
          <div className="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center border border-indigo-200">
            <span className="text-indigo-600 font-bold text-sm">A</span>
          </div>
          <div className="text-left hidden md:block">
            <p className="text-sm font-medium text-slate-700 leading-tight">
              {user ? user.name : "Loading..."}
            </p>
            <p className="text-xs text-slate-500">
              {user
                ? user.role.charAt(0).toUpperCase() + user.role.slice(1)
                : "Loading..."}
            </p>
          </div>
          <ChevronDown className="w-4 h-4 text-slate-400" />
        </button>
      </div>
    </header>
  );
}
