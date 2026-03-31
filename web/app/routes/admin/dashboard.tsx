import AuthGuard from "~/components/auth_guard";
import { 
  Users, 
  GraduationCap, 
  Building2, 
  Calendar,
  MoreVertical,
  ArrowUpRight,
  TrendingUp,
  Clock
} from 'lucide-react';
import { motion } from 'motion/react';

export default function DashboardPage() {

  const summaryCards = [
    {
      title: "Total Mahasiswa",
      value: "4,209",
      icon: Users,
      color: "bg-blue-500 text-white",
      trend: "+12% from last month",
      trendIcon: TrendingUp,
      trendColor: "text-emerald-500",
    },
    {
      title: "Total Dosen",
      value: "312",
      icon: GraduationCap,
      color: "bg-indigo-500 text-white",
      trend: "+4 new this semester",
      trendIcon: ArrowUpRight,
      trendColor: "text-blue-500",
    },
    {
      title: "Total Kelas Aktif",
      value: "184",
      icon: Building2,
      color: "bg-emerald-500 text-white",
      trend: "Steady",
      trendIcon: TrendingUp,
      trendColor: "text-slate-400",
    },
    {
      title: "Total Pertemuan",
      value: "1,024",
      icon: Calendar,
      color: "bg-violet-500 text-white",
      trend: "+8% this week",
      trendIcon: ArrowUpRight,
      trendColor: "text-emerald-500",
    },
  ];

  const recentActivity = [
    {
      id: 1,
      action: "Dosen Dr. Budi mengupdate absen",
      detail: "Kelas Pemrograman Web A - Pertemuan 4",
      time: "10 mins ago",
      status: "success",
    },
    {
      id: 2,
      action: "Mahasiswa baru ditambahkan",
      detail: "NIM 24001 - Andi Setiawan (Teknik Informatika)",
      time: "2 hours ago",
      status: "info",
    },
    {
      id: 3,
      action: "Pertemuan dibuat",
      detail: "Kelas Struktur Data B - Pertemuan 1",
      time: "3 hours ago",
      status: "success",
    },
    {
      id: 4,
      action: "Dosen gagal scan QR",
      detail: "Sistem mengalami timeout selama 2 detik",
      time: "1 day ago",
      status: "warning",
    },
    {
      id: 5,
      action: "Data Kelas diimpor",
      detail: "30 kelas baru ditambahkan via CSV",
      time: "2 days ago",
      status: "info",
    },
  ];

  return (
    <AuthGuard>
      <div className="flex flex-col gap-6 w-full">
        {/* SUMMARY CARDS */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          {summaryCards.map((card, idx) => {
            const Icon = card.icon;
            const TrendIcon = card.trendIcon;

            return (
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: idx * 0.1, duration: 0.4 }}
                key={idx}
                className="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col hover:shadow-md transition-shadow relative overflow-hidden group"
              >
                <div className="flex justify-between items-start mb-4 relative z-10">
                  <div className={`p-3 rounded-xl ${card.color} shadow-sm`}>
                    <Icon className="w-6 h-6" />
                  </div>
                  <button className="text-slate-400 hover:text-slate-600">
                    <MoreVertical className="w-5 h-5" />
                  </button>
                </div>

                <div className="relative z-10">
                  <h3 className="text-slate-500 text-sm font-medium">
                    {card.title}
                  </h3>
                  <p className="text-3xl font-bold text-slate-800 mt-1">
                    {card.value}
                  </p>
                </div>

                <div className="mt-4 flex items-center gap-1.5 text-xs font-medium relative z-10">
                  <TrendIcon className={`w-4 h-4 ${card.trendColor}`} />
                  <span className={card.trendColor}>{card.trend}</span>
                </div>

                {/* Decorative Background */}
                <div className="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-slate-50 opacity-0 group-hover:opacity-100 transition-opacity blur-2xl z-0" />
              </motion.div>
            );
          })}
        </div>

        {/* RECENT ACTIVITY & CHARTS PLACEHOLDER */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.4, duration: 0.4 }}
            className="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col min-h-100"
          >
            <div className="flex justify-between items-center mb-6">
              <h2 className="text-lg font-semibold text-slate-800">
                Statistik Absensi
              </h2>
              <select className="bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                <option>Bulan Ini</option>
                <option>Bulan Lalu</option>
                <option>Semester Ini</option>
              </select>
            </div>

            <div className="flex-1 border-2 border-dashed border-slate-100 rounded-xl flex items-center justify-center bg-slate-50/50 relative overflow-hidden">
              <div className="text-center">
                <TrendingUp className="w-12 h-12 text-slate-300 mx-auto mb-3" />
                <p className="text-slate-400 font-medium">
                  Chart Visualization Area
                </p>
                <p className="text-slate-400 text-sm mt-1">
                  Implement with Recharts
                </p>
              </div>

              {/* Mock chart bars just for aesthetics */}
              <div className="absolute bottom-0 left-0 right-0 flex items-end justify-between px-12 gap-4 h-32 opacity-20 pointer-events-none">
                {[40, 70, 45, 90, 65, 85, 50].map((h, i) => (
                  <div
                    key={i}
                    className="w-full bg-blue-500 rounded-t-sm"
                    style={{ height: `${h}%` }}
                  ></div>
                ))}
              </div>
            </div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.5, duration: 0.4 }}
            className="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col"
          >
            <div className="flex justify-between items-center mb-6">
              <h2 className="text-lg font-semibold text-slate-800">
                Aktivitas Terbaru
              </h2>
              <button className="text-blue-600 text-sm font-medium hover:text-blue-700">
                Lihat Semua
              </button>
            </div>

            <div className="flex flex-col gap-5 relative">
              <div className="absolute left-3.5 top-2 bottom-2 w-px bg-slate-100" />

              {recentActivity.map((activity, idx) => (
                <div
                  key={activity.id}
                  className="flex gap-4 relative z-10 group"
                >
                  <div className="relative mt-1">
                    <div
                      className={`w-7 h-7 rounded-full flex items-center justify-center border-2 border-white ring-4 ring-slate-50 group-hover:ring-blue-50 transition-all ${
                        activity.status === "success"
                          ? "bg-emerald-100 text-emerald-600"
                          : activity.status === "warning"
                            ? "bg-amber-100 text-amber-600"
                            : "bg-blue-100 text-blue-600"
                      }`}
                    >
                      {activity.status === "success" && (
                        <div className="w-2 h-2 rounded-full bg-emerald-500" />
                      )}
                      {activity.status === "warning" && (
                        <div className="w-2 h-2 rounded-full bg-amber-500" />
                      )}
                      {activity.status === "info" && (
                        <div className="w-2 h-2 rounded-full bg-blue-500" />
                      )}
                    </div>
                  </div>
                  <div>
                    <p className="text-sm font-semibold text-slate-800">
                      {activity.action}
                    </p>
                    <p className="text-xs text-slate-500 mt-0.5">
                      {activity.detail}
                    </p>
                    <div className="flex items-center gap-1.5 mt-1 text-[10px] font-medium text-slate-400">
                      <Clock className="w-3 h-3" />
                      {activity.time}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </motion.div>
        </div>
      </div>
    </AuthGuard>
  );
}
