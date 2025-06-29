import { useAuthStore } from '../store/authStore';

export const useAuth = () => {
  const {
    user,
    permissions,
    roles,
    menus,
    isAuthenticated,
    isLoading,
    login,
    register,
    logout,
    loadUser,
    updateProfile,
    hasPermission,
    hasRole,
    hasAnyRole,
    hasAnyPermission,
    clearAuth,
  } = useAuthStore();

  return {
    // State
    user,
    permissions,
    roles,
    menus,
    isAuthenticated,
    isLoading,
    
    // Actions
    login,
    register,
    logout,
    loadUser,
    updateProfile,
    
    // Permission checks
    hasPermission,
    hasRole,
    hasAnyRole,
    hasAnyPermission,
    
    // Utils
    clearAuth,
  };
};

export default useAuth;
