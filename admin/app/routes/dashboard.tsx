import AuthGuard from "~/components/auth_guard";
import AdminLayout from "~/layouts/admin_layout";

export default function DashboardPage() {
  return (
    <AuthGuard>
      <h1>Dashboard Admin</h1>
    </AuthGuard>
  );
};
