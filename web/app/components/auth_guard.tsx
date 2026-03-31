import { useEffect } from "react";
import { useNavigate } from "react-router";
import { isAuthenticated } from "~/lib/auth";

export default function AuthGuard({ children }: { children: React.ReactNode }) {
  const navigate = useNavigate();

  useEffect(() => {
    if(!isAuthenticated()) {
      navigate("/login");
    }
  }, []);

  return <>{children}</>
};
