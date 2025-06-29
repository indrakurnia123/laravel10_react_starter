<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'label' => $this->label,
            'icon' => $this->icon,
            'route' => $this->route,
            'url' => $this->url,
            'order_by' => $this->order_by,
            'is_active' => $this->is_active,
            'permissions' => $this->permissions,
            'description' => $this->description,
            'parent' => $this->whenLoaded('parent', function () {
                return new MenuResource($this->parent);
            }),
            'children' => $this->when(
                isset($this->children) && $this->children->isNotEmpty(),
                function () {
                    return MenuResource::collection($this->children);
                }
            ),
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'display_name' => $role->display_name ?? $role->name,
                    ];
                });
            }),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
