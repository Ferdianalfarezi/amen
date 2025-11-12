<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'departemen',
        'role',
        'status',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
    ];

    /**
     * Relationship dengan Drawing
     */
    public function drawings()
    {
        return $this->hasMany(\App\Models\Drawing::class);
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperadmin()
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has permission for specific action and module
     */
    public function hasPermission($module, $action)
    {
        // Superadmin bypass all permissions
        if ($this->isSuperadmin()) {
            return true;
        }

        // Download is always allowed for everyone
        if ($action === 'download') {
            return true;
        }

        // Check permissions array
        $permissions = $this->permissions ?? [];
        
        return isset($permissions[$module][$action]) && $permissions[$module][$action] === true;
    }

    /**
     * Get user permissions (for display)
     */
    public function getPermissions()
    {
        if ($this->isSuperadmin()) {
            return $this->getAllPermissionsEnabled();
        }

        return $this->permissions ?? self::getDefaultPermissions();
    }

    /**
     * Get all permissions with enabled status
     */
    private function getAllPermissionsEnabled()
    {
        $permissions = self::getDefaultPermissions();
        
        foreach ($permissions as $module => &$actions) {
            foreach ($actions as $action => &$value) {
                $value = true;
            }
        }
        
        return $permissions;
    }

    /**
     * Get default permissions structure
     */
    public static function getDefaultPermissions()
    {
        return [
            'drawings' => [
                'view' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'files_3d' => [
                'view' => false,
                'upload' => false,
                'delete' => false,
            ],
            'files_2d' => [
                'view' => false,
                'upload' => false,
                'delete' => false,
            ],
            'sample_parts' => [
                'view' => false,
                'upload' => false,
                'delete' => false,
            ],
            'quality' => [
                'view' => false,
                'upload' => false,
                'delete' => false,
            ],
            'setup_procedures' => [
                'view' => false,
                'upload' => false,
                'delete' => false,
            ],
            'quotes' => [
                'view' => false,
                'upload' => false,
                'delete' => false,
            ],
            'work_instructions' => [
                'view' => false,
                'upload' => false,
                'delete' => false,
            ],
            'user_management' => [
                'view' => false,
                'create' => false,
                'edit' => false,
                'delete' => false,
            ],
        ];
    }

    /**
     * Get available modules list
     */
    public static function getAvailableModules()
    {
        return [
            'drawings' => [
                'label' => 'Drawings',
                'actions' => ['view', 'create', 'update', 'delete']
            ],
            'files_3d' => [
                'label' => 'Files 3D',
                'actions' => ['view', 'upload', 'delete']
            ],
            'files_2d' => [
                'label' => 'Files 2D',
                'actions' => ['view', 'upload', 'delete']
            ],
            'sample_parts' => [
                'label' => 'Sample Parts',
                'actions' => ['view', 'upload', 'delete']
            ],
            'quality' => [
                'label' => 'Quality',
                'actions' => ['view', 'upload', 'delete']
            ],
            'setup_procedures' => [
                'label' => 'Setup Procedures',
                'actions' => ['view', 'upload', 'delete']
            ],
            'quotes' => [
                'label' => 'Quotes',
                'actions' => ['view', 'upload', 'delete']
            ],
            'work_instructions' => [
                'label' => 'Work Instructions',
                'actions' => ['view', 'upload', 'delete']
            ],
            'user_management' => [
                'label' => 'User Management',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],
        ];
    }

    /**
     * Get default permissions by role
     */
    public static function getDefaultPermissionsByRole($role)
    {
        $permissions = self::getDefaultPermissions();

        if ($role === 'superadmin') {
            // Superadmin doesn't need permissions (will bypass)
            return null;
        }

        if ($role === 'admin') {

        // Admin mendapat semua akses KECUALI user_management
        foreach ($permissions as $module => &$actions) {
            foreach ($actions as $action => &$value) {

                if ($module === 'user_management') {
                    $value = false; // ← admin TIDAK boleh akses user management
                } else {
                    $value = true; // module lain full access
                }
            }
        }

        return $permissions;
    }


        // User role - only view enabled for modules EXCEPT user_management
        foreach ($permissions as $module => &$actions) {
            foreach ($actions as $action => &$value) {
                // User biasa dapat view semua module KECUALI user_management
                if ($module === 'user_management') {
                    $value = false; // user_management harus di-enable manual
                } else {
                    $value = ($action === 'view'); // view only untuk module lain
                }
            }
        }

        return $permissions;
    }
}