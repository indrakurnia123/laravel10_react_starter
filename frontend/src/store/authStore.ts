import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import { User, Menu } from '../types';
import authService from '../services/auth';

interface AuthState {
  user: User | null;
  permissions: string[];
  roles: string[];
  menus: Menu[];
  isAuthenticated: boolean;
  isLoading: boolean;
  
  // Actions
  login: (credentials: { email: string; password: string; remember?: boolean }) => Promise<void>;
  register: (data: {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    terms: boolean;
  }) => Promise<void>;
  logout: () => Promise<void>;
  loadUser: () => Promise<void>;
  updateProfile: (data: FormData | Record<string, any>) => Promise<void>;
  hasPermission: (permission: string) => boolean;
  hasRole: (role: string) => boolean;
  hasAnyRole: (roles: string[]) => boolean;
  hasAnyPermission: (permissions: string[]) => boolean;
  clearAuth: () => void;
}

export const useAuthStore = create<AuthState>()(
  persist(
    (set, get) => ({
      user: null,
      permissions: [],
      roles: [],
      menus: [],
      isAuthenticated: false,
      isLoading: false,

      login: async (credentials) => {
        set({ isLoading: true });
        try {
          const response = await authService.login(credentials);
          
          if (response.status === 'success') {
            set({
              user: response.data.user,
              permissions: response.data.permissions || [],
              roles: response.data.roles || [],
              isAuthenticated: true,
            });
            
            // Load additional user data
            await get().loadUser();
          }
        } catch (error) {
          set({ isAuthenticated: false });
          throw error;
        } finally {
          set({ isLoading: false });
        }
      },

      register: async (data) => {
        set({ isLoading: true });
        try {
          const response = await authService.register(data);
          
          if (response.status === 'success') {
            set({
              user: response.data.user,
              permissions: response.data.permissions || [],
              roles: response.data.roles || [],
              isAuthenticated: true,
            });
            
            // Load additional user data
            await get().loadUser();
          }
        } catch (error) {
          set({ isAuthenticated: false });
          throw error;
        } finally {
          set({ isLoading: false });
        }
      },

      logout: async () => {
        try {
          await authService.logout();
        } catch (error) {
          console.error('Logout error:', error);
        } finally {
          get().clearAuth();
        }
      },

      loadUser: async () => {
        if (!authService.isAuthenticated()) {
          get().clearAuth();
          return;
        }

        set({ isLoading: true });
        try {
          const response = await authService.me();
          
          if (response.status === 'success') {
            set({
              user: response.data.user,
              permissions: response.data.permissions,
              roles: response.data.roles,
              menus: response.data.menus,
              isAuthenticated: true,
            });
          }
        } catch (error) {
          console.error('Load user error:', error);
          get().clearAuth();
        } finally {
          set({ isLoading: false });
        }
      },

      updateProfile: async (data) => {
        set({ isLoading: true });
        try {
          const response = await authService.updateProfile(data);
          
          if (response.status === 'success') {
            set({ user: response.data.user });
          }
        } finally {
          set({ isLoading: false });
        }
      },

      hasPermission: (permission: string) => {
        const { permissions } = get();
        return permissions.includes(permission);
      },

      hasRole: (role: string) => {
        const { roles } = get();
        return roles.includes(role);
      },

      hasAnyRole: (roleList: string[]) => {
        const { roles } = get();
        return roleList.some(role => roles.includes(role));
      },

      hasAnyPermission: (permissionList: string[]) => {
        const { permissions } = get();
        return permissionList.some(permission => permissions.includes(permission));
      },

      clearAuth: () => {
        set({
          user: null,
          permissions: [],
          roles: [],
          menus: [],
          isAuthenticated: false,
          isLoading: false,
        });
      },
    }),
    {
      name: 'auth-storage',
      partialize: (state) => ({
        user: state.user,
        permissions: state.permissions,
        roles: state.roles,
        menus: state.menus,
        isAuthenticated: state.isAuthenticated,
      }),
    }
  )
);

// Initialize auth state on app start
if (typeof window !== 'undefined') {
  // Check if user is authenticated on app start
  const { loadUser, clearAuth } = useAuthStore.getState();
  
  if (authService.isAuthenticated()) {
    loadUser().catch(() => {
      clearAuth();
    });
  } else {
    clearAuth();
  }
}
