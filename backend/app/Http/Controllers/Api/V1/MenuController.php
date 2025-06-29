<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    /**
     * Get user's accessible menus.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $menus = $user->getAccessibleMenus();
            
            // Build hierarchical menu structure
            $menuTree = $this->buildMenuTree($menus);
            
            return response()->json([
                'status' => 'success',
                'data' => MenuResource::collection($menuTree)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch menus',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all menus (admin only).
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $this->authorize('manage-menus');
            
            $menus = Menu::with(['roles', 'parent', 'children'])
                ->orderBy('order_by')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => MenuResource::collection($menus)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch all menus',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new menu.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->authorize('manage-menus');
            
            $request->validate([
                'name' => 'required|string|max:100',
                'label' => 'required|string|max:100',
                'icon' => 'nullable|string|max:50',
                'route' => 'nullable|string|max:100',
                'url' => 'nullable|string|max:255',
                'parent_id' => 'nullable|exists:menus,id',
                'order_by' => 'integer|min:0',
                'is_active' => 'boolean',
                'permissions' => 'nullable|array',
                'permissions.*' => 'string',
                'role_ids' => 'nullable|array',
                'role_ids.*' => 'exists:roles,id',
            ]);

            $menu = Menu::create($request->except('role_ids'));
            
            // Sync roles
            if ($request->has('role_ids')) {
                $menu->roles()->sync($request->role_ids);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Menu created successfully',
                'data' => new MenuResource($menu->load(['roles', 'parent', 'children']))
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create menu',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update menu.
     */
    public function update(Request $request, Menu $menu): JsonResponse
    {
        try {
            $this->authorize('manage-menus');
            
            $request->validate([
                'name' => 'required|string|max:100',
                'label' => 'required|string|max:100',
                'icon' => 'nullable|string|max:50',
                'route' => 'nullable|string|max:100',
                'url' => 'nullable|string|max:255',
                'parent_id' => 'nullable|exists:menus,id',
                'order_by' => 'integer|min:0',
                'is_active' => 'boolean',
                'permissions' => 'nullable|array',
                'permissions.*' => 'string',
                'role_ids' => 'nullable|array',
                'role_ids.*' => 'exists:roles,id',
            ]);

            $menu->update($request->except('role_ids'));
            
            // Sync roles
            if ($request->has('role_ids')) {
                $menu->roles()->sync($request->role_ids);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Menu updated successfully',
                'data' => new MenuResource($menu->load(['roles', 'parent', 'children']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update menu',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete menu.
     */
    public function destroy(Menu $menu): JsonResponse
    {
        try {
            $this->authorize('manage-menus');
            
            // Check if menu has children
            if ($menu->children()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete menu with child items'
                ], 422);
            }
            
            $menu->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Menu deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete menu',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build hierarchical menu tree.
     */
    private function buildMenuTree($menus, $parentId = null): \Illuminate\Support\Collection
    {
        return $menus->where('parent_id', $parentId)->map(function ($menu) use ($menus) {
            $menu->children = $this->buildMenuTree($menus, $menu->id);
            return $menu;
        })->values();
    }
}
