import { Outlet } from 'react-router';
import Sidebar from '~/components/sidebar';
import Navbar from '~/components/navbar';

export default function AdminLayout() {
  return (
    <div className="flex h-screen w-full bg-[#F8FAFC] overflow-hidden font-sans text-slate-800 selection:bg-blue-100">
      {/* LEFT SIDEBAR */}
      <Sidebar />

      {/* RIGHT CONTENT */}
      <div className="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        {/* TOP NAVBAR */}
        <Navbar />

        {/* MAIN CONTENT AREA */}
        <main className="flex-1 overflow-y-auto p-8 relative scroll-smooth">
          <div className="max-w-7xl mx-auto h-full">
            <Outlet />
          </div>
        </main>
      </div>
    </div>
  );
}
