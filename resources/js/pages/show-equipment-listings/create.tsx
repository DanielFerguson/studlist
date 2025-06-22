import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { ShowEquipmentListingForm } from '@/components/forms/show-equipment-listing-form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'List Show Equipment',
        href: '/show-equipment/create',
    },
];

export default function ShowEquipmentCreate() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List Show Equipment" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>List Show Equipment</CardTitle>
                            <CardDescription>Create a new listing for your show equipment.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ShowEquipmentListingForm />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}