import AppLayout from '@/layouts/app-layout';
import { ServiceListing, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { ServiceListingForm } from '@/components/forms/service-listing-form';

interface Props {
    service: ServiceListing;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Edit Service',
        href: '#',
    },
];

export default function ServiceEdit({ service }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Service Listing" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Edit Service Listing</CardTitle>
                            <CardDescription>Update the details for your service listing.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ServiceListingForm service={service} />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}

