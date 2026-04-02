import { Search, Bell, ChevronDown, LogOut } from "lucide-react";
import { motion, AnimatePresence } from "motion/react";
import { useState } from "react";
import { useLocation, useNavigate } from "react-router";
import { toast } from "sonner";
import { useAuth } from "~/context/auth_context";
import { logout } from "~/lib/auth";

export default function Navbar() {
  const { user } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [isProfileOpen, setIsProfileOpen] = useState(false);

  const getPageTitle = () => {
    const path = location.pathname;
    if (path === "/admin/dashboard") return "Dashboard";
    if (path === "/admin/users") return "Manajemen Pengguna";
    if (path === "/admin/mata-kuliah") return "Manajemen Mata Kuliah";
    if (path === "/admin/kelas") return "Manajemen Kelas";
    if (path === "/admin/pertemuan") return "Manajemen Pertemuan";
    return "Admin Panel";
  };

  const handleLogout = () => {
    logout();
    navigate("/login");
    toast.success("Logout berhasil, sampai jumpa lagi! 👋")
  };

  return (
    <header className="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0 z-10">
      <h1 className="text-xl font-semibold text-slate-800">{getPageTitle()}</h1>

      <div className="flex items-center gap-6 relative">
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

        <button
          className="flex items-center gap-3 hover:opacity-80 transition-opacity"
          onClick={() => setIsProfileOpen(!isProfileOpen)}
        >
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
          <ChevronDown
            className={`w-4 h-4 text-slate-400 transition-transform ${isProfileOpen ? "rotate-180" : ""}`}
          />
        </button>

        <AnimatePresence>
          {isProfileOpen && (
            <>
              <div
                className="fixed inset-0 z-40"
                onClick={() => setIsProfileOpen(false)}
              />
              <motion.div
                initial={{ opacity: 0, y: 10, scale: 0.95 }}
                animate={{ opacity: 1, y: 0, scale: 1 }}
                exit={{ opacity: 0, y: 10, scale: 0.95 }}
                transition={{ duration: 0.15 }}
                className="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 z-50 overflow-hidden"
              >
                <div className="p-2">
                  <button
                    onClick={handleLogout}
                    className="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors font-medium"
                  >
                    <LogOut className="w-4 h-4" />
                    Logout
                  </button>
                </div>
              </motion.div>
            </>
          )}
        </AnimatePresence>
      </div>
    </header>
  );
}
