export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  is_active: boolean;
  email_verified_at?: string;
  last_login_at?: string;
  settings?: Record<string, any>;
  roles?: string[];
  permissions?: string[];
  unread_notifications_count?: number;
  created_at: string;
  updated_at: string;
}

export interface LoginCredentials {
  email: string;
  password: string;
  remember?: boolean;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
  terms: boolean;
}

export interface AuthResponse {
  status: string;
  message: string;
  data: {
    user: User;
    token: string;
    permissions?: string[];
    roles?: string[];
  };
}

export interface ApiResponse<T = any> {
  status: 'success' | 'error';
  message: string;
  data?: T;
  errors?: any;
}

export interface Menu {
  id: number;
  parent_id?: number;
  name: string;
  label: string;
  icon?: string;
  route?: string;
  url?: string;
  order_by: number;
  is_active: boolean;
  permissions?: string[];
  description?: string;
  children?: Menu[];
  roles?: Array<{
    id: number;
    name: string;
    display_name: string;
  }>;
  created_at: string;
  updated_at: string;
}

export interface Notification {
  id: number;
  user_id: number;
  title: string;
  message: string;
  type: 'info' | 'success' | 'warning' | 'error' | 'system';
  data?: Record<string, any>;
  read_at?: string;
  action_url?: string;
  action_text?: string;
  created_at: string;
  updated_at: string;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
}

export interface SystemSetting {
  id: number;
  key: string;
  value: string;
  type: 'string' | 'number' | 'boolean' | 'json' | 'array';
  description?: string;
  group?: string;
  is_public: boolean;
  created_at: string;
  updated_at: string;
}

export interface Role {
  id: number;
  name: string;
  display_name?: string;
  description?: string;
  permissions?: Permission[];
  created_at: string;
  updated_at: string;
}

export interface Permission {
  id: number;
  name: string;
  display_name?: string;
  description?: string;
  guard_name: string;
  created_at: string;
  updated_at: string;
}
