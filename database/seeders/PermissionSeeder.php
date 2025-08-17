<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Dashboard permissions
        $dashboardPermissions = [
            'dashboard',
        ];

        // Role permissions
        $rolePermissions = [
            'role-view',
            'role-add',
            'role-edit',
            'role-delete',
        ];

        // User permissions
        $userPermissions = [
            'user-view',
            'user-add',
            'user-edit',
            'user-delete',
        ];

        // Karyawan permissions
        $karyawanPermissions = [
            'karyawan-view',
            'karyawan-add',
            'karyawan-edit',
            'karyawan-delete',
        ];

        // Project Manager permissions
        $projectManagerPermissions = [
            'project_manager-view',
            'project_manager-add',
            'project_manager-edit',
            'project_manager-delete',
        ];

        // Customer permissions
        $customerPermissions = [
            'customer-view',
            'customer-add',
            'customer-edit',
            'customer-delete',
        ];

        // Vendor permissions
        $vendorPermissions = [
            'vendor-view',
            'vendor-add',
            'vendor-edit',
            'vendor-delete',
        ];

        // Lokasi Project permissions
        $lokasiProjectPermissions = [
            'lokasi_project-view',
            'lokasi_project-add',
            'lokasi_project-edit',
            'lokasi_project-delete',
        ];

        // Jenis Kapal permissions
        $jenisKapalPermissions = [
            'jenis_kapal-view',
            'jenis_kapal-add',
            'jenis_kapal-edit',
            'jenis_kapal-delete',
        ];

        // Pekerjaan permissions
        $pekerjaanPermissions = [
            'pekerjaan-view',
            'pekerjaan-add',
            'pekerjaan-edit',
            'pekerjaan-delete',
        ];

        // Kategori permissions
        $kategoriPermissions = [
            'kategori-view',
            'kategori-add',
            'kategori-edit',
            'kategori-delete',
        ];

        // Sub Kategori permissions
        $subKategoriPermissions = [
            'sub_kategori-view',
            'sub_kategori-add',
            'sub_kategori-edit',
            'sub_kategori-delete',
        ];

        // Setting Pekerjaan permissions
        $settingPekerjaanPermissions = [
            'setting_pekerjaan-view',
        ];

        // On Request permissions
        $onRequestPermissions = [
            'on_request-view',
            'on_request-add',
            'on_request-detail',
        ];

        // On Progress permissions
        $onProgressPermissions = [
            'on_progress-view',
        ];

        // Complete permissions
        $completePermissions = [
            'complete-view',
        ];

        // Laporan permissions
        $laporanPermissions = [
            'laporan_customer-view',
            'laporan_customer-detail',
            'laporan_vendor-view',
            'laporan_project_manager-view',
            'laporan_lokasi_project-view',
            'laporan-vendor',
            'laporan-customer',
            'complete-laporan-vendor',
            'complete-laporan-customer',
        ];

        // Recent Activity permissions
        $recentActivityPermissions = [
            'recent-activity-harga-vendor',
            'recent-activity-harga-customer',
        ];

        // Edit permissions (specific to on progress and complete)
        $editPermissions = [
            'edit-pekerjaan-detail',
            'edit-pekerjaan-vendor',
            'edit-harga-vendor',
            'edit-harga-customer',
            'edit-vendor-job-category',
            'edit-customer-bill',
            'edit-vendor-bill',
            'edit-unit',
            'edit-amount',
            'edit-customer-bill-complete',
            'edit-vendor-bill-complete',
            'edit-unit-complete',
            'edit-amount-complete',
            'complete-edit-pekerjaan-vendor',
            'complete-edit-harga-vendor',
            'complete-edit-harga-customer',
        ];

        // Satisfaction Note permissions (commented in navbar but included for completeness)
        $satisfactionNotePermissions = [
            'satisfaction_note-view',
        ];

        // Combine all permissions
        $allPermissions = array_merge(
            $dashboardPermissions,
            $rolePermissions,
            $userPermissions,
            $karyawanPermissions,
            $projectManagerPermissions,
            $customerPermissions,
            $vendorPermissions,
            $lokasiProjectPermissions,
            $jenisKapalPermissions,
            $pekerjaanPermissions,
            $kategoriPermissions,
            $subKategoriPermissions,
            $settingPekerjaanPermissions,
            $onRequestPermissions,
            $onProgressPermissions,
            $completePermissions,
            $laporanPermissions,
            $recentActivityPermissions,
            $editPermissions,
            $satisfactionNotePermissions
        );

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

        // Assign all permissions to Super Admin
        $superAdminRole->givePermissionTo($allPermissions);

        $this->command->info('Permissions and roles created successfully!');
    }
}
