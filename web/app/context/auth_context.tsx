import { createContext, useContext, useEffect, useState } from 'react';
import api from '~/lib/api';
import type { User } from '~/types/user';

const AuthContext = createContext<{
  user: User | null;
  isLoading: boolean;
  refreshUser: () => Promise<void>;
}>({
  user: null,
  isLoading: true,
  refreshUser: async () => {},
});

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  const fetchUser = async () => {
    setIsLoading(true);
    const token = localStorage.getItem('token');
    if (!token) {
      setUser(null);
      setIsLoading(false);
      return;
    }
    
    try {
      const res = await api.get('/me');
      setUser(res.data);
    } catch (error) {
      setUser(null);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    fetchUser();
  }, []);

  return (
    <AuthContext.Provider value={{ user, isLoading, refreshUser: fetchUser }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}