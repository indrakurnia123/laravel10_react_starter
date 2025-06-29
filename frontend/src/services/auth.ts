import apiService from './api';
import { 
  User, 
  LoginCredentials, 
  RegisterData, 
  AuthResponse, 
  ApiResponse 
} from '../types';

class AuthService {
  async login(credentials: LoginCredentials): Promise<AuthResponse> {
    const response = await apiService.post<AuthResponse>('/auth/login', credentials);
    
    if (response.status === 'success' && response.data.token) {
      apiService.setAuthToken(response.data.token);
    }
    
    return response;
  }

  async register(data: RegisterData): Promise<AuthResponse> {
    const response = await apiService.post<AuthResponse>('/auth/register', data);
    
    if (response.status === 'success' && response.data.token) {
      apiService.setAuthToken(response.data.token);
    }
    
    return response;
  }

  async me(): Promise<ApiResponse<{
    user: User;
    permissions: string[];
    roles: string[];
    menus: any[];
  }>> {
    return apiService.get('/auth/me');
  }

  async logout(): Promise<ApiResponse> {
    const response = await apiService.post<ApiResponse>('/auth/logout');
    apiService.clearAuthToken();
    return response;
  }

  async refreshToken(): Promise<AuthResponse> {
    return apiService.post<AuthResponse>('/auth/refresh');
  }

  async updateProfile(data: FormData | Record<string, any>): Promise<ApiResponse<{ user: User }>> {
    if (data instanceof FormData) {
      return apiService.upload('/auth/profile', data);
    }
    return apiService.put('/auth/profile', data);
  }

  async changePassword(data: {
    current_password: string;
    password: string;
    password_confirmation: string;
  }): Promise<ApiResponse> {
    return apiService.put('/auth/change-password', data);
  }

  async forgotPassword(email: string): Promise<ApiResponse> {
    return apiService.post('/auth/forgot-password', { email });
  }

  async resetPassword(data: {
    token: string;
    password: string;
    password_confirmation: string;
  }): Promise<ApiResponse> {
    return apiService.post('/auth/reset-password', data);
  }

  isAuthenticated(): boolean {
    return apiService.isAuthenticated();
  }
}

export const authService = new AuthService();
export default authService;
