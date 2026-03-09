"use client";

import { useState, useMemo } from "react";
import useSWR from "swr";
import axios from "@/lib/axios";
import toast from "react-hot-toast";
import { 
  createColumnHelper, 
  flexRender, 
  getCoreRowModel, 
  useReactTable, 
  getPaginationRowModel,
  getSortedRowModel,
  SortingState
} from "@tanstack/react-table";
import { Plus, Pencil, Trash2, Search, ArrowUpDown, Loader2, X } from "lucide-react";

const fetcher = (url: string) => axios.get(url).then((res) => res.data.data);

export default function UsersPage() {
  const { data: users, mutate: mutateUsers, isLoading: isLoadingUsers } = useSWR("/users", fetcher);
  const { data: roles } = useSWR("/roles", fetcher);

  const [editingUser, setEditingUser] = useState<any>(null);
  const [deletingUserId, setDeletingUserId] = useState<number | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [sorting, setSorting] = useState<SortingState>([]);
  const [globalFilter, setGlobalFilter] = useState("");

  const confirmDelete = async () => {
    if (!deletingUserId) return;
    const toastId = toast.loading("Deleting user...");
    setIsSubmitting(true);
    try {
      await axios.delete(`/users/${deletingUserId}`);
      mutateUsers();
      toast.success("User deleted successfully.", { id: toastId });
    } catch (err) {
      console.error(err);
      toast.error("Failed to delete user.", { id: toastId });
    } finally {
      setIsSubmitting(false);
      setDeletingUserId(null);
    }
  };

  const handleDelete = (id: number) => {
    setDeletingUserId(id);
  };

  const handleSaveUser = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    const toastId = toast.loading(editingUser ? "Updating user..." : "Creating user...");
    
    const formData = new FormData(e.target as HTMLFormElement);
    const payload: any = {
      name: formData.get("name"),
      email: formData.get("email"),
      roles: [formData.get("role_name")]
    };

    const password = formData.get("password");
    if (password) {
      payload.password = password;
    }

    const url = editingUser?.id ? `/users/${editingUser.id}` : "/users";
    const method = editingUser?.id ? "put" : "post";

    try {
      await axios[method](url, payload);
      setIsModalOpen(false);
      setEditingUser(null);
      mutateUsers();
      toast.success(`User ${editingUser ? 'updated' : 'created'} successfully!`, { id: toastId });
    } catch (err: any) {
      console.error(err);
      toast.error(err.response?.data?.message || "Failed to save user.", { id: toastId });
    } finally {
      setIsSubmitting(false);
    }
  };

  const openModal = (user: any = null) => {
    setEditingUser(user);
    setIsModalOpen(true);
  };

  const columnHelper = createColumnHelper<any>();

  const columns = useMemo(() => [
    columnHelper.accessor('name', {
      header: ({ column }) => (
        <button className="flex items-center gap-1 hover:text-slate-700" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
          User Details
          <ArrowUpDown className="w-3 h-3" />
        </button>
      ),
      cell: info => (
        <div className="flex items-center gap-3">
          <div className="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold text-sm border border-indigo-200">
            {info.getValue().charAt(0).toUpperCase()}
          </div>
          <div>
            <div className="text-sm font-semibold text-slate-900">{info.getValue()}</div>
            <div className="text-xs text-slate-500">{info.row.original.email}</div>
          </div>
        </div>
      )
    }),
    columnHelper.accessor('roles', {
      header: 'Role',
      cell: info => {
        const roles = info.getValue() || [];
        return (
          <div className="flex gap-1 flex-wrap">
            {roles.length > 0 ? roles.map((r: any) => (
              <span key={r.id} className="px-2.5 py-1 inline-flex text-[11px] leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm">
                {r.name.toUpperCase()}
              </span>
            )) : <span className="text-sm text-slate-400 italic">No roles</span>}
          </div>
        )
      }
    }),
    columnHelper.accessor('created_at', {
      header: 'Date Joined',
      cell: info => (
        <span className="text-sm text-slate-600 font-medium">
          {new Date(info.getValue()).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}
        </span>
      )
    }),
    columnHelper.display({
      id: 'actions',
      header: () => <div className="text-right">Actions</div>,
      cell: info => (
        <div className="flex justify-end gap-2">
          <button 
            onClick={() => openModal(info.row.original)}
            className="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors"
            title="Edit User"
          >
            <Pencil className="w-4 h-4" />
          </button>
          <button 
            onClick={() => handleDelete(info.row.original.id)}
            className="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors"
            title="Delete User"
          >
            <Trash2 className="w-4 h-4" />
          </button>
        </div>
      )
    })
  ], []);

  const filteredUsers = useMemo(() => {
    if (!users) return [];
    if (!globalFilter) return users;
    return users.filter((u: any) => 
      u.name.toLowerCase().includes(globalFilter.toLowerCase()) || 
      u.email.toLowerCase().includes(globalFilter.toLowerCase())
    );
  }, [users, globalFilter]);

  const table = useReactTable({
    data: filteredUsers,
    columns,
    state: { sorting },
    onSortingChange: setSorting,
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    initialState: {
      pagination: { pageSize: 5 }
    }
  });

  return (
    <div className="space-y-6 animate-in fade-in duration-500">
      <div className="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
        <div>
           <h1 className="text-2xl font-bold text-slate-900 tracking-tight">Team Members</h1>
           <p className="text-slate-500 text-sm mt-1">Manage user access and roles for the platform.</p>
        </div>
        <div className="flex items-center gap-3">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <input 
              type="text" 
              placeholder="Search users..." 
              value={globalFilter}
              onChange={e => setGlobalFilter(e.target.value)}
              className="pl-9 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64"
            />
          </div>
          <button
            onClick={() => openModal()}
            className="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium shadow-sm shadow-indigo-200 hover:bg-indigo-700 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all flex items-center gap-2"
          >
            <Plus className="w-4 h-4" />
            <span className="hidden sm:inline">Add User</span>
          </button>
        </div>
      </div>

      <div className="bg-white shadow-sm border border-slate-200 rounded-xl overflow-hidden">
        {isLoadingUsers ? (
          <div className="flex flex-col items-center justify-center h-64 text-slate-500">
            <Loader2 className="w-8 h-8 animate-spin mb-2 text-indigo-600" />
            <p className="text-sm">Loading users data...</p>
          </div>
        ) : (
          <>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-slate-200">
                <thead className="bg-slate-50">
                  {table.getHeaderGroups().map(headerGroup => (
                    <tr key={headerGroup.id}>
                      {headerGroup.headers.map(header => (
                        <th key={header.id} className="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider whitespace-nowrap">
                          {flexRender(header.column.columnDef.header, header.getContext())}
                        </th>
                      ))}
                    </tr>
                  ))}
                </thead>
                <tbody className="bg-white divide-y divide-slate-100">
                  {table.getRowModel().rows.length === 0 ? (
                    <tr>
                      <td colSpan={columns.length} className="px-6 py-12 text-center text-slate-500 text-sm">
                        No users found matching your criteria.
                      </td>
                    </tr>
                  ) : (
                    table.getRowModel().rows.map(row => (
                      <tr key={row.id} className="hover:bg-slate-50 transition-colors group">
                        {row.getVisibleCells().map(cell => (
                          <td key={cell.id} className="px-6 py-4 whitespace-nowrap">
                            {flexRender(cell.column.columnDef.cell, cell.getContext())}
                          </td>
                        ))}
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>

            {/* Pagination */}
            <div className="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
              <div className="text-sm text-slate-500">
                Showing page <span className="font-medium text-slate-900">{table.getState().pagination.pageIndex + 1}</span> of{' '}
                <span className="font-medium text-slate-900">{table.getPageCount() || 1}</span>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => table.previousPage()}
                  disabled={!table.getCanPreviousPage()}
                  className="px-3 py-1.5 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Previous
                </button>
                <button
                  onClick={() => table.nextPage()}
                  disabled={!table.getCanNextPage()}
                  className="px-3 py-1.5 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Next
                </button>
              </div>
            </div>
          </>
        )}
      </div>

      {/* Slide-over Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 overflow-hidden flex items-center justify-center">
          <div className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onClick={() => !isSubmitting && setIsModalOpen(false)} />
          
          <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all scale-100 opacity-100 border border-slate-200">
            <button 
              onClick={() => setIsModalOpen(false)} 
              className="absolute top-4 right-4 text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 p-1.5 rounded-full transition-colors"
            >
              <X className="w-5 h-5" />
            </button>

            <div className="mb-6">
              <h2 className="text-xl font-bold text-slate-900 flex items-center gap-2">
                {editingUser ? <Pencil className="w-5 h-5 text-indigo-600" /> : <Plus className="w-5 h-5 text-indigo-600" />}
                {editingUser ? "Edit User Profile" : "Add New User"}
              </h2>
              <p className="text-sm text-slate-500 mt-1">Fill in the information below to {editingUser ? 'update the' : 'create a new'} user.</p>
            </div>

            <form onSubmit={handleSaveUser} className="space-y-5">
              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1.5">Full Name</label>
                <input type="text" name="name" defaultValue={editingUser?.name || ""} required className="block w-full border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" placeholder="John Doe" />
              </div>
              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                <input type="email" name="email" defaultValue={editingUser?.email || ""} required className="block w-full border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" placeholder="john@company.com" />
              </div>
              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1.5">
                  Password {editingUser && <span className="text-slate-400 font-normal ml-1">(Leave blank to keep current)</span>}
                </label>
                <input type="password" name="password" required={!editingUser} minLength={8} className="block w-full border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" placeholder="••••••••" />
              </div>
              <div>
                <label className="block text-sm font-semibold text-slate-700 mb-1.5">Assign Role</label>
                <select name="role_name" defaultValue={editingUser?.roles?.[0]?.name || ""} required className="block w-full border border-slate-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                  <option value="" disabled>Select a system role</option>
                  {roles?.map((r: any) => <option key={r.id} value={r.name}>{r.name.charAt(0).toUpperCase() + r.name.slice(1)}</option>)}
                </select>
              </div>
              
              <div className="pt-4 flex gap-3">
                <button type="button" onClick={() => setIsModalOpen(false)} className="flex-1 px-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition-colors">
                  Cancel
                </button>
                <button type="submit" disabled={isSubmitting} className="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-semibold shadow-sm hover:bg-indigo-700 transition-colors disabled:bg-indigo-400 flex items-center justify-center gap-2">
                  {isSubmitting ? (
                    <><Loader2 className="w-4 h-4 animate-spin" /> Saving...</>
                  ) : (
                    "Save User"
                  )}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
      {/* Delete Confirmation Modal */}
      {deletingUserId && (
        <div className="fixed inset-0 z-50 overflow-hidden flex items-center justify-center">
          <div className="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onClick={() => !isSubmitting && setDeletingUserId(null)} />
          
          <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 transform transition-all scale-100 opacity-100 border border-slate-200 text-center">
            <div className="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
              <Trash2 className="h-6 w-6 text-red-600" />
            </div>
            <h3 className="text-lg font-bold text-slate-900 mb-2">Delete User</h3>
            <p className="text-sm text-slate-500 mb-6">
              Are you sure you want to delete this user? This action cannot be undone and will permanently remove their access.
            </p>
            <div className="flex gap-3 w-full">
              <button 
                type="button" 
                onClick={() => setDeletingUserId(null)} 
                disabled={isSubmitting}
                className="flex-1 px-4 py-2.5 border border-slate-300 rounded-lg text-slate-700 font-semibold hover:bg-slate-50 transition-colors disabled:opacity-50"
              >
                Cancel
              </button>
              <button 
                onClick={confirmDelete}
                disabled={isSubmitting} 
                className="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg font-semibold shadow-sm hover:bg-red-700 transition-colors disabled:bg-red-400 flex items-center justify-center gap-2"
              >
                {isSubmitting ? (
                  <><Loader2 className="w-4 h-4 animate-spin" /> Deleting...</>
                ) : (
                  "Delete User"
                )}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
