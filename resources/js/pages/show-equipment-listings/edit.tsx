import AppLayout from '@/layouts/app-layout';
import { ShowEquipmentListing, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { ShowEquipmentListingForm } from '@/components/forms/show-equipment-listing-form';

interface Props {
    showEquipment: ShowEquipmentListing;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Edit Show Equipment',
        href: '#',
    },
];

export default function ShowEquipmentEdit({ showEquipment }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Show Equipment Listing" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Edit Show Equipment Listing</CardTitle>
                            <CardDescription>Update the details for your show equipment listing.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ShowEquipmentListingForm showEquipment={showEquipment} />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}