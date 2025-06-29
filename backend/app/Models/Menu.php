<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Menu extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'label',
        'icon',
        'route',
        'url',
        'order_by',
        'is_active',
        'permissions',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'permissions' => 'array',
        'order_by' => 'integer',
    ];

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'label', 'route', 'is_active', 'permissions'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the parent menu.
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menus.
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order_by');
    }

    /**
     * Get the roles that can access this menu.
     */
    public function roles()
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            'menu_role',
            'menu_id',
            'role_id'
        );
    }

    /**
     * Scope for active menus.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root menus (no parent).
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get menu tree structure.
     */
    public static function getTree($parentId = null)
    {
        return self::with(['children' => function ($query) {
            $query->active()->orderBy('order_by');
        }])
        ->active()
        ->where('parent_id', $parentId)
        ->orderBy('order_by')
        ->get();
    }

    /**
     * Check if menu is accessible by user.
     */
    public function isAccessibleBy(User $user): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check if user has role that can access this menu
        $userRoleIds = $user->roles->pluck('id');
        $menuRoleIds = $this->roles->pluck('id');

        if ($userRoleIds->intersect($menuRoleIds)->isEmpty()) {
            return false;
        }

        // Check permissions if specified
        if (!empty($this->permissions)) {
            foreach ($this->permissions as $permission) {
                if (!$user->hasPermissionTo($permission)) {
                    return false;
                }
            }
        }

        return true;
    }
}
