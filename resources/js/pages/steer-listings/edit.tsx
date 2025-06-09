import AppLayout from '@/layouts/app-layout';
import { SteerListing, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { SteerListingForm } from '@/components/forms/steer-listing-form';

interface Props {
    steer: SteerListing;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Listings',
        href: '/dashboard',
    },
    {
        title: 'Edit Steer',
        href: '#',
    },
];

export default function ListingsEdit({ steer }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Steer Listing" />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70">
                    <Card className="shadow-none border-none">
                        <CardHeader>
                            <CardTitle>Edit Steer Listing</CardTitle>
                            <CardDescription>Update the details for your steer listing.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <SteerListingForm steer={steer} />
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
} 