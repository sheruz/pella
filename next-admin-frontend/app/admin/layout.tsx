"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import axios from "@/lib/axios";
import { Toaster } from "react-hot-toast";
import { Users, LogOut, Shield, LayoutDashboard, Menu, X } from "lucide-react";

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  const router = useRouter();
  const [mounted, setMounted] = useState(false);
  const [user, setUser] = useState<any>(null);
  const [isSidebarOpen, setSidebarOpen] = useState(true);

  useEffect(() => {
    setMounted(true);
    const token = localStorage.getItem("token");
    const storedUser = localStorage.getItem("user");
    
    if (!token || !storedUser) {
      router.push("/login");
    } else {
      setUser(JSON.parse(storedUser));
      axios.get('/me').then(res => {
         setUser(res.data.data);
         localStorage.setItem("user", JSON.stringify(res.data.data));
      }).catch(() => {
         // Axios interceptor will handle 401
      });
    }
  }, [router]);

  const handleLogout = async () => {
    try {
      await axios.post("/logout");
    } finally {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      router.push("/login");
    }
  };

  if (!mounted || !user) return (
    <div className="flex items-center justify-center min-h-screen bg-gray-50">
      <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>
  );

  return (
    <div className="min-h-screen bg-gray-50 flex">
      {/* Mobile sidebar overlay */}
      {!isSidebarOpen && (
        <button 
          className="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-md shadow-md text-gray-600"
          onClick={() => setSidebarOpen(true)}
        >
          <Menu className="w-6 h-6" />
        </button>
      )}

      {/* Sidebar */}
      <aside className={`fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-white flex flex-col shadow-xl transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full'}`}>
        <div className="p-6 flex items-center justify-between border-b border-slate-800">
          <div className="flex items-center gap-3">
            <Shield className="w-8 h-8 text-indigo-400" />
            <span className="text-lg font-bold tracking-wider uppercase text-slate-100">Admin Pro</span>
          </div>
          <button className="lg:hidden text-slate-400 hover:text-white" onClick={() => setSidebarOpen(false)}>
            <X className="w-6 h-6" />
          </button>
        </div>
        
        <nav className="flex-1 p-4 space-y-2 overflow-y-auto">
          <div className="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4 mt-2 px-2">Menu</div>
          <a href="/admin/users" className="flex items-center gap-3 p-3 rounded-lg bg-indigo-600 hover:bg-indigo-500 transition-colors font-medium text-white shadow-md shadow-indigo-900/20">
            <Users className="w-5 h-5" />
            User Management
          </a>
        </nav>

        <div className="p-4 border-t border-slate-800 bg-slate-950">
          <div className="flex items-center gap-3 mb-4 p-2 rounded-lg bg-slate-900 border border-slate-800">
             <div className="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-lg">
               {user.name.charAt(0)}
             </div>
             <div className="overflow-hidden">
               <p className="font-semibold text-sm truncate">{user.name}</p>
               <div className="flex gap-1 mt-0.5 overflow-x-auto no-scrollbar">
                 {user.roles?.map((r: any) => (
                   <span key={r.id} className="text-[10px] bg-slate-700 text-slate-300 px-2 py-0.5 rounded-full border border-slate-600 whitespace-nowrap">
                     {r.name}
                   </span>
                 ))}
               </div>
             </div>
          </div>
          <button 
            onClick={handleLogout} 
            className="flex items-center justify-center gap-2 w-full text-center p-2.5 rounded-lg text-slate-300 hover:bg-red-500/10 hover:text-red-400 transition-colors font-medium border border-transparent hover:border-red-500/20"
          >
            <LogOut className="w-4 h-4" />
            Sign Out
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 flex flex-col h-screen overflow-hidden w-full">
        <Toaster position="top-right" toastOptions={{ 
          className: 'text-sm font-medium',
          duration: 4000,
          style: { borderRadius: '8px', padding: '16px' }
        }} />
        <div className="flex-1 overflow-y-auto p-4 lg:p-8 w-full">
          <div className="max-w-7xl mx-auto w-full">
            {children}
          </div>
        </div>
      </main>
    </div>
  );
}
